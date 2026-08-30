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
- Frontend build: `cd laravel && npm run dev` (or `production`). `Dockerfile-dev` does **not** build assets — you must run npm yourself. The prod/test `Dockerfile` builds assets in a node stage and requires the `SSH_KEY` build arg (both `test.sh` and `deploy.sh` set it from `~/.ssh/id_rsa`) to install private `github:robotkudos/*` npm packages.
- Private `robotkudos/*` packages exist in both `composer.json` and `package.json`.

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

## Architecture

- Each published property is served as its own site at `GET /{slug}` (`WebsiteController::showWebsite`). This catch-all is the **last route** in `routes/web.php` — add new routes above it.
- Both route files are active: `web.php` = pages + form/POST endpoints; `api.php` = token-authenticated (`auth:api`) JSON API used by the React front-end.
- Auth: session auth on web, `users.api_token` on api. Middleware alias `check-user` → `CheckIfUserCanAccessListing`. Admin routes gated by `can:accessAdmin`, defined in `AuthServiceProvider` as `role === 'admin'` (`users.role` is a plain string, no enum table).
- Password reset is custom: token + timestamp on the `users` table, 30-minute validity enforced by `User::isResetTokenValid` — the model is the source of truth, not `config/auth.php`.
- `User` fires the `Registered` event on `created` via `$dispatchesEvents` — creating users in tests/seeders triggers verification mail (array/log mailer in test env).
- Stripe (`stripe-php` 7.x): payment-intent endpoints in `api.php`; hook route `POST /stripe/payment-hook` in `web.php`.
- Bugsnag reporting; smoke-test route `GET /test-bugsnag`.
- Frontend: laravel-mix 6 + Sass + React/TS (`webpack.mix.js`). Builds versioned `bundle.js`, `simple.js`, `main.css`, `simple.css`, plus React bundles `propertyspot-dashboard.js` / `rk-instant-list.js`; copies `resources/img/` → `public/img/`.

## Conventions

- Eloquent relations use string class names (`$this->hasMany('App\Models\Listing')`), not `::class` — follow this in models.
- Don't rename old migrations — they are already deployed.
- Style: StyleCI preset `laravel` with `no_unused_imports` disabled (`laravel/.styleci.yml`); there is no local lint command.
- Per-environment configs are committed: `laravel/.env`, `.env.testing`, `.env.prod`, `.env.dusk`.

## CI/CD

`Jenkinsfile` runs `./test.sh` then `./deploy.sh` (both need `JENKINS_HOME_HOST`; failure cleanup removes the `_test` containers). `deploy.sh` rebuilds prod containers (`docker-compose.yml`, site `propertyspot.net` behind nginx-proxy) and runs `artisan migrate --force`.