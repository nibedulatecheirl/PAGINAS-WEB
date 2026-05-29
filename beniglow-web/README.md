# BeniGlow Back-office

Sistema Laravel adaptado para administrar el catalogo, stock, promociones y pedidos web de BeniGlow.

## Acceso local

- Tienda publica: http://127.0.0.1:8000/tienda
- Panel administrativo: http://127.0.0.1:8000/login
- Usuario: `admin`
- Contrasena local por defecto: `admin123`

En produccion el seeder exige `BENIGLOW_ADMIN_PASSWORD`; no se debe desplegar con una clave por defecto.

## Preparacion para Git

Sube el proyecto sin `vendor`, `node_modules`, `.env`, caches, sesiones ni logs. Esos archivos ya estan cubiertos por `.gitignore`.

Los assets compilados de la tienda estan en `public/store/dist` y deben subirse. Las imagenes fuente del catalogo estan en `database/seeders/assets/beniglow-productos`; el seeder genera los WebP en `public/uploads/productos`.

## Base de datos

El script principal esta en `database/install.sql`.

La base sugerida para despliegue es `beniglow_store`. El script incluye la estructura necesaria, roles base, usuario administrador, configuracion, empresa BeniGlow, una caja opcional para ventas presenciales, categorias, proveedor, productos y promocion inicial.

El catalogo inicial de BeniGlow se carga con `Database\Seeders\BeniglowCatalogSeeder`. Incluye:

- 5 categorias de skincare.
- 1 proveedor base: YesStyle.
- 5 productos iniciales con imagenes.
- 1 promocion de lanzamiento.

Para cargar todo en una base nueva desde Laravel:

```bash
php artisan migrate --force
php artisan db:seed --force
```

Para reiniciar completamente una base de pruebas:

```bash
php artisan migrate:fresh --seed --force
```

Para recargar solo el catalogo BeniGlow:

```bash
php artisan db:seed --class=BeniglowCatalogSeeder --force
```

Las imagenes fuente del catalogo estan en `database/seeders/assets/beniglow-productos`; el seeder las copia a `public/uploads/productos`.

## Produccion

Usa `.env.production.example` como base para el servidor. Antes de ejecutar seeders en produccion configura:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://tu-dominio.com`
- `DB_DATABASE=beniglow_store`
- `DB_USERNAME` y `DB_PASSWORD` del usuario MySQL real
- `BENIGLOW_ADMIN_PASSWORD` con una clave fuerte
- `STOREFRONT_ALLOWED_ORIGINS=https://tu-dominio.com`

Comandos base en el servidor:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Modulos clave

- Tienda publica: servida desde `public/store` y conectada al API del mismo Laravel.
- Productos: catalogo cosmetico con marca, linea, tono, presentacion, tipo de piel, precio web, oferta, SEO, visibilidad web y stock.
- Categorias: catalogo limpio para cargar categorias reales desde el panel.
- Promociones: descuentos por producto o categoria.
- Pedidos web: endpoints para registrar pedidos desde el e-commerce, confirmar pago, generar venta web y descontar stock.
- Inventario: movimientos de entrada, salida, merma y ajuste con control de stock negativo.

## Endpoints para e-commerce

- `GET /api/catalogo/categorias`
- `GET /api/catalogo/productos`
- `GET /api/catalogo/productos/{slug}`
- `GET /api/catalogo/promociones`
- `POST /api/pedidos-web`
- La confirmacion de pago se realiza desde el panel administrativo autenticado.

Si se define `STOREFRONT_API_TOKEN` en `.env`, el e-commerce debe enviar el header `X-Storefront-Token`. Para produccion se debe usar `STOREFRONT_ALLOWED_ORIGINS=https://beniglow.com`.
