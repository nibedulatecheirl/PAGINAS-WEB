<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuraciones';

    protected $fillable = ['clave', 'valor', 'tipo', 'grupo', 'descripcion'];

    public static function defaults(): array
    {
        return [
            'puntos_por_moneda' => ['valor' => '0.1', 'tipo' => 'string', 'grupo' => 'fidelidad', 'descripcion' => 'Puntos por unidad de moneda'],
            'dias_aviso_vencimiento' => ['valor' => '30', 'tipo' => 'integer', 'grupo' => 'inventario', 'descripcion' => 'Dias de anticipacion para alertas de vencimiento'],
            'stock_minimo_default' => ['valor' => '5', 'tipo' => 'integer', 'grupo' => 'inventario', 'descripcion' => 'Stock minimo por defecto para nuevos productos'],
            'serie_ticket' => ['valor' => 'T001', 'tipo' => 'string', 'grupo' => 'facturacion', 'descripcion' => 'Serie para tickets'],
            'serie_boleta' => ['valor' => 'B001', 'tipo' => 'string', 'grupo' => 'facturacion', 'descripcion' => 'Serie para boletas'],
            'serie_factura' => ['valor' => 'F001', 'tipo' => 'string', 'grupo' => 'facturacion', 'descripcion' => 'Serie para facturas'],
            'ancho_ticket' => ['valor' => '80', 'tipo' => 'integer', 'grupo' => 'ticket', 'descripcion' => 'Ancho de ticket en milimetros'],
            'imprimir_auto' => ['valor' => '0', 'tipo' => 'boolean', 'grupo' => 'ticket', 'descripcion' => 'Imprimir ticket automaticamente al cobrar'],
            'mostrar_logo_ticket' => ['valor' => '1', 'tipo' => 'boolean', 'grupo' => 'ticket', 'descripcion' => 'Mostrar logo en ticket'],
            'whatsapp_contacto' => ['valor' => '993 902 669', 'tipo' => 'string', 'grupo' => 'ecommerce', 'descripcion' => 'WhatsApp principal de atencion web'],
            'email_contacto' => ['valor' => 'binitostore15@gmail.com', 'tipo' => 'string', 'grupo' => 'ecommerce', 'descripcion' => 'Correo principal de atencion web'],
            'ciudad_operacion' => ['valor' => 'Tacna, Perú', 'tipo' => 'string', 'grupo' => 'ecommerce', 'descripcion' => 'Ciudad de operacion del negocio'],
            'pedidos_web_descuentan_stock' => ['valor' => '0', 'tipo' => 'boolean', 'grupo' => 'ecommerce', 'descripcion' => 'Los pedidos web descuentan stock solo al confirmar pago'],
            'pedidos_web_metodo_default' => ['valor' => 'whatsapp', 'tipo' => 'string', 'grupo' => 'ecommerce', 'descripcion' => 'Metodo de pago/contacto por defecto para pedidos web'],
        ];
    }

    public static function ensureDefaults(): void
    {
        foreach (self::defaults() as $clave => $config) {
            self::firstOrCreate(
                ['clave' => $clave],
                [
                    'valor' => $config['valor'],
                    'tipo' => $config['tipo'],
                    'grupo' => $config['grupo'],
                    'descripcion' => $config['descripcion'],
                ]
            );
        }
    }

    public static function get($clave, $default = null)
    {
        $config = self::where('clave', $clave)->first();
        if (!$config) return $default;

        return match ($config->tipo) {
            'integer' => (int) $config->valor,
            'boolean' => filter_var($config->valor, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($config->valor, true),
            default => $config->valor,
        };
    }

    public static function set($clave, $valor, $tipo = 'string', $grupo = 'general')
    {
        $default = self::defaults()[$clave] ?? null;
        $tipo = $default['tipo'] ?? $tipo;
        $grupo = $default['grupo'] ?? $grupo;

        if ($tipo === 'json') $valor = json_encode($valor);
        if ($tipo === 'boolean') $valor = $valor ? '1' : '0';

        return self::updateOrCreate(
            ['clave' => $clave],
            [
                'valor' => $valor,
                'tipo' => $tipo,
                'grupo' => $grupo,
                'descripcion' => $default['descripcion'] ?? null,
            ]
        );
    }
}
