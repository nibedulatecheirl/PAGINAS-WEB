<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ECommerceController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\PedidoWebController;

// Autenticación
Route::get('/', [ECommerceController::class, 'home'])->name('home');
Route::get('/tienda', [ECommerceController::class, 'storefront'])->name('storefront');

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Productos
    Route::resource('productos', ProductoController::class);
    Route::post('productos/{producto}/ajuste-stock', [ProductoController::class, 'ajusteStock'])->name('productos.ajuste-stock');
    Route::get('api/productos/buscar', [ProductoController::class, 'buscarApi'])->name('api.productos.buscar');

    // Categorías
    Route::resource('categorias', CategoriaController::class)->except(['create', 'show', 'edit']);

    // Clientes
    Route::resource('clientes', ClienteController::class);
    Route::get('api/clientes/buscar', [ClienteController::class, 'buscarApi'])->name('api.clientes.buscar');

    // Proveedores
    Route::resource('proveedores', ProveedorController::class)->parameters([
        'proveedores' => 'proveedor',
    ]);

    // Ventas - POS
    Route::get('pos', [VentaController::class, 'pos'])->name('ventas.pos');
    Route::resource('ventas', VentaController::class)->only(['index', 'store', 'show']);
    Route::get('ventas/{venta}/ticket', [VentaController::class, 'ticket'])->name('ventas.ticket');
    Route::post('ventas/{venta}/anular', [VentaController::class, 'anular'])->name('ventas.anular');

    Route::get('ecommerce', [ECommerceController::class, 'index'])->name('ecommerce.index');

    // Pedidos web - e-commerce
    Route::get('pedidos-web', [PedidoWebController::class, 'index'])->name('pedidos-web.index');
    Route::get('pedidos-web/{pedidoWeb}', [PedidoWebController::class, 'show'])->name('pedidos-web.show');
    Route::post('pedidos-web/{pedidoWeb}/confirmar-pago', [PedidoWebController::class, 'confirmarPago'])->name('pedidos-web.confirmar-pago');
    Route::post('pedidos-web/{pedidoWeb}/estado', [PedidoWebController::class, 'actualizarEstado'])->name('pedidos-web.actualizar-estado');

    // Compras
    Route::resource('compras', CompraController::class)->except(['edit', 'update', 'destroy']);

    // Caja
    Route::get('caja', [CajaController::class, 'index'])->name('caja.index');
    Route::post('caja/abrir', [CajaController::class, 'abrirTurno'])->name('caja.abrir');
    Route::post('caja/turno/{turno}/cerrar', [CajaController::class, 'cerrarTurno'])->name('caja.cerrar');
    Route::get('caja/turno/{turno}/cierre', [CajaController::class, 'cierre'])->name('caja.cierre');
    Route::post('caja/turno/{turno}/movimiento', [CajaController::class, 'movimiento'])->name('caja.movimiento');
    Route::post('caja/store', [CajaController::class, 'storeCaja'])->name('caja.store');

    // Reportes
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/ventas', [ReporteController::class, 'ventas'])->name('reportes.ventas');
    Route::get('reportes/productos', [ReporteController::class, 'productos'])->name('reportes.productos');
    Route::get('reportes/inventario', [ReporteController::class, 'inventario'])->name('reportes.inventario');
    Route::get('reportes/vencimientos', [ReporteController::class, 'vencimientos'])->name('reportes.vencimientos');

    // Promociones
    Route::resource('promociones', PromocionController::class)->only(['index', 'store', 'update', 'destroy']);

    // Solo administradores
    Route::middleware('role:Administrador')->group(function () {
        Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
        Route::post('configuracion/empresa', [ConfiguracionController::class, 'actualizarEmpresa'])->name('configuracion.empresa');
        Route::post('configuracion/general', [ConfiguracionController::class, 'actualizarConfig'])->name('configuracion.general');

        Route::get('backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('backup/crear', [BackupController::class, 'crear'])->name('backup.crear');
        Route::get('backup/{backup}/descargar', [BackupController::class, 'descargar'])->name('backup.descargar');
        Route::post('backup/restaurar', [BackupController::class, 'restaurar'])->name('backup.restaurar');
        Route::post('backup/restaurar-archivo', [BackupController::class, 'restaurarArchivo'])->name('backup.restaurar-archivo');
        Route::delete('backup/{backup}', [BackupController::class, 'eliminar'])->name('backup.eliminar');
        Route::post('backup/resetear', [BackupController::class, 'resetear'])->name('backup.resetear');

        Route::get('usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::put('usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
        Route::get('roles', [UsuarioController::class, 'roles'])->name('usuarios.roles');
        Route::post('roles', [UsuarioController::class, 'storeRol'])->name('roles.store');
        Route::put('roles/{rol}', [UsuarioController::class, 'updateRol'])->name('roles.update');
    });
});
