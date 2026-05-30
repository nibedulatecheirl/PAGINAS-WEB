# Despliegue demo de CRM Colegio en Contabo

Este proyecto esta preparado para desplegarse como demo por IP y puerto, sin dominio.

## URL demo propuesta

```text
http://31.220.102.218:8084
```

## Arquitectura

```text
Usuario
  -> http://31.220.102.218:8084
  -> Nginx del VPS escuchando en 8084
  -> proxy a 127.0.0.1:18084
  -> contenedor crm_colegio_web
  -> contenedor crm_colegio_app con PHP 8.3
  -> contenedor crm_colegio_db con MariaDB
```

La base de datos no se expone publicamente.

## Datos demo

El seeder crea usuarios de prueba visibles en la pantalla de login:

```text
admin@colegio.edu.pe / admin123
docente@colegio.edu.pe / admin123
estudiante@colegio.edu.pe / admin123
```

Esto es intencional para la etapa demo. En produccion se deben cambiar.

## Base de datos

La base recomendada es:

```text
colegio_crm
```

Para este demo se recomienda usar migraciones y seeders:

```bash
docker compose exec -T app php artisan migrate:fresh --seed --force
```

No se recomienda importar los SQL antiguos, porque las migraciones actuales estan mas completas.

## Comandos de despliegue en VPS

```bash
cd /srv/apps/crm-colegio/current
cp .env.demo.example .env
docker compose build app
docker compose up -d db
docker compose run -T --rm app composer install --no-dev --optimize-autoloader
docker compose exec -T app php artisan key:generate --force
docker compose exec -T app php artisan migrate:fresh --seed --force
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose up -d
```

## Nginx del host

Copiar `deploy/nginx/crm-colegio-demo.conf` a:

```bash
/etc/nginx/sites-available/crm-colegio-demo
```

Activar:

```bash
ln -s /etc/nginx/sites-available/crm-colegio-demo /etc/nginx/sites-enabled/crm-colegio-demo
nginx -t
systemctl reload nginx
```

Abrir firewall:

```bash
ufw allow 8084/tcp
```

## Validacion

```bash
curl -I http://31.220.102.218:8084
curl -I http://31.220.102.218:8084/login
docker compose ps
tail -50 storage/logs/laravel.log
```
