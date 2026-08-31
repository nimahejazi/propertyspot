# TODO — Design Flaws & Remediation

Captured from architecture review (Aug 2026). Prioritized; file references point at current code.

## High — Security

- [ ] **Hide sensitive User attributes in serialization** — add `api_token`, `reset_token`, `stripe_customer_id` to `$hidden` in `laravel/app/Models/User.php:45`. Currently they leak into any `User` JSON response.
- [ ] **Hash API tokens at rest** — tokens are stored plaintext and never expire/rotate. Use Laravel 8's token-guard hashing (SHA-256), add rotation endpoint.
- [ ] **Throttle auth endpoints** — `/signin`, `/signup`, `/forgot-password`, `/reset-password` have zero rate limiting (brute force + email enumeration). Wrap in `throttle` middleware in `routes/web.php`.
- [ ] **Use constant-time comparison for reset tokens** — `isResetTokenValid` uses `==`; switch to `hash_equals` (`laravel/app/Models/User.php:110`).
- [ ] **Stop baking `.env.prod` into the Docker image** — secrets (Stripe, Bugsnag, SMTP) live in image layers; mount at runtime / use secrets manager instead. Credential rotation currently requires rebuild.
- [ ] **Constrain `users.role`** — free-text string set ad-hoc (`AdminUserController.php:59`); enum/casted value object so typos can't silently break `can:accessAdmin` gating.
- [ ] **Harden Stripe webhook** (`StripeWebhookController.php:40`) — verify `payment_amount` matches the listing's price/currency before marking paid; distinguish `charge.succeeded` vs `payment_intent.succeeded`; add idempotency (skip if `payment_status === 'paid'`).

## Medium — Correctness / Data Integrity

- [ ] **Add unique index on `listings.slug`** — currently nullable with no unique constraint; `createSlug()` count-then-insert loop is a TOCTOU race → duplicate slugs under concurrent payments (`laravel/app/Models/Listing.php:153`, migration `2020_10_03_001657`). Add index + retry logic.
- [ ] **Index `slug` for the catch-all query** — `GET /{slug}` does an un-indexed lookup + eager loads on every page view; add DB index and consider response caching for published sites.

## Low — Ops / Hygiene

- [ ] **Upgrade EOL stack** — Laravel 8 (EOL security fixes), MySQL 5.7 (EOL Oct 2023), TS 3.9, node-sass 4.x. Version pins that fixed builds are now blocking security patches.
- [ ] **Reduce supply-chain fragility** — private VCS packages require `~/.ssh/id_rsa` at build time; use authenticated composer/npm tokens instead of raw SSH keys.
- [ ] **Abstract photo storage** — files go straight to Docker-volume-backed `public/img/` (no Storage facade/S3); blocks scaling beyond a single node.
- [ ] **Move image-upload endpoints out of `vendor/`** — core upload logic lives in private `rk-image-api` package; harder to audit/patch than app-side code.
- [ ] **Improve deploy pipeline** — no health checks, no rollback story, `migrate --force` on deploy; parallel CI runs would trample shared `_test` MySQL containers.