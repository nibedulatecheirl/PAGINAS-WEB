<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Promocion;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function categorias()
    {
        $categorias = Categoria::where('activo', true)
            ->whereHas('productos', function ($query) {
                $query->where('activo', true)->where('visible_web', true);
            })
            ->orderBy('nombre')
            ->get()
            ->map(fn ($categoria) => [
                'id' => $categoria->id,
                'nombre' => $categoria->nombre,
                'descripcion' => $categoria->descripcion,
                'color' => $categoria->color,
                'icono' => $categoria->icono,
            ]);

        return response()->json(['data' => $categorias]);
    }

    public function productos(Request $request)
    {
        $query = Producto::with('categoria')
            ->where('activo', true)
            ->where('visible_web', true);

        if ($request->filled('q')) {
            $buscar = $request->q;
            $query->where(function ($subquery) use ($buscar) {
                $subquery->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('descripcion', 'like', "%{$buscar}%")
                    ->orWhere('marca', 'like', "%{$buscar}%")
                    ->orWhere('linea', 'like', "%{$buscar}%")
                    ->orWhere('tono', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('categoria')) {
            $query->whereHas('categoria', fn ($categoria) => $categoria->where('nombre', $request->categoria));
        }

        if ($request->filled('marca')) {
            $query->where('marca', $request->marca);
        }

        if ($request->boolean('destacado')) {
            $query->where('destacado_web', true);
        }

        if ($request->boolean('con_stock')) {
            $query->where(function ($stock) {
                $stock->where('controla_stock', false)->orWhere('stock', '>', 0);
            });
        }

        $perPage = min(max((int) $request->integer('per_page', 24), 1), 100);

        $productos = $query
            ->orderByDesc('destacado_web')
            ->orderBy('orden_web')
            ->orderBy('nombre')
            ->paginate($perPage)
            ->appends($request->query())
            ->through(fn (Producto $producto) => $this->productoResource($producto));

        return response()->json($productos);
    }

    public function producto(string $slug)
    {
        $producto = Producto::with('categoria')
            ->where('activo', true)
            ->where('visible_web', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json(['data' => $this->productoResource($producto, true)]);
    }

    public function promociones()
    {
        $promociones = Promocion::with(['producto', 'categoria'])
            ->where('activo', true)
            ->whereDate('fecha_inicio', '<=', now()->toDateString())
            ->whereDate('fecha_fin', '>=', now()->toDateString())
            ->where(function ($query) {
                $query->whereNull('producto_id')
                    ->orWhereHas('producto', function ($producto) {
                        $producto->where('activo', true)->where('visible_web', true);
                    });
            })
            ->where(function ($query) {
                $query->whereNull('categoria_id')
                    ->orWhereHas('categoria', fn ($categoria) => $categoria->where('activo', true));
            })
            ->orderBy('fecha_fin')
            ->orderBy('nombre')
            ->get()
            ->map(fn (Promocion $promocion) => [
                'id' => $promocion->id,
                'nombre' => $promocion->nombre,
                'descripcion' => $promocion->descripcion,
                'tipo' => $promocion->tipo,
                'valor' => (float) $promocion->valor,
                'cantidad_minima' => $promocion->cantidad_minima,
                'fecha_inicio' => $promocion->fecha_inicio?->toDateString(),
                'fecha_fin' => $promocion->fecha_fin?->toDateString(),
                'producto' => $promocion->producto ? [
                    'id' => $promocion->producto->id,
                    'slug' => $promocion->producto->slug,
                    'nombre' => $promocion->producto->nombre,
                ] : null,
                'categoria' => $promocion->categoria ? [
                    'id' => $promocion->categoria->id,
                    'nombre' => $promocion->categoria->nombre,
                ] : null,
            ]);

        return response()->json(['data' => $promociones]);
    }

    private function productoResource(Producto $producto, bool $detalle = false): array
    {
        $data = [
            'id' => $producto->id,
            'codigo' => $producto->codigo,
            'slug' => $producto->slug,
            'nombre' => $producto->nombre,
            'categoria' => $producto->categoria ? [
                'id' => $producto->categoria->id,
                'nombre' => $producto->categoria->nombre,
            ] : null,
            'marca' => $producto->marca,
            'linea' => $producto->linea,
            'tono' => $producto->tono,
            'presentacion' => $producto->presentacion,
            'tipo_piel' => $producto->tipo_piel,
            'acabado' => $producto->acabado,
            'volumen' => $producto->volumen,
            'precio' => (float) $producto->precio_venta,
            'precio_oferta' => $producto->precio_oferta !== null ? (float) $producto->precio_oferta : null,
            'precio_final' => (float) $producto->precio_final_web,
            'en_oferta' => $producto->en_oferta_web,
            'stock' => (float) $producto->stock,
            'controla_stock' => $producto->controla_stock,
            'disponible' => $producto->disponible_web,
            'imagen_url' => $producto->imagen_url,
            'imagen_alt' => $producto->imagen_alt ?: $producto->nombre,
            'destacado' => $producto->destacado_web,
            'updated_at' => $producto->updated_at?->toISOString(),
        ];

        if ($detalle) {
            $data += [
                'descripcion' => $producto->descripcion,
                'ingredientes_clave' => $producto->ingredientes_clave,
                'meta_title' => $producto->meta_title ?: $producto->nombre,
                'meta_description' => $producto->meta_description ?: $producto->descripcion,
            ];
        }

        return $data;
    }
}
