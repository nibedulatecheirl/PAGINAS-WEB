<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('canal', 30)->default('pos')->after('serie');
            $table->string('referencia_externa')->nullable()->after('canal');
            $table->string('estado_pago', 30)->default('pagado')->after('detalle_pago');
            $table->string('estado_envio', 30)->nullable()->after('estado_pago');
            $table->json('direccion_envio')->nullable()->after('estado_envio');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn([
                'canal',
                'referencia_externa',
                'estado_pago',
                'estado_envio',
                'direccion_envio',
            ]);
        });
    }
};
