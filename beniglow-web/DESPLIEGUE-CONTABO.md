# Despliegue de BeniGlow Store en Contabo

Este archivo resume el despliegue de BeniGlow Store en un VPS Ubuntu usando Docker Compose para aislar PHP 8.3, Nginx del host como reverse proxy y MariaDB privada dentro del proyecto.

## Estado del despliegue

- VPS: Ubuntu 24.04 LTS en `31.220.102.218`
- Zona horaria del servidor: `America/Lima`
- Dominio preparado en Nginx: `beniglow.com` y `www.beniglow.com`
- DNS: activo hacia `31.220.102.218`
- HTTPS: activo con Let's Encrypt para `beniglow.com` y `www.beniglow.com`
- Certificado vigente hasta: `2026-08-27`
- URL final: `https://beniglow.com/`
- Credenciales iniciales generadas: `/root/beniglow-store-credentials.txt`

## Arquitectura usada

- Proyecto: `/srv/apps/beniglow-store/current`
- Aplicación Laravel: contenedor `beniglow_app` con PHP 8.3 FPM
- Servidor web interno: contenedor `beniglow_web` con Nginx, publicado solo en `127.0.0.1:8083`
- Base de datos: contenedor `beniglow_db` con MariaDB, sin puerto público
- Reverse proxy del host: Nginx del VPS recibe el dominio y reenvía a `127.0.0.1:8083`
- SSL: Certbot en el host, cuando el DNS del dominio apunte al VPS
- Backups: `/srv/apps/beniglow-store/backups`

Esta separación permite alojar otros proyectos después con otro puerto interno, otra red Docker y otra versión de PHP, sin mezclar dependencias.

## DNS en Porkbun

En Porkbun se debe configurar el dominio así:

- `A` para `beniglow.com` apuntando a `31.220.102.218`
- `CNAME` para `www` apuntando a `beniglow.com`, o un `A` para `www.beniglow.com` apuntando a `31.220.102.218`

El dominio se relaciona con la aplicación así:

1. Porkbun resuelve `beniglow.com` hacia la IP del VPS con un registro `A`.
2. `www.beniglow.com` apunta al dominio principal con `CNAME`.
3. Nginx del host recibe el tráfico público en puertos `80` y `443`.
4. Certbot instaló el certificado SSL en Nginx.
5. Nginx reenvía la petición al contenedor interno en `127.0.0.1:8083`.
6. El contenedor `beniglow_web` entrega Laravel mediante PHP-FPM en `beniglow_app`.

Registros DNS esperados:

```text
A      beniglow.com       31.220.102.218
CNAME  www.beniglow.com   beniglow.com
```

Si alguna vez se debe reemitir el certificado:

```bash
sudo certbot --nginx -d beniglow.com -d www.beniglow.com --redirect
sudo systemctl reload nginx
```

Después de activar HTTPS, verificar:

```bash
curl -I https://beniglow.com
curl -I https://www.beniglow.com
```

Las imágenes del catálogo no se suben manualmente al servidor para el catálogo inicial. El seeder `BeniglowCatalogSeeder` toma las imágenes fuente de `database/seeders/assets/beniglow-productos`, genera WebP y las copia a `public/uploads/productos`. Esos archivos generados se ignoran en Git porque son runtime/uploads.

## Variables de producción

En el servidor se crea un `.env` fuera de Git dentro de `/srv/apps/beniglow-store/current/.env`.

Valores principales:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://beniglow.com`
- `DB_HOST=db`
- `DB_DATABASE=beniglow_store`
- `BENIGLOW_NOMBRE_COMERCIAL="BeniGlow Store"`
- `BENIGLOW_RAZON_SOCIAL="Beniglow E.I.R.L."`
- `BENIGLOW_DIRECCION="Ciudad de Tacna, Perú"`

Las contraseñas reales y claves generadas no se deben subir al repositorio.

## Comandos útiles

Entrar a la carpeta:

```bash
cd /srv/apps/beniglow-store/current
```

Ver estado:

```bash
docker compose ps
docker compose logs -f app
docker compose logs -f web
```

Crear backup manual:

```bash
/srv/apps/beniglow-store/current/scripts/backup-beniglow.sh
```

Reiniciar:

```bash
docker compose restart
sudo systemctl reload nginx
```

Actualizar desde Git cuando exista repositorio remoto:

```bash
cd /srv/apps/beniglow-store/current
git pull
docker compose run -T --rm app composer install --no-dev --optimize-autoloader
docker compose run -T --rm node npm ci
docker compose run -T --rm node npm run build:store
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose up -d --force-recreate app web
```

También queda disponible:

```bash
/srv/apps/beniglow-store/current/scripts/update-beniglow.sh
```

Si se actualizan extensiones PHP o el Dockerfile:

```bash
docker compose build app
docker compose up -d
```

## Backups

Los backups básicos se guardan en:

```bash
/srv/apps/beniglow-store/backups
```

Incluyen:

- dump de MariaDB (`.sql.gz`)
- archivos importantes de Laravel (`storage`, `public/uploads`, `.env`)

## Seguridad mínima

- Base de datos sin exposición pública.
- Firewall UFW solo con SSH, HTTP y HTTPS.
- `.env` ignorado por Git.
- `APP_DEBUG=false`.
- Contenedores separados por red Docker del proyecto.
- Permisos de escritura solo para `storage`, `bootstrap/cache` y uploads.
- Contraseña inicial de administrador generada fuerte; se recomienda cambiarla desde el panel.
- Recomendado: cambiar la contraseña root del VPS después del despliegue, porque fue compartida temporalmente para esta instalación.

## Rutas principales

- Tienda pública: `https://beniglow.com/` y `https://beniglow.com/tienda`
- Login administrativo: `https://beniglow.com/login`
- Dashboard: `https://beniglow.com/dashboard`
- API catálogo: `https://beniglow.com/api/catalogo/productos`
- Pedidos web: `POST https://beniglow.com/api/pedidos-web`
