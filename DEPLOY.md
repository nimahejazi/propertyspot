# Production Deployment — Single-VM Runbook

Status quo preserved: one VM runs docker-compose with `nginx-proxy` +
`letsencrypt-companion` in front, `propertyspot_laravel` (php-fpm + nginx) and
`propertyspot_db` (mysql:5.7) behind it. Zero code changes needed for this move.

> Before starting: THIS history has already had secrets stripped (see
> "Appendix: history" at the bottom). Fresh clones are safe to hand to the VM.

---

## 1. Provision VM

- Ubuntu 22.04+x86_64, **2 vCPU / 4 GB RAM min** (mysql 5.7 + php7.4 under one roof), 40 GB disk
- Provider suggestion: Hetzner CX22 / DO s-2vcpu-4gb / Lightsail 2vcpu — anywhere with fast disk I/O
- Open ports 22, 80, 443
- Install: `curl -fsSL https://get.docker.com | sh` (includes compose v2)
- Add a non-root user with docker group, e.g. `deploy`

## 2. Code + secrets on the VM

```
sudo mkdir -p /opt/propertyspot && sudo chown deploy:deploy /opt/propertyspot
git clone git@github.com:nimahejazi/propertyspot.git /opt/propertyspot/app
```

- Deploy key: generate `ssh-keygen -t ed25519` on VM, add the **public** key on
  GitHub (repo → Settings → Deploy keys → read-only).
- `ln -s ~/.ssh/id_ed25519 ~/.ssh/id_rsa` — `test.sh`/`deploy.sh` and the
  Dockerfile npm stage all expect `~/.ssh/id_rsa`.
- **Root `.env`** (compose credentials) — transfer from your machine, NOT via git:
  `scp .env deploy@vm:/opt/propertyspot/app/.env`
- **`laravel/.env.prod`** — same way:
  `scp laravel/.env.prod deploy@vm:/opt/propertyspot/app/laravel/.env.prod`
  (check `APP_KEY` is the real production key — this decrypts nothing but signs
  sessions; keep the one from the old server to keep sessions valid)

## 3. nginx-proxy + Let's Encrypt (frontend)

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

(`VIRTUAL_HOST`/`LETSENCRYPT_*` env vars in `docker-compose.yml` already point
at propertyspot.net + www.)

**DNS before first deploy:** lower TTL a day ahead; point A records for
`propertyspot.net` and `www` at the VM IP *before* `up` so the cert bot can
complete the HTTP-01 challenge.

## 4. Data migration from the old server (run ON THE OLD HOST)

```
# DB
docker exec propertyspot_db /usr/bin/mysqldump -upropertyspot -p'<old pw>' \
  --single-transaction --routines --triggers propertyspot \
  | gzip > /tmp/propertyspot_final.sql.gz

# uploaded images (user avatars, listing photos) + app storage
docker run --rm -v propertyspot_public_img:/data -v /tmp:/backup alpine \
  tar czf /backup/public_img.tgz -C /data .
docker run --rm -v propertyspot_storage:/data -v /tmp:/backup alpine \
  tar czf /backup/storage.tgz -C /data .

scp /tmp/propertyspot_final.sql.gz /tmp/public_img.tgz /tmp/storage.tgz deploy@vm:/tmp/
```

On the NEW VM (before first `up`, so volumes are pristine):

```
cd /opt/propertyspot/app
docker network create nginx-proxy   # skip if done in step 3

# create volumes
docker volume create propertyspot_db_data
docker volume create propertyspot_public_img
docker volume create propertyspot_storage

# seed images/storage
docker run --rm -v propertyspot_public_img:/data -v /tmp:/backup alpine \
  sh -c "cd /data && tar xzf /backup/public_img.tgz"
docker run --rm -v propertyspot_storage:/data -v /tmp:/backup alpine \
  sh -c "cd /data && tar xzf /backup/storage.tgz"
```

## 5. First deploy

```
cd /opt/propertyspot/app
export JENKINS_HOME_HOST=$(pwd)     # script quirk: required even outside Jenkins
./deploy.sh
# deploy.sh = composer install, SSH_KEY=$(cat ~/.ssh/id_rsa) docker-compose up --build -d,
#             migrate --force
```

If MySQL was too slow on first boot and migrate fails with "Connection refused",
just re-run `./deploy.sh` — it's idempotent.

## 6. Restore DB (new VM)

```
gunzip -c /tmp/propertyspot_final.sql.gz | \
  docker exec -i propertyspot_db /usr/bin/mysql -upropertyspot \
    -p"$(grep '^MYSQL_PASSWORD=' .env | cut -d= -f2-)" propertyspot
```

## 7. Verify (in this order)

1. `curl -I https://propertyspot.net` → 200/302, valid Let's Encrypt cert
2. Log in with a real account → dashboard renders
3. Open any listing site (`https://propertyspot.net/<slug>`) → photos load (proves public_img restored)
4. Upload one image from the dashboard → appears (writes to new volume)
5. Password reset email arrives (Brevo SMTP, host baked into image)
6. `GET /test-bugsnag` → 200, event lands in Bugsnag
7. Stripe: run one real (cheap or test-mode-alternative) transaction; check webhook hit

## 8. Backups on the VM (do on day 1)

```
sudo apt install -y awscli   # optional, for offsite
crontab -e
15 4 * * * cd /opt/propertyspot/app && ./backup.sh >> /var/log/propertyspot-backup.log 2>&1
```

Restore drill: `gunzip -c <file>.sql.gz | docker exec -i propertyspot_db mysql ...`

## 9. Cutover

- Old server keeps running untouched until step 7 passes
- When verified: old server → stop cron, take final mysqldump, `docker-compose down`
- Propagation check: `dig +short propertyspot.net` shows the new IP globally

## 10. Jenkins

The old Jenkins deploy flow is dead after the move. Simplest replacement
(team of one): from your Mac,

```
ssh deploy@vm 'cd /opt/propertyspot/app && git pull && JENKINS_HOME_HOST=$(pwd) ./deploy.sh'
```

Or keep Jenkins and install an agent on the VM — the Jenkinsfile works as-is
apart from `JENKINS_HOME_HOST=/opt/propertyspot` (see `test.sh`).

## Known gotchas (all verified during local bring-up)

- `.env.prod` is **baked into the image** — changing any credential = rebuild + redeploy, not restart
- Prod/test Dockerfile needs `SSH_KEY` build arg (deploy.sh sets it from `~/.ssh/id_rsa`)
- `mysql:5.7` has no arm64 images — x86_64 VM only, or `platform: linux/amd64` + Rosetta locally
- external network `nginx-proxy` must exist before any `docker-compose up` in this repo
- `deploy.sh` runs `composer install` first; a failed prior run can leave vendor/ in a broken
  state — `./composer install` retries are safe
- Log rotation: add to `/etc/docker/daemon.json`:
  `{"log-driver":"json-file","log-opts":{"max-size":"10m","max-file":"3"}}` then
  `systemctl restart docker`

## Appendix: history secrets

`git filter-repo` (see commit history of this file) removed:
- `laravel/.env*` (APP_KEYs, live Stripe `sk_live_*`, SMTP creds, Slack webhook, DB passwords)
- literal DB passwords from compose files / `mysql` wrapper (replaced by `${MYSQL_*}`)

All exposed credentials were rotated externally: Stripe (regenerated), Brevo SMTP
key (regenerated), Bugsnag key (regenerated), MySQL passwords (regenerated into
untracked root `.env`). Old SendinBlue hostname retired to smtp-relay.brevo.com.