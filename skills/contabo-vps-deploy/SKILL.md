---
name: contabo-vps-deploy
description: >-
  Deploy or update separated projects from the PAGINAS-WEB workspace on a Contabo Ubuntu VPS, especially Laravel/PHP apps using Docker Compose, Nginx reverse proxy, MariaDB, optional SSL, and production validation. Use when Codex is asked to deploy, redeploy, publish, update, diagnose, standardize, or document a Contabo VPS web deployment for folders such as beniglow-web, crm-colegio-web, or future sibling projects. Default policy: keep each project isolated, preserve folder structure, do not create automatic backups unless explicitly requested, and enable Certbot only when a real domain is configured and resolving.
---

# Contabo VPS Deploy

## Core Workflow

Use this skill for Contabo VPS deployments from `PAGINAS-WEB`. Keep secrets out of files, commits, logs, and final answers. If the user provides credentials, use them only for the active operation and never echo passwords back.

## Standard Policy

- Keep every project separated under its own local folder and remote app directory.
- Preserve the project structure from its source folder; do not flatten or mix files between apps.
- Use unique names for Compose project, containers, Docker volumes, Docker networks, Nginx site files, database names, and internal ports.
- Do not create automatic backups, cron jobs, or systemd timers unless the user explicitly asks for them.
- For risky database/file operations, ask before creating a one-time manual backup unless the user has already approved it.
- If a deployment has no domain, publish by IP/port or a temporary reverse proxy without SSL.
- If a real domain exists, first verify DNS points to the VPS; then configure Nginx for `80`, issue SSL with Certbot, and redirect HTTP to HTTPS.

1. Discover the project shape:
   - Read the project README/deployment docs.
   - Inspect `docker-compose.yml`, `Dockerfile`, `docker/nginx`, `scripts/backup-*.sh`, `scripts/update-*.sh`, `.env.example`, and package/composer files.
   - Identify whether the deployment is full Laravel app, static site, or a small frontend-only change.
2. Preserve production state:
   - Confirm remote app path under `/srv/apps/<app>/current`.
   - Confirm the project is isolated from sibling apps and uses unique service names.
   - Never overwrite remote `.env`, `storage`, uploads, database volumes, or runtime caches with local copies.
3. Choose the smallest safe update path:
   - For storefront-only HTML/JS/CSS changes, upload only the changed public files and rebuild/caches only as needed.
   - For PHP, routes, migrations, Composer, Dockerfile, or env-related changes, run the fuller deployment flow.
   - Prefer project update scripts when they are present and known to be current.
4. Validate:
   - Check `docker compose ps`.
   - Hit the public URL and important routes with `curl -I`.
   - Check Laravel logs and web container logs.
   - Verify the exact changed asset or UI state from the deployed URL.

## Reference

For the reusable Laravel/Docker/Contabo command flow, read `references/laravel-docker-contabo.md`.

## Workspace Conventions

- Source workspace: `E:\2026 - PROYECTOS\PAGINAS-WEB`.
- Remote base path: `/srv/apps`.
- Existing Contabo host used by current projects: `31.220.102.218`.
- BeniGlow production path: `/srv/apps/beniglow-store/current`.
- CRM Colegio demo path: `/srv/apps/crm-colegio/current`.

Do not assume ports, domains, container names, database names, or app names for new projects. Infer them from project files and remote state.
