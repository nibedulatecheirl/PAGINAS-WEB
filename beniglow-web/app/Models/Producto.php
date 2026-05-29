<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'codigo', 'codigo_barras', 'nombre', 'slug', 'descripcion', 'categoria_id', 'proveedor_id',
        'marca', 'linea', 'tono', 'presentacion', 'tipo_piel', 'acabado', 'volumen',
        'ingredientes_clave',
        'unidad_medida', 'precio_compra', 'precio_venta', 'precio_mayoreo', 'cantidad_mayoreo',
        'precio_oferta', 'oferta_inicio', 'oferta_fin',
        'stock', 'stock_minimo', 'stock_maximo', 'controla_stock', 'aplica_impuesto',
        'imagen', 'imagen_alt', 'meta_title', 'meta_description',
        'fecha_vencimiento', 'lote', 'ubicacion', 'activo', 'destacado',
        'visible_web', 'destacado_web', 'orden_web',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'precio_mayoreo' => 'decimal:2',
        'precio_oferta' => 'decimal:2',
        'oferta_inicio' => 'date',
        'oferta_fin' => 'date',
        'stock' => 'decimal:3',
        'stock_minimo' => 'decimal:3',
        'stock_maximo' => 'decimal:3',
        'fecha_vencimiento' => 'date',
        'controla_stock' => 'boolean',
        'aplica_impuesto' => 'boolean',
        'activo' => 'boolean',
        'destacado' => 'boolean',
        'visible_web' => 'boolean',
        'destacado_web' => 'boolean',
        'orden_web' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Producto $producto) {
            if (! $producto->slug) {
                $producto->slug = static::uniqueSlug($producto->nombre, $producto->id);
            }
        });
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function ventaDetalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    public function compraDetalles()
    {
        return $this->hasMany(CompraDetalle::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    public function getImagenUrlAttribute()
    {
        if ($this->imagen && file_exists(public_path('uploads/productos/' . $this->imagen))) {
            return asset('uploads/productos/' . $this->imagen);
        }
        return asset('img/producto-default.png');
    }

    public function getPrecioFinalWebAttribute()
    {
        return $this->en_oferta_web ? $this->precio_oferta : $this->precio_venta;
    }

    public function getEnOfertaWebAttribute(): bool
    {
        if ($this->precio_oferta === null || $this->precio_oferta <= 0) {
            return false;
        }

        $hoy = now()->toDateString();

        if ($this->oferta_inicio && $this->oferta_inicio->toDateString() > $hoy) {
            return false;
        }

        if ($this->oferta_fin && $this->oferta_fin->toDateString() < $hoy) {
            return false;
        }

        return $this->precio_oferta < $this->precio_venta;
    }

    public function getDisponibleWebAttribute(): bool
    {
        return $this->activo && $this->visible_web && (! $this->controla_stock || $this->stock > 0);
    }

    public function getStockBajoAttribute()
    {
        return $this->controla_stock && $this->stock <= $this->stock_minimo;
    }

    public function getMargenAttribute()
    {
        if ($this->precio_compra == 0) return 0;
        return round((($this->precio_venta - $this->precio_compra) / $this->precio_compra) * 100, 2);
    }

    private static function uniqueSlug(string $nombre, ?int $ignoreId = null): string
    {
        $base = Str::slug($nombre) ?: Str::lower(Str::random(8));
        $slug = $base;
        $i = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '<>', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
