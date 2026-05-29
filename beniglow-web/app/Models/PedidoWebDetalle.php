<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoWebDetalle extends Model
{
    use HasFactory;

    protected $table = 'pedido_web_detalles';

    protected $fillable = [
        'pedido_web_id', 'producto_id', 'codigo', 'nombre',
        'cantidad', 'precio_unitario', 'descuento', 'impuesto',
        'subtotal', 'total', 'meta',
    ];

    protected $casts = [
        'cantidad' => 'decimal:3',
        'precio_unitario' => 'decimal:2',
        'descuento' => 'decimal:2',
        'impuesto' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'meta' => 'array',
    ];

    public function pedidoWeb()
    {
        return $this->belongsTo(PedidoWeb::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
