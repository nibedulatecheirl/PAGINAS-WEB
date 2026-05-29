<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\PedidoWeb;
use App\Models\Producto;
use App\Models\Promocion;

class ECommerceController extends Controller
{
    public function home()
    {
        return auth()->check()
            ? redirect()->route('dashboard')
            : $this->storefront();
    }

    public function storefront()
    {
        return response()->file(public_path('store/index.html'));
    }

    public function index()
    {
        $productosWeb = Producto::query()
            ->where('activo', true)
            ->where('visible_web', true);

        $resumen = [
            'productos_web' => (clone $productosWeb)->count(),
            'productos_destacados' => (clone $productosWeb)->where('destacado_web', true)->count(),
            'categorias_activas' => Categoria::where('activo', true)->count(),
            'promociones_vigentes' => Promocion::where('activo', true)
                ->whereDate('fecha_inicio', '<=', now()->toDateString())
                ->whereDate('fecha_fin', '>=', now()->toDateString())
                ->count(),
            'pedidos_pendientes' => PedidoWeb::where('estado_pago', 'pendiente')->count(),
            'pedidos_preparando' => PedidoWeb::where('estado', 'preparando')->count(),
        ];

        $revision = [
            [
                'titulo' => 'Productos visibles sin categoría',
                'valor' => (clone $productosWeb)->whereNull('categoria_id')->count(),
                'detalle' => 'Cada producto web debería pertenecer a una categoría para navegar mejor el catálogo.',
                'ruta' => route('productos.index', ['visible_web' => 'si']),
                'accion' => 'Revisar productos',
            ],
            [
                'titulo' => 'Productos visibles sin imagen',
                'valor' => (clone $productosWeb)->whereNull('imagen')->count(),
                'detalle' => 'La imagen es crítica para conversión en cosmética y skincare.',
                'ruta' => route('productos.index', ['visible_web' => 'si']),
                'accion' => 'Completar imágenes',
            ],
            [
                'titulo' => 'Productos visibles sin descripción',
                'valor' => (clone $productosWeb)
                    ->where(function ($query) {
                        $query->whereNull('descripcion')->orWhere('descripcion', '');
                    })
                    ->count(),
                'detalle' => 'La descripción alimenta la ficha, el detalle y el SEO del producto.',
                'ruta' => route('productos.index', ['visible_web' => 'si']),
                'accion' => 'Completar descripción',
            ],
            [
                'titulo' => 'Productos web sin stock',
                'valor' => (clone $productosWeb)
                    ->where('controla_stock', true)
                    ->where('stock', '<=', 0)
                    ->count(),
                'detalle' => 'Los productos sin stock no aparecen en la tienda pública cuando el catálogo filtra disponibilidad.',
                'ruta' => route('productos.index', ['visible_web' => 'si']),
                'accion' => 'Revisar stock',
            ],
            [
                'titulo' => 'Categorías activas sin productos web',
                'valor' => Categoria::where('activo', true)
                    ->whereDoesntHave('productos', function ($query) {
                        $query->where('activo', true)->where('visible_web', true);
                    })
                    ->count(),
                'detalle' => 'Una categoría vacía no se muestra en la tienda pública.',
                'ruta' => route('categorias.index'),
                'accion' => 'Revisar categorías',
            ],
        ];

        $campos = [
            [
                'titulo' => 'Producto web',
                'items' => ['Código', 'Nombre', 'Categoría', 'Precio de venta', 'Stock', 'Imagen', 'Marca', 'Descripción', 'Texto alt', 'Visibilidad web'],
            ],
            [
                'titulo' => 'Categoría web',
                'items' => ['Nombre', 'Descripción corta', 'Color', 'Ícono', 'Estado activo', 'Productos visibles asociados'],
            ],
            [
                'titulo' => 'Promoción',
                'items' => ['Nombre', 'Tipo de descuento', 'Valor', 'Fecha de inicio', 'Fecha de fin', 'Producto o categoría objetivo', 'Estado activo'],
            ],
            [
                'titulo' => 'Pedido web',
                'items' => ['Cliente', 'Teléfono', 'Productos', 'Cantidad', 'Método de pago', 'Referencia de pago', 'Dirección o modalidad de entrega', 'Estado del pedido'],
            ],
        ];

        return view('ecommerce.index', compact('resumen', 'revision', 'campos'));
    }
}
