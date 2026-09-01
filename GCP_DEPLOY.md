# Production on GCP — Runbook (e2-micro, ≤ $10/mo)

One GCP Compute Engine **e2-micro** VM runs docker-compose: `nginx-proxy` +
`acme-companion` in front, `propertyspot_laravel` (php-fpm + nginx) and
`propertyspot_db` (mysql:5.7) behind it, with **Cloudflare** (free plan) proxying
and edge-caching photos. **Images are never built on the VM** — the Mac builds
them and pushes to Artifact Registry; the VM only pulls.

> Prereq: this history has had secrets stripped (see DEPLOY.md appendix). Fresh
> clones are safe. The two untracked env files still must be scp'd (§7).

## Why these choices (decided Aug 2026, do not silently re-litigate)

- **e2-micro, not e2-small/medium**: budget is $10/mo hard cap. e2-micro is the
  only machine that fits (~$6.30 compute + ~$1.20 pd-standard disk in us-west1).
  It is *enough* only because of the three mitigations below.
- **No on-VM builds**: the Dockerfile builder stage (`npm ci` + webpack 5 +
  node 15) needs ~1–1.5 GB alone — OOM-killed on a 1 GB VM. Hence build on Mac →
  Artifact Registry → VM pulls (`docker-compose.prod.yml` overlay).
- **Ephemeral IP, not static**: a reserved IP costs $3.65/mo and blows the
  budget. Ephemeral IP changes only on VM **stop/start** (not reboot) — after
  such an event, update the two A records in Cloudflare (§12). Cloudflare in
  front makes this even more livable.
- **Cloudflare in front (free plan)**: edge-caches `public/img/*` — this is the
  only egress-controlling lever (images are served full-size; imagick generates
  no variants) and offloads the 0.25 vCPU. SSL mode: **Full** (Let's Encrypt
  stays on the VM). If first cert issuance struggles with proxy on, flip the
  records to DNS-only (gray cloud) briefly, then back.
- **Fresh deploy, no old data**: DEPLOY.md §4/§6 (data migration, DB restore)
  do not apply. Admin account comes from `db:seed`.
- Cost guardrail: set a **billing alert at $10** in the GCP console (egress is
  the only line that can spike).

## Budget

| Item | $/mo |
|---|---|
| e2-micro (us-west1) | ~$6.30 |
| 30 GB pd-standard boot disk | ~$1.20 |
| Artifact Registry ≤ 0.5 GB image | ~$0 (always-free tier) |
| Egress (photos served via Cloudflare cache) | ~$0–1 |
| IP | $0 (ephemeral) |
| **Total** | **~$7.50–8.50** |

---

## 1. GCP project + provisioning (one-time, on the Mac)

```bash
gcloud projects create propertyspot --set-as-default   # or reuse a project
gcloud config set compute/zone us-west1-b
gcloud services enable compute.googleapis.com artifactregistry.googleapis.com

# Image registry (≤0.5 GB storage = always-free tier)
gcloud artifacts repositories create propertyspot \
  --repository-format=docker --location=us-west1

# VM: x86_64 required (mysql:5.7 has no arm64). pd-standard, 30 GB.
gcloud compute instances create propertyspot \
  --machine-type=e2-micro --zone=us-west1-b \
  --image-family=ubuntu-2204-lts --image-project=ubuntu-os-cloud \
  --boot-disk-size=30GB --boot-disk-type=pd-standard \
  --tags=propertyspot
# NOTE: no --address reservation — ephemeral IP on purpose.

gcloud compute firewall-rules create allow-http-https \
  --direction=INGRESS --action=ALLOW --rules=tcp:80,tcp:443 \
  --target-tags=propertyspot

# Note the ephemeral IP for DNS:
gcloud compute instances describe propertyspot --zone=us-west1-b \
  --format='get(networkInterfaces[0].accessConfigs[0].natIP)'
```

## 2. VM bring-up (one-time)

```bash
# on the VM (ssh into it):
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker deploy   # then re-login

# 1 GB survival kit:
sudo fallocate -l 2G /swapfile && sudo chmod 600 /swapfile && \
  sudo mkswap /swapfile && sudo swapon /swapfile
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
echo 'vm.swappiness=15' | sudo tee /etc/sysctl.d/99-swap.conf
sudo sysctl --system

# log rotation (else the tiny disk fills):
sudo tee /etc/docker/daemon.json <<'EOF'
{"log-driver":"json-file","log-opts":{"max-size":"10m","max-file":"3"}}
EOF
sudo systemctl restart docker

# Artifact Registry pull auth for the VM:
sudo apt-get install -y apt-transport-https ca-certificates gnupg curl
gcloud auth login   # or: curl token from metadata + docker login (below)
gcloud auth configure-docker us-west1-docker.pkg.dev
```

Simplest AR auth path: grant the VM's default service account
`roles/artifactregistry.reader`, then on the VM:

```bash
TOKEN=$(curl -s -H 'Metadata-Flavor: Google' \
  'http://metadata.google.internal/computeMetadata/v1/instance/service-accounts/default/token' \
  | cut -d'"' -f4)
echo "oauth2accesstoken:$TOKEN" | docker login us-west1-docker.pkg.dev -u oauth2accesstoken --password-stdin
```

## 3. Cloudflare (before first deploy)

- Add `propertyspot.net` zone (free plan); Cloudflare imports registrar NS —
  confirm at the registrar.
- Two A records: `@` and `www` → the VM's ephemeral IP, **Proxied** (orange).
- SSL/TLS mode: **Full** (not strict-strict yet; cert arrives in §6).
- Note: GCP's HTTP-01 challenge flows through the Cloudflare proxy — if it
  fails, temporarily gray-cloud both records until the first cert issues
  (§6), then re-enable proxy.

## 4. Code + secrets on the VM

```bash
sudo mkdir -p /opt/propertyspot && sudo chown deploy:deploy /opt/propertyspot
git clone git@github.com:nimahejazi/propertyspot.git /opt/propertyspot/app
```

- Deploy key: `ssh-keygen -t ed25519` on VM, public key → GitHub deploy key
  (read-only). **GitHub may take a few minutes to activate a freshly added
  deploy key** — if `ssh -T git@github.com` says `Permission denied (publickey)`
  right after adding it, wait ~5 min and retry before debugging anything else.
- `ln -s ~/.ssh/id_ed25519 ~/.ssh/id_rsa` — scripts/Dockerfile expect id_rsa.
- **Root `.env`** (compose `MYSQL_*` creds **and** `GCP_PROJECT_ID=propertyspot-net`
  for the AR image reference in `docker-compose.prod.yml`):
  `scp .env deploy@vm:/opt/propertyspot/app/.env`
- **`laravel/.env.prod`**: `scp laravel/.env.prod deploy@vm:/opt/propertyspot/app/laravel/.env.prod`
  Fresh deploy → new `APP_KEY` is fine (generate locally).

## 5. Frontend network + proxy (one-time)

```
docker network create nginx-proxy

docker run -d --name nginx-proxy --restart always \
  -p 80:80 -p 443:443 \
  -v /var/run/docker.sock:/tmp/docker.sock:ro \
  -v nginx-certs:/etc/nginx/certs \
  -v nginx-vhost:/etc/nginx/vhost.d \
  -v nginx-html:/usr/share/nginx/html \
  --network nginx-proxy nginxproxy/nginx-proxy

docker run -d --name letsencrypt-companion --restart always \
  -v /var/run/docker.sock:/var/run/docker.sock:ro \
  -v nginx-certs:/etc/nginx/certs \
  -v nginx-vhost:/etc/nginx/vhost.d \
  -v nginx-html:/usr/share/nginx/html \
  --network nginx-proxy nginxproxy/acme-companion
```

(`VIRTUAL_HOST`/`LETSENCRYPT_*` in `docker-compose.yml` already point at
propertyspot.net + www.)

## 6. Build + first deploy

```bash
# on the Mac (build needs ~/.ssh/id_rsa for private nimahejazi/* deps):
./build-push.sh

# on the VM:
cd /opt/propertyspot/app
./deploy-vm.sh
# deploy-vm.sh = docker compose pull && up -d (yaml + prod overlay) && migrate --force

# first run only: seed users, then IMMEDIATELY change the admin password
# (seeded creds are in AGENTS.md / tests — treat as public):
./artisan db:seed
```

If mysql was slow on first boot ("Connection refused" from migrate), re-run
`./deploy-vm.sh` — idempotent.

## 7. Verify (in this order)

1. `curl -I https://propertyspot.net` → 200/302, valid cert (Cloudflare edge
   cert is visible; origin LE cert is behind it)
2. Log in as seeded admin → dashboard renders
3. Create a listing, publish, open `https://propertyspot.net/<slug>` → site
   renders; photos upload and load (writes `public_img` volume)
4. Password reset email arrives (Brevo SMTP — host baked into image)
5. `GET /test-bugsnag` → 200, event lands in Bugsnag
6. Stripe: one test-mode transaction; webhook hits `POST /stripe/payment-hook`
7. `curl -sI https://propertyspot.net/img/<any-photo>` → `cf-cache-status: HIT`
   on second request (proves Cloudflare edge caching works)

## 8. Backups (day 1)

```bash
crontab -e
15 4 * * * cd /opt/propertyspot/app && ./backup.sh >> /var/log/propertyspot-backup.log 2>&1
```

`backup.sh` keeps 14 days locally. Optional offsite: point its `S3_BUCKET`
branch at any S3-compatible bucket, or `gcloud storage cp` to a GCS bucket
(~$0.02/GB/mo). Restore: `gunzip -c <file>.sql.gz | docker exec -i
propertyspot_db mysql ...`.

## 9. Normal deploys (from the Mac)

```bash
./build-push.sh
ssh deploy@VM 'cd /opt/propertyspot/app && git pull && ./deploy-vm.sh'
```

`deploy.sh`/`test.sh` (on-VM build + test stack) are **not** used in prod;
they remain for local/Jenkins-style testing.

## 10. Ops notes

- Ephemeral IP changes on VM **stop/start**: re-read the IP (§1 last command)
  and update the two Cloudflare A records. Reboots are unaffected.
- Billing alert at $10 (console → Billing → budgets).
- Boot disk snapshot policy is cheap insurance: `gcloud compute disks
  create-snapshot` manually, or add a snapshot schedule.

## 11. Memory tuning (baked into `docker-compose.prod.yml`)

- `mem_limit`: laravel 700M, db 450M — OOM kills are scoped, not host-wide.
- mysql: `innodb_buffer_pool_size=64M`, `performance_schema=OFF`,
  `max_connections=30` — idles ~200–220 MB instead of ~400.
- php: `pm.max_children=3` — imagick on a 10 MB upload spikes hard; 3 workers +
  swap survives, more would OOM.
- Host: 2 GB swap (§2), swappiness 15.

## Known gotchas

Everything in DEPLOY.md "Known gotchas" still applies, plus:

- **1 GB host**: concurrent photo uploads are the OOM hot spot. If uploads
  intermittently 502, check `dmesg` for OOM kills before touching code.
- **Never build on the VM** — not even `./deploy.sh`; it runs `npm run prod`
  in the builder stage and will OOM.
- Cloudflare proxied + HTTP-01: see §3 note.
- `.env.prod` is baked into the image — credential changes = `./build-push.sh`
  + redeploy, not restart.
- Egress is the budget's only variable line; Cloudflare caching is what keeps
  it near $0. If Cloudflare is ever removed, re-check egress costs.

## Appendix: future options (parked)

- **Static IP**: if stop/start IP churn annoys, 1-yr CUD (~37% off) +
  static IP ≈ $8.80/mo total. Only if ephemeral proves painful.
- **GitHub Actions build**: when deploys get frequent — build+push on git push
  (`SSH_KEY` as repo secret, AR push auth via SA JSON secret). Deploy stays
  `ssh + ./deploy-vm.sh`.
- **Bigger VM**: e2-small (2 GB) ≈ $13.40 + disk — over budget on GCP; Hetzner
  CX22 (2 vCPU/4 GB) ≈ $5 if GCP stops being a requirement.