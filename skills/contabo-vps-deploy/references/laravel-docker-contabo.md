# Laravel Docker Contabo Reference

## Standard Structure

Keep every project independent. Do not mix files, containers, databases, ports, logs, or Nginx configs across apps.

Local workspace:

```text
E:\2026 - PROYECTOS\PAGINAS-WEB
  <project-folder>/
    app/
    public/
    resources/
    routes/
    docker/
    scripts/
    docker-compose.yml
    Dockerfile
```

Remote VPS:

```text
/srv/apps/<project-slug>/
  current/            # active project files
  shared/             # optional future shared runtime data
```

Recommended naming for each project:

```text
project-slug:       beniglow-store
compose name:       beniglow-store
containers:         beniglow_app, beniglow_web, beniglow_db
network:            beniglow_network
db volume:          beniglow_db_data
remote path:        /srv/apps/beniglow-store/current
nginx site:         /etc/nginx/sites-available/beniglow-store
internal web port:  127.0.0.1:8083, 127.0.0.1:8084, etc.
```

For new projects, choose unused names and ports after inspecting the VPS. Never reuse a container, network, volume, Nginx site name, or bound port from another project.

## Secrets

- Never write VPS passwords, `.env` contents, database passwords, API keys, or admin passwords into repo files.
- Avoid commands that put passwords directly in process arguments when a safer client/library can use them in memory.
- If SSH password auth is the only available path, prefer a transient automation layer and clean it up afterward.
- Never upload local `.env` over a production `.env`.

## Backup Policy

Default policy for this workspace: do not create automatic backups.

- Do not add cron jobs or systemd timers for backups unless the user explicitly asks.
- Do not run backup scripts automatically as a deployment habit.
- For risky operations, such as migrations, destructive deletes, replacing uploads, or changing storage paths, ask whether to create a one-time manual backup.
- If the user says no backups, respect that and continue with non-destructive validation steps.
- If an old automatic backup schedule exists, disable it rather than editing unrelated system cron files.

## Local Preparation

From the project directory, run only commands that exist for that project:

```powershell
git status --short
npm ci
npm run build:store
```

For Laravel apps, clear or rebuild local caches only when useful:

```powershell
php artisan route:clear
php artisan view:clear
php artisan config:clear
```

Do not copy local `vendor`, `node_modules`, `storage/logs`, `bootstrap/cache`, `public/storage`, backups, or generated uploads to production.

## Remote Discovery

Use SSH to inspect the target:

```bash
cd /srv/apps/<project-slug>/current
pwd
docker compose ps
ls -la
git status --short || true
```

Inspect global VPS state before adding or moving projects:

```bash
ls -la /srv/apps
docker ps --format 'table {{.Names}}\t{{.Ports}}\t{{.Status}}'
docker network ls
docker volume ls
ss -tulpn | grep -E ':(80|443|8000|8001|8002|8003|8004|8083|8084|8085|18084)\b' || true
ls -la /etc/nginx/sites-available /etc/nginx/sites-enabled
```

## Deployment Types

### Existing Frontend-Only Update

Use this when only static storefront assets changed, such as `public/store/index.html`, `public/store/dist/*`, images, CSS, or JS.

Upload only the changed files. Then run the minimal remote refresh:

```bash
cd /srv/apps/<project-slug>/current
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose restart app web
systemctl reload nginx
```

If the app is purely static and not Laravel, skip Artisan commands and only reload Nginx when its config changed.

### Existing Full Laravel Update

When Git is configured and the remote branch is correct:

```bash
cd /srv/apps/<project-slug>/current
git pull --ff-only
docker compose run -T --rm app composer install --no-dev --optimize-autoloader
docker compose run -T --rm node npm ci
docker compose run -T --rm node npm run build:store
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan storage:link --force
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose up -d --force-recreate app web
systemctl reload nginx
```

Skip `node` lines when the project has no frontend build. Ask before migrations if the user has not approved database changes.

If the Dockerfile or base image changed:

```bash
docker compose build app
docker compose up -d --force-recreate app web
```

## Nginx, Domain, SSL

### Without Domain

If there is no domain, do not configure Certbot and do not promise HTTPS.

Use one of these:

```text
http://<server-ip>:<public-demo-port>
```

or an Nginx server block that listens on a chosen public port and proxies to the app's internal port.

Open the firewall only for the chosen public port:

```bash
ufw allow <public-demo-port>/tcp
```

Use this for demos or temporary previews. Keep the database private and expose only the web entry point.

### With Domain

If there is a real domain:

1. Confirm DNS points to the VPS:

```bash
dig +short example.com
dig +short www.example.com
```

2. Configure Nginx on port `80` for the domain and proxy to the app's internal port.
3. Test and reload Nginx:

```bash
nginx -t
systemctl reload nginx
```

4. Issue SSL only after DNS works:

```bash
certbot --nginx -d example.com -d www.example.com
```

5. Confirm HTTPS and redirect behavior:

```bash
curl -I http://example.com
curl -I https://example.com
certbot certificates
```

## Validation

Run remote checks:

```bash
cd /srv/apps/<project-slug>/current
docker compose ps
curl -I <public-url>
curl -I <public-url>/login
tail -50 storage/logs/laravel.log
docker compose logs --tail=80 web
```

For storefront changes, fetch the deployed files and verify expected tokens:

```bash
curl -s <public-url>/store/index.html | grep -- '--product-title'
curl -s <public-url>/store/dist/components.js | grep -- 'product-card-title'
```

For visual changes, open the deployed page in a browser and inspect computed styles or take a screenshot.

## Do Not Do

- Do not create automatic backup schedules by default.
- Do not activate SSL for an IP-only deployment.
- Do not expose MariaDB ports publicly.
- Do not share Docker volumes or networks between unrelated apps unless the user explicitly requests a shared service.
- Do not overwrite production `.env` or generated uploads with local copies.
- Do not reuse a demo public port for a second project.
