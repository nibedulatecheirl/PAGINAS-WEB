<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('nombre');
            $table->string('marca', 120)->nullable()->after('proveedor_id');
            $table->string('linea', 120)->nullable()->after('marca');
            $table->string('tono', 80)->nullable()->after('linea');
            $table->string('presentacion', 120)->nullable()->after('tono');
            $table->string('tipo_piel', 120)->nullable()->after('presentacion');
            $table->string('acabado', 120)->nullable()->after('tipo_piel');
            $table->string('volumen', 80)->nullable()->after('acabado');
            $table->text('ingredientes_clave')->nullable()->after('volumen');
            $table->decimal('precio_oferta', 12, 2)->nullable()->after('precio_venta');
            $table->date('oferta_inicio')->nullable()->after('precio_oferta');
            $table->date('oferta_fin')->nullable()->after('oferta_inicio');
            $table->boolean('visible_web')->default(true)->after('destacado');
            $table->boolean('destacado_web')->default(false)->after('visible_web');
            $table->integer('orden_web')->default(0)->after('destacado_web');
            $table->string('imagen_alt')->nullable()->after('imagen');
            $table->string('meta_title')->nullable()->after('imagen_alt');
            $table->string('meta_description', 320)->nullable()->after('meta_title');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn([
                'slug',
                'marca',
                'linea',
                'tono',
                'presentacion',
                'tipo_piel',
                'acabado',
                'volumen',
                'ingredientes_clave',
                'precio_oferta',
                'oferta_inicio',
                'oferta_fin',
                'visible_web',
                'destacado_web',
                'orden_web',
                'imagen_alt',
                'meta_title',
                'meta_description',
            ]);
        });
    }
};
