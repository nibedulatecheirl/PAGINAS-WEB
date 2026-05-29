<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $fillable = [
        'razon_social', 'nombre_comercial', 'ruc_nit', 'direccion', 'ciudad',
        'telefono', 'email', 'sitio_web', 'logo', 'moneda', 'codigo_moneda',
        'impuesto', 'impuesto_incluido', 'mensaje_ticket', 'terminos_condiciones',
    ];

    protected $casts = [
        'impuesto' => 'decimal:2',
        'impuesto_incluido' => 'boolean',
    ];

    public function getLogoUrlAttribute()
    {
        if ($this->logo && file_exists(public_path('uploads/empresa/' . $this->logo))) {
            return asset('uploads/empresa/' . $this->logo);
        }
        return null;
    }
}
