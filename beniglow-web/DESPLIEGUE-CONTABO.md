# Despliegue de BeniGlow Store en Contabo

Este documento resume el despliegue de BeniGlow Store en el VPS Contabo. La idea principal fue aislar Laravel con Docker Compose, dejar Nginx del servidor como reverse proxy, usar HTTPS con Certbot y evitar conflictos con futuros proyectos.

## Estado actual

- VPS: Ubuntu 24.04 LTS.
- IP: `31.220.102.218`.
- Zona horaria: `America/Lima`.
- Dominio principal: `https://beniglow.com`.
- Dominio con www: `https://www.beniglow.com`.
- SSL: activo con Let's Encrypt.
- Certificado vigente hasta: `2026-08-27`.
- Proyecto: `/srv/apps/beniglow-store/current`.
- Backups externos: `/srv/apps/beniglow-store/backups`.
- Credenciales iniciales protegidas: `/root/beniglow-store-credentials.txt`.

## Que se hizo

1. Se reviso el proyecto local `beniglow-web`.
2. Se confirmo que es Laravel 11 y que puede correr bien con PHP 8.3.
3. Se preparo Docker para aislar PHP 8.3 sin instalar PHP global en el host.
4. Se agrego Docker Compose con tres servicios:
   - `beniglow_app`: PHP 8.3 FPM.
   - `beniglow_web`: Nginx interno de la app.
   - `beniglow_db`: MariaDB privada.
5. Se instalo en el VPS:
   - Docker Engine.
   - Docker Compose plugin.
   - Nginx.
   - Certbot.
   - Git, unzip y herramientas basicas.
6. Se creo la estructura limpia:
   - `/srv/apps/beniglow-store/current`
   - `/srv/apps/beniglow-store/backups`
7. Se subio el proyecto inicial desde la carpeta local porque todavia no se entrego URL Git remota.
8. Se creo `.env` de produccion fuera de Git.
9. Se generaron claves y passwords fuertes para la app, base de datos y administrador inicial.
10. Se levanto MariaDB en contenedor, sin puerto publico.
11. Se ejecuto:
    - `composer install --no-dev --optimize-autoloader`
    - `npm ci`
    - `npm run build:store`
    - `php artisan key:generate --force`
    - `php artisan migrate --seed --force`
    - `php artisan storage:link`
    - `php artisan config:cache`
    - `php artisan route:cache`
    - `php artisan view:cache`
12. Se configuro Nginx del host como reverse proxy.
13. Se configuro DNS en Porkbun hacia el VPS.
14. Se emitio SSL con Certbot para `beniglow.com` y `www.beniglow.com`.
15. Se activo UFW permitiendo solo SSH, HTTP y HTTPS.
16. Se configuro backup automatico diario a las `02:30` hora Peru.
17. Se ajusto `.gitignore` y `.dockerignore` para no subir secretos, dependencias, caches, logs ni archivos runtime.

## Como esta conectado el dominio

En Porkbun deben existir estos registros:

```text
A      beniglow.com       31.220.102.218
CNAME  www.beniglow.com   beniglow.com
```

Flujo de una visita:

```text
Usuario
  -> https://beniglow.com
  -> Nginx del VPS en puerto 443
  -> proxy interno a 127.0.0.1:8083
  -> contenedor beniglow_web
  -> contenedor beniglow_app con PHP 8.3
  -> contenedor beniglow_db si necesita base de datos
```

Los usuarios no ven el puerto interno `8083`. Ese puerto solo sirve para que Nginx del host se comunique con esta app. Para futuros proyectos se puede usar otro puerto interno, por ejemplo `8084`, `8085`, etc., siempre con Nginx decidiendo por dominio.

## Arquitectura final

```text
/srv/apps/beniglow-store/current
  Dockerfile
  docker-compose.yml
  docker/
  app/
  public/
  storage/
  .env
```

Servicios Docker:

```text
beniglow_app   PHP 8.3 FPM
beniglow_web   Nginx interno, publicado en 127.0.0.1:8083
beniglow_db    MariaDB, solo dentro de la red Docker
```

Puertos publicos del VPS:

```text
22   SSH
80   HTTP, redirige a HTTPS
443  HTTPS
```

La base de datos no esta expuesta publicamente.

## Variables importantes

El archivo real esta en:

```bash
/srv/apps/beniglow-store/current/.env
```

Valores principales:

```text
APP_ENV=production
APP_DEBUG=false
APP_URL=https://beniglow.com
DB_HOST=db
DB_DATABASE=beniglow_store
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

Datos de empresa configurados:

```text
Nombre comercial: BeniGlow Store
Razon social: Beniglow E.I.R.L.
Direccion: Ciudad de Tacna, Peru
```

## Imagenes del catalogo

No es necesario subir manualmente las imagenes iniciales del catalogo.

El seeder `BeniglowCatalogSeeder` usa las imagenes fuente de:

```bash
database/seeders/assets/beniglow-productos
```

Luego genera archivos WebP en:

```bash
public/uploads/productos
```

Esos WebP son runtime/uploads y estan ignorados por Git.

## Backups

Backups automaticos del VPS:

```bash
/srv/apps/beniglow-store/backups
```

Incluyen:

- Dump comprimido de MariaDB.
- Archivos importantes de Laravel.

Crear backup manual:

```bash
/srv/apps/beniglow-store/current/scripts/backup-beniglow.sh
```

El modulo interno de backups de Laravel guarda SQL en:

```bash
storage/app/private/backups
```

La ruta `/backups` queda bloqueada en Nginx por defensa adicional.

## Comandos utiles

Entrar al proyecto:

```bash
cd /srv/apps/beniglow-store/current
```

Ver contenedores:

```bash
docker compose ps
```

Ver logs:

```bash
docker compose logs -f app
docker compose logs -f web
```

Reiniciar la app:

```bash
docker compose restart
sudo systemctl reload nginx
```

Actualizar desde Git cuando exista remoto:

```bash
cd /srv/apps/beniglow-store/current
git pull --ff-only
docker compose run -T --rm app composer install --no-dev --optimize-autoloader
docker compose run -T --rm node npm ci
docker compose run -T --rm node npm run build:store
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan storage:link
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose up -d --force-recreate app web
```

Tambien existe el script:

```bash
/srv/apps/beniglow-store/current/scripts/update-beniglow.sh
```

Si cambia el Dockerfile:

```bash
docker compose build app
docker compose up -d --force-recreate app web
```

## Seguridad aplicada

- `APP_ENV=production`.
- `APP_DEBUG=false`.
- `.env` fuera de Git.
- `.env` con permisos restringidos.
- Firewall UFW activo.
- Solo estan abiertos SSH, HTTP y HTTPS.
- MariaDB no tiene puerto publico.
- Laravel corre aislado con PHP 8.3 dentro de Docker.
- Nginx del host rechaza hosts desconocidos.
- Nginx bloquea `/backups`.
- HTTPS activo con Let's Encrypt.
- Cookies de sesion marcadas como seguras.
- `storage`, `bootstrap/cache` y uploads tienen permisos de escritura controlados.
- Composer audit y npm audit no reportaron vulnerabilidades.

## Recomendaciones pendientes

- Cambiar la contrasena inicial del usuario administrador desde el panel.
- Cambiar la contrasena root del VPS porque fue compartida para el despliegue.
- Crear acceso SSH con llave y luego deshabilitar login root con password.
- Cuando exista el repositorio remoto, subir solo el codigo limpio y nunca subir `.env`, `vendor`, `node_modules`, logs, caches ni uploads generados.
- Revisar periodicamente los backups y probar una restauracion en un entorno de prueba.

## Rutas principales

- Tienda publica: `https://beniglow.com/`
- Tienda publica alternativa: `https://beniglow.com/tienda`
- Login administrativo: `https://beniglow.com/login`
- Dashboard: `https://beniglow.com/dashboard`
- API catalogo: `https://beniglow.com/api/catalogo/productos`
- Pedidos web: `POST https://beniglow.com/api/pedidos-web`
