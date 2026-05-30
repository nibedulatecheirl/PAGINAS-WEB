# Despliegue demo de CRM Colegio en Contabo

Este archivo resume el despliegue demo realizado en el VPS Contabo. El proyecto queda
publicado por IP y puerto, sin dominio y sin SSL por ahora.

## URL final

```text
http://31.220.102.218:8004
```

## Arquitectura

```text
Usuario
  -> http://31.220.102.218:8004
  -> Nginx del VPS escuchando en 8004
  -> proxy a 127.0.0.1:18084
  -> contenedor crm_colegio_web
  -> contenedor crm_colegio_app con PHP 8.3
  -> contenedor crm_colegio_db con MariaDB
```

La base de datos no se expone publicamente. Solo el puerto 8004 queda abierto hacia
Internet para este demo.

Docker Compose tiene nombre fijo:

```yaml
name: crm-colegio
```

Esto evita conflictos con otros proyectos desplegados en carpetas llamadas `current`.

## Que se hizo

1. Se limpio el proyecto local para no subir `vendor`, `.env`, caches, logs, SQL antiguos ni archivos de documentacion/imagenes que no eran necesarios para ejecutar Laravel.
2. Se preparo Docker para PHP 8.3 FPM, Nginx interno y MariaDB 11.4.
3. Se creo la estructura remota en `/srv/apps/crm-colegio/current`.
4. Se subio el proyecto limpio al VPS como paquete, porque para este demo no se uso URL Git.
5. Se creo `.env` desde `.env.demo.example` con `APP_URL=http://31.220.102.218:8004`.
6. Se generaron contrasenas internas nuevas para MariaDB en el `.env` remoto.
7. Se ejecuto `composer install --no-dev --optimize-autoloader`.
8. Se genero `APP_KEY`.
9. Se ejecuto `migrate:fresh --seed --force` para crear la base demo desde cero.
10. Se ejecuto `storage:link` y las caches de Laravel.
11. Se configuro Nginx del host como reverse proxy en el puerto 8004.
12. Se abrio `8004/tcp` en UFW.
13. Se dejo backup diario en `/etc/cron.d/crm-colegio-backup`.
14. Se corrigio el nombre de Compose de BeniGlow en el servidor para evitar cruces entre proyectos.

## Datos demo

El seeder crea usuarios de prueba visibles en la pantalla de login:

```text
admin@colegio.edu.pe / admin123
docente@colegio.edu.pe / admin123
estudiante@colegio.edu.pe / admin123
```

Esto es intencional para la etapa demo. En produccion se deben cambiar.

## Base de datos

La base usada en el contenedor MariaDB es:

```text
colegio_crm
```

Para este demo se usan migraciones y seeders:

```bash
docker compose exec -T app php artisan migrate:fresh --seed --force
```

No se importaron los SQL antiguos, porque las migraciones actuales estan mas completas
y reproducen mejor el estado de la aplicacion.

## Comandos usados en el VPS

```bash
cd /srv/apps/crm-colegio/current
cp .env.demo.example .env
docker compose config --quiet
docker compose build app
docker compose up -d db
docker compose run -T --rm app composer install --no-dev --optimize-autoloader
docker compose up -d
docker compose exec -T app php artisan key:generate --force
docker compose exec -T app php artisan migrate:fresh --seed --force
docker compose exec -T app php artisan storage:link --force
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
```

## Nginx del host

La configuracion esta en `deploy/nginx/crm-colegio-demo.conf`.

Copiar a:

```bash
/etc/nginx/sites-available/crm-colegio-demo
```

Activar:

```bash
ln -sfn /etc/nginx/sites-available/crm-colegio-demo /etc/nginx/sites-enabled/crm-colegio-demo
nginx -t
systemctl reload nginx
```

Abrir firewall:

```bash
ufw allow 8004/tcp
```

## Backups

El script esta en:

```text
/srv/apps/crm-colegio/current/scripts/backup-crm-colegio.sh
```

Los backups quedan en:

```text
/srv/apps/crm-colegio/backups
```

Se programo una tarea diaria:

```text
/etc/cron.d/crm-colegio-backup
```

Para ejecutar un backup manual:

```bash
/srv/apps/crm-colegio/current/scripts/backup-crm-colegio.sh
```

## Validacion

```bash
curl -I http://31.220.102.218:8004
curl -I http://31.220.102.218:8004/login
docker compose ps
tail -50 storage/logs/laravel.log
```

Validacion realizada:

```text
GET /                  -> 302 a /login
GET /login             -> 200
POST /login admin demo -> 302 a /dashboard
GET /dashboard         -> 200
composer audit         -> sin vulnerabilidades conocidas en dependencias de produccion
```

## Actualizar manualmente

Si el servidor queda conectado a Git:

```bash
ssh root@31.220.102.218
cd /srv/apps/crm-colegio/current
./scripts/update-crm-colegio.sh
```

Si aun no se usa Git, subir un nuevo paquete limpio del proyecto a
`/srv/apps/crm-colegio/current`, conservar el `.env` remoto y ejecutar:

```bash
cd /srv/apps/crm-colegio/current
docker compose run -T --rm app composer install --no-dev --optimize-autoloader
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan storage:link --force
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose up -d --force-recreate app web
systemctl reload nginx
```
