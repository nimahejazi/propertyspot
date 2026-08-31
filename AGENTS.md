# PropertySpot

Laravel 8 app for hosting single-property websites. The app lives in `laravel/`; all PHP/Composer execution runs inside Docker via the root wrapper scripts — **never run `php artisan` or `composer` on the host**.

## Commands

Run everything from the repo root:

```bash
./deploy-local.sh     # one-time local setup: dev compose up + composer install + migrate:fresh --seed
./artisan <cmd>       # docker exec $CONTAINER_NAME php artisan (default container: propertyspot_laravel)
./composer <cmd>      # one-shot composer:1.10.16 container mounting $WORKSPACE/laravel
./mysql               # mysql client into the dev DB container (propertyspot_db)
```

- All three compose files use an **external Docker network `nginx-proxy`** — create it first (`docker network create nginx-proxy`) or `up` fails.
- Frontend build: `cd laravel && npm run dev` (or `production`). `Dockerfile-dev` does **not** build assets — you must run npm yourself. The prod/test `Dockerfile` builds assets in a node stage and requires the `SSH_KEY` build arg (both `test.sh` and `deploy.sh` set it from `~/.ssh/id_rsa`) to install private `github:nimahejazi/*` npm packages.
- Private `nimahejazi/*` packages exist in both `composer.json` and `package.json` (formerly `robotkudos/*`; e.g. `rk-image-api`, `RKImage`, `propertyspot-dashboard`, `rk-instant-list`). `composer.json` carries two VCS repositories (`https://github.com/nimahejazi/rk-image-api`, `https://github.com/nimahejazi/RKImage`) to pull patched versions.
- **Build pinning**: the Dockerfile builder runs `npm ci` against `package-lock.json`, and the npm toolchain is pinned to exact last-known-good versions (`laravel-mix 6.0.11`, `webpack 5.21.2`, `webpack-cli 4.5.0`, `bulma 0.9.2`, `typescript 3.9.9`, `sass 1.32.7`, …). Floating ranges broke the Docker build when upstreams released incompatible releases (webpack ≥5.8x removed `webpack/lib/SizeFormatHelpers`, mix 6.0.49 + TS 5 fails under ts-loader 8). Don't re-broaden the `^` ranges casually, and verify with a full `docker build -f Dockerfile --build-arg SSH_KEY=...` after any bump. `tsconfig.json` is COPY'd into the builder — its `skipLibCheck` is required (newer transitive `.d.ts` files don't parse under TS 3.9).

## Testing

```bash
./test.sh                                   # full CI pipeline: test containers, migrate:fresh --seed, phpunit + dusk
./artisan test --filter=UserTest            # focused PHPUnit run (needs dev stack up)
./artisan test tests/Feature/AdminTest.php
./artisan dusk --filter=LoginTest           # Dusk browser test (needs selenium container)
```

Gotchas:

- `test.sh` requires `JENKINS_HOME_HOST`; locally: `export JENKINS_HOME_HOST=$(pwd)`. It targets containers `propertyspot_laravel_test` / `propertyspot_db_test` / `propertyspot_selenium`.
- Tests run against the **MySQL container**, not sqlite — the sqlite lines in `phpunit.xml` are commented out (`.env.testing` points at `propertyspot_db_test`). Bring the DB up first.
- Dusk drives `http://selenium:4444/wd/hub` (selenium service in both dev and test compose files), uses `phpunit.dusk.xml` (separate config), and writes screenshots to `laravel/tests/Browser/screenshots/`.
- Seeded logins (password `asdf1234`): `nima@robotkudos.com` (admin), `nhejazi@gmail.com` (user). Factory admin state: `User::factory()->admin()`.
- On Apple Silicon: mysql:5.7 and selenium need `platform: linux/amd64` (set in compose files) and Rosetta emulation enabled in Docker Desktop. Selenium also needs `shm_size: 2g` (test compose) or Chrome dies with "tab crashed".
- Asset build on modern host Node: use `./npm-build.sh` (Node 15.8 container; host Node 26 breaks node-sass 4.x). Needs `~/.ssh/id_rsa` (or symlink to your key) with access to private GitHub deps.
- **Token-guard caching in Feature tests**: making API requests as *different users* within one test reuses the cached `TokenGuard` user — the 2nd user's requests resolve as the 1st (production is unaffected: one request per process). Reset cached guards between users (see `PhotoApiTest::resetAuth()`); `Auth::forgetGuards()` does not work on Laravel 8 (proxies to the session guard).
- Dusk selectors break when view copy changes (button rename in `0b0eda2` silently broke `AdminTest` for months) — update `tests/Browser/*` in the same commit as button-label changes.
- Feature tests in this repo use manual `setUp`/`tearDown` cleanup (factory create + explicit deletes), not `RefreshDatabase` — follow that convention.

## Architecture

- Each published property is served as its own site at `GET /{slug}` (`WebsiteController::showWebsite`). This catch-all is the **last route** in `routes/web.php` — add new routes above it.
- Both route files are active: `web.php` = pages + form/POST endpoints; `api.php` = token-authenticated (`auth:api`) JSON API used by the React front-end.
- Auth: session auth on web, `users.api_token` on api. Middleware alias `check-user` → `CheckIfUserCanAccessListing`. Admin routes gated by `can:accessAdmin`, defined in `AuthServiceProvider` as `role === 'admin'` (`users.role` is a plain string, no enum table).
- Password reset is custom: token + timestamp on the `users` table, 30-minute validity enforced by `User::isResetTokenValid` — the model is the source of truth, not `config/auth.php`.
- `User` fires the `Registered` event on `created` via `$dispatchesEvents` — creating users in tests/seeders triggers verification mail (array/log mailer in test env).
- Stripe (`stripe-php` 7.x): payment-intent endpoints in `api.php`; hook route `POST /stripe/payment-hook` in `web.php`.
- Bugsnag reporting; smoke-test route `GET /test-bugsnag`. Requires `BugsnagServiceProvider` enabled in `config/app.php` (package discovery alone doesn't register the `bugsnag` channel).
- Mail: Brevo SMTP relay (`smtp-relay.brevo.com`, port 587/TLS). SMTP **username is the dedicated `*@smtp-brevo.com` login, not the account email**. `.env.prod` is baked into the image at build time — credential changes require a rebuild, not a restart.
- Frontend: laravel-mix 6 + Sass + React/TS (`webpack.mix.js`). Builds versioned `bundle.js`, `simple.js`, `main.css`, `simple.css`, plus React bundles `propertyspot-dashboard.js` / `rk-instant-list.js`; copies `resources/img/` → `public/img/`.
- **All `public/js` + `public/css` are gitignored build artifacts** — never edit them; change `resources/` and rebuild.
- **Image upload** lives in the private `rk-image-api` package, not app controllers: upload/rename/delete/reorder endpoints are closures in `vendor/robotkudos/rk-image-api/src/routes/images.php`, configured app-side via `config/rkimageapi.php` (table `property_photos`, middleware `['auth:api','check-user','throttle:image-api']`, `max_items` 50, `max_size` 10240 KB). Files go straight to `public/img/` (no Storage facade/S3). Any new single-photo endpoint must scope by **both** `key` (listing id) *and* photo id — the `check-user` middleware validates `key` only; scoping by id alone is the IDOR pattern that was fixed in package 1.0.2. Server rejects images < 1500px wide (`ImageTooSmallException` → 422) — test fixtures need explicit dimensions (e.g. `UploadedFile::fake()->image('x.jpg', 1600, 1000)`). Deleting a listing must call `PropertyPhoto::deleteWithFiles()` per photo (see `AdminController::deleteListing`); photo API tests: `tests/Feature/PhotoApiTest.php`.

## Conventions

- Eloquent relations use string class names (`$this->hasMany('App\Models\Listing')`), not `::class` — follow this in models.
- Don't rename old migrations — they are already deployed.
- Style: StyleCI preset `laravel` with `no_unused_imports` disabled (`laravel/.styleci.yml`); there is no local lint command.
- All `.env*` files are untracked (secrets); `laravel/.env.example` (prod-shaped) and `laravel/.env.dev.example` (dev-shaped) are the checked-in templates.

## CI/CD

`Jenkinsfile` runs `./test.sh` then `./deploy.sh` (both need `JENKINS_HOME_HOST`; failure cleanup removes the `_test` containers). `deploy.sh` rebuilds prod containers (`docker-compose.yml`, site `propertyspot.net` behind nginx-proxy) and runs `artisan migrate --force`.