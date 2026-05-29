<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoWeb extends Model
{
    use HasFactory;

    protected $table = 'pedidos_web';

    protected $fillable = [
        'codigo', 'cliente_id', 'venta_id', 'canal', 'origen',
        'estado', 'estado_pago', 'estado_stock',
        'subtotal', 'descuento', 'impuesto', 'envio', 'total', 'moneda',
        'metodo_pago', 'referencia_pago', 'payment_payload',
        'cliente_nombre', 'cliente_email', 'cliente_telefono', 'cliente_documento',
        'direccion_envio', 'notas', 'confirmed_at', 'cancelled_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'impuesto' => 'decimal:2',
        'envio' => 'decimal:2',
        'total' => 'decimal:2',
        'payment_payload' => 'array',
        'direccion_envio' => 'array',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function detalles()
    {
        return $this->hasMany(PedidoWebDetalle::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
