<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos_web', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 40)->unique();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('venta_id')->nullable()->constrained('ventas')->nullOnDelete();
            $table->string('canal', 30)->default('web');
            $table->string('origen')->nullable();
            $table->enum('estado', ['pendiente_pago', 'pagado', 'preparando', 'enviado', 'entregado', 'cancelado', 'fallido'])->default('pendiente_pago');
            $table->enum('estado_pago', ['pendiente', 'pagado', 'rechazado', 'reembolsado'])->default('pendiente');
            $table->enum('estado_stock', ['sin_descontar', 'descontado', 'restaurado'])->default('sin_descontar');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('impuesto', 12, 2)->default(0);
            $table->decimal('envio', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->string('moneda', 10)->default('PEN');
            $table->string('metodo_pago', 50)->nullable();
            $table->string('referencia_pago')->nullable();
            $table->json('payment_payload')->nullable();
            $table->string('cliente_nombre');
            $table->string('cliente_email')->nullable();
            $table->string('cliente_telefono', 30)->nullable();
            $table->string('cliente_documento', 30)->nullable();
            $table->json('direccion_envio')->nullable();
            $table->text('notas')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['estado', 'estado_pago']);
            $table->index('cliente_email');
            $table->index('created_at');
        });

        Schema::create('pedido_web_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_web_id')->constrained('pedidos_web')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos');
            $table->string('codigo', 50);
            $table->string('nombre');
            $table->decimal('cantidad', 12, 3);
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('impuesto', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('total', 12, 2);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_web_detalles');
        Schema::dropIfExists('pedidos_web');
    }
};
