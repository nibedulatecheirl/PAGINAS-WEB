# Pasos para desplegar cambios de BeniGlow Store

Este archivo explica como actualizar manualmente BeniGlow Store cuando hagas cambios en el proyecto.

## Datos importantes

- Proyecto local: `E:\2026 - PROYECTOS\PAGINAS-WEB\beniglow-web`
- Proyecto en servidor: `/srv/apps/beniglow-store/current`
- Dominio: `https://beniglow.com`
- Contenedor PHP: `beniglow_app`
- Contenedor web interno: `beniglow_web`
- Base de datos: `beniglow_store`

No copies ni subas estos archivos/carpetas:

```text
.env
vendor
node_modules
storage/logs
bootstrap/cache
public/storage
public/backups
```

## 1. Entrar al servidor por SSH

Desde Windows PowerShell:

```powershell
ssh root@31.220.102.218
```

Luego escribe la contrasena del VPS cuando la terminal la pida.

Si mas adelante configuras una llave SSH:

```powershell
ssh -i C:\ruta\a\tu_llave root@31.220.102.218
```

## 2. Ir a la carpeta del proyecto en el servidor

Ya dentro del servidor:

```bash
cd /srv/apps/beniglow-store/current
```

Revisar contenedores:

```bash
docker compose ps
```

Crear backup antes de actualizar:

```bash
/srv/apps/beniglow-store/current/scripts/backup-beniglow.sh
```

## 3. Forma recomendada: actualizar desde Git

En tu PC, antes de subir cambios:

```powershell
cd "E:\2026 - PROYECTOS\PAGINAS-WEB\beniglow-web"
npm ci
npm run build:store
php artisan route:cache
php artisan route:clear
php artisan view:clear
php artisan view:cache
git status
```

Si todo esta bien:

```powershell
git add .
git commit -m "Actualizar BeniGlow Store"
git push
```

En el servidor:

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
sudo systemctl reload nginx
```

Verificar:

```bash
curl -I https://beniglow.com
curl -I https://beniglow.com/login
curl -I https://beniglow.com/api/catalogo/productos
tail -50 storage/logs/laravel.log
```

## 4. Forma manual: copiar solo el compilado frontend

Usa esto si solo cambiaste archivos de la tienda en `public/store/src`.

En tu PC:

```powershell
cd "E:\2026 - PROYECTOS\PAGINAS-WEB\beniglow-web"
npm ci
npm run build:store
```

El compilado queda en:

```text
E:\2026 - PROYECTOS\PAGINAS-WEB\beniglow-web\public\store\dist
```

Copiarlo al servidor:

```powershell
scp -r "E:\2026 - PROYECTOS\PAGINAS-WEB\beniglow-web\public\store\dist" root@31.220.102.218:/srv/apps/beniglow-store/current/public/store/
```

En el servidor:

```bash
cd /srv/apps/beniglow-store/current
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose restart app web
sudo systemctl reload nginx
```

## 5. Forma manual: copiar archivos PHP o Blade puntuales

Ejemplos desde Windows PowerShell:

```powershell
scp "E:\2026 - PROYECTOS\PAGINAS-WEB\beniglow-web\app\Http\Controllers\UsuarioController.php" root@31.220.102.218:/srv/apps/beniglow-store/current/app/Http/Controllers/UsuarioController.php

scp "E:\2026 - PROYECTOS\PAGINAS-WEB\beniglow-web\resources\views\usuarios\index.blade.php" root@31.220.102.218:/srv/apps/beniglow-store/current/resources/views/usuarios/index.blade.php

scp "E:\2026 - PROYECTOS\PAGINAS-WEB\beniglow-web\resources\views\usuarios\roles.blade.php" root@31.220.102.218:/srv/apps/beniglow-store/current/resources/views/usuarios/roles.blade.php
```

Despues, dentro del servidor:

```bash
cd /srv/apps/beniglow-store/current
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose restart app web
sudo systemctl reload nginx
```

## 6. Si cambias composer.json o composer.lock

En el servidor:

```bash
cd /srv/apps/beniglow-store/current
docker compose run -T --rm app composer install --no-dev --optimize-autoloader
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose restart app web
```

## 7. Si cambias base de datos o migraciones

En el servidor:

```bash
cd /srv/apps/beniglow-store/current
/srv/apps/beniglow-store/current/scripts/backup-beniglow.sh
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
```

## 8. Comandos utiles

Ver logs de Laravel:

```bash
tail -100 /srv/apps/beniglow-store/current/storage/logs/laravel.log
```

Ver logs de contenedores:

```bash
cd /srv/apps/beniglow-store/current
docker compose logs -f app
docker compose logs -f web
```

Reiniciar aplicacion:

```bash
cd /srv/apps/beniglow-store/current
docker compose restart app web
sudo systemctl reload nginx
```

Verificar SSL:

```bash
certbot certificates
certbot renew --dry-run --cert-name beniglow.com
```

## 9. Regla de oro

Antes de cualquier actualizacion:

```bash
/srv/apps/beniglow-store/current/scripts/backup-beniglow.sh
```

Despues de cualquier actualizacion:

```bash
curl -I https://beniglow.com
curl -I https://beniglow.com/login
tail -50 /srv/apps/beniglow-store/current/storage/logs/laravel.log
```
