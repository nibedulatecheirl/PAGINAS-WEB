<?php

use App\Http\Controllers\Api\CatalogoController;
use App\Http\Controllers\Api\PedidoWebController;
use Illuminate\Support\Facades\Route;

Route::prefix('catalogo')->group(function () {
    Route::get('categorias', [CatalogoController::class, 'categorias']);
    Route::get('productos', [CatalogoController::class, 'productos']);
    Route::get('promociones', [CatalogoController::class, 'promociones']);
    Route::get('productos/{slug}', [CatalogoController::class, 'producto']);
});

Route::middleware(['storefront', 'throttle:storefront-orders'])->group(function () {
    Route::post('pedidos-web', [PedidoWebController::class, 'store']);
});
