<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Promocion;
use App\Models\Proveedor;
use App\Support\ImageOptimizer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BeniglowCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = $this->seedCategorias();
        $proveedor = $this->seedProveedor();

        foreach ($this->productos() as $index => $producto) {
            $slug = Str::slug($producto['nombre']);
            $imagen = $this->copiarImagen($producto['directorio_imagen'], $producto['archivo_imagen'], $slug);

            Producto::updateOrCreate([
                'codigo' => $producto['codigo'],
            ], [
                'codigo_barras' => null,
                'nombre' => $producto['nombre'],
                'slug' => $slug,
                'descripcion' => $producto['descripcion'],
                'categoria_id' => $categorias[$producto['categoria']]->id,
                'proveedor_id' => $proveedor->id,
                'marca' => $producto['marca'],
                'linea' => $producto['linea'],
                'tono' => null,
                'presentacion' => $producto['presentacion'],
                'tipo_piel' => $producto['tipo_piel'],
                'acabado' => $producto['acabado'],
                'volumen' => $producto['volumen'],
                'ingredientes_clave' => $producto['ingredientes_clave'],
                'unidad_medida' => 'UND',
                'precio_compra' => $producto['precio_compra'],
                'precio_venta' => $producto['precio_venta'],
                'precio_mayoreo' => 0,
                'cantidad_mayoreo' => 0,
                'precio_oferta' => $producto['precio_oferta'],
                'oferta_inicio' => now()->subDay()->toDateString(),
                'oferta_fin' => now()->addMonths(3)->toDateString(),
                'stock' => $producto['stock'],
                'stock_minimo' => $producto['stock_minimo'],
                'stock_maximo' => 100,
                'controla_stock' => true,
                'aplica_impuesto' => true,
                'imagen' => $imagen,
                'imagen_alt' => $producto['nombre'] . ' en BeniGlow Store',
                'meta_title' => $producto['nombre'] . ' | BeniGlow Store',
                'meta_description' => Str::limit($producto['descripcion'], 300, ''),
                'fecha_vencimiento' => null,
                'lote' => 'IMP-2026-01',
                'ubicacion' => 'Almacen principal',
                'activo' => true,
                'destacado' => $producto['destacado'],
                'visible_web' => true,
                'destacado_web' => $producto['destacado'],
                'orden_web' => $index + 1,
            ]);
        }

        Promocion::updateOrCreate([
            'nombre' => 'Lanzamiento K-Beauty BeniGlow',
        ], [
            'descripcion' => 'Descuento de bienvenida para el catalogo inicial de cuidado facial coreano.',
            'tipo' => 'descuento_porcentaje',
            'valor' => 10,
            'producto_id' => null,
            'categoria_id' => null,
            'fecha_inicio' => now()->subDay()->toDateString(),
            'fecha_fin' => now()->addMonths(3)->toDateString(),
            'cantidad_minima' => 1,
            'activo' => true,
        ]);
    }

    private function seedCategorias(): array
    {
        $data = [
            'Limpieza facial' => [
                'descripcion' => 'Aceites, espumas y productos para retirar maquillaje, protector solar e impurezas.',
                'color' => '#B98263',
                'icono' => 'droplet',
            ],
            'Protección solar' => [
                'descripcion' => 'Protectores solares ligeros para rutina diaria y acabado natural.',
                'color' => '#D8A155',
                'icono' => 'sun',
            ],
            'Tratamientos faciales' => [
                'descripcion' => 'Serums y boosters enfocados en textura, luminosidad y firmeza.',
                'color' => '#8A5A44',
                'icono' => 'sparkles',
            ],
            'Contorno de ojos' => [
                'descripcion' => 'Cremas y tratamientos para mirada cansada, ojeras y tono desigual.',
                'color' => '#C87F67',
                'icono' => 'eye',
            ],
            'Sets y rutinas' => [
                'descripcion' => 'Combinaciones listas para armar una rutina completa de cuidado facial.',
                'color' => '#7A6A5A',
                'icono' => 'package',
            ],
        ];

        $categorias = [];

        foreach ($data as $nombre => $categoria) {
            $categorias[$nombre] = Categoria::updateOrCreate([
                'nombre' => $nombre,
            ], [
                'nombre' => $nombre,
                'descripcion' => $categoria['descripcion'],
                'color' => $categoria['color'],
                'icono' => $categoria['icono'],
                'activo' => true,
            ]);
        }

        return $categorias;
    }

    private function seedProveedor(): Proveedor
    {
        return Proveedor::updateOrCreate([
            'codigo' => 'YESSTYLE',
        ], [
            'razon_social' => 'YesStyle.com',
            'nombre_comercial' => 'YesStyle',
            'ruc_nit' => null,
            'contacto' => 'Canal de compras online YesStyle Beauty',
            'telefono' => null,
            'email' => null,
            'direccion' => 'https://www.yesstyle.com/es/beauty-skin-care/list.html',
            'ciudad' => 'Hong Kong',
            'observaciones' => 'Proveedor internacional de productos de belleza, skincare y K-beauty. Enlace base del catalogo: https://www.yesstyle.com/es/beauty-skin-care/list.html. Contacto formal pendiente de confirmar en el formulario oficial de YesStyle.',
            'activo' => true,
        ]);
    }

    private function copiarImagen(string $directorioImagen, string $archivoImagen, string $slug): ?string
    {
        $origen = database_path('seeders/assets/beniglow-productos/' . $directorioImagen . '/' . $archivoImagen);

        if (! File::exists($origen)) {
            return null;
        }

        $destino = public_path('uploads/productos');
        File::ensureDirectoryExists($destino);

        $nombreArchivo = $slug . '.webp';

        if (ImageOptimizer::toWebp($origen, $destino . DIRECTORY_SEPARATOR . $nombreArchivo)) {
            return $nombreArchivo;
        }

        $extension = pathinfo($archivoImagen, PATHINFO_EXTENSION) ?: 'png';
        $nombreArchivo = $slug . '.' . $extension;
        File::copy($origen, $destino . DIRECTORY_SEPARATOR . $nombreArchivo);

        return $nombreArchivo;
    }

    private function productos(): array
    {
        return [
            [
                'codigo' => 'BG-CEL-RET-001',
                'nombre' => 'Celimax The Vita-A Retinal Shot Tightening Booster',
                'categoria' => 'Tratamientos faciales',
                'marca' => 'Celimax',
                'linea' => 'The Vita-A',
                'presentacion' => 'Booster facial',
                'tipo_piel' => 'Piel con textura, poros visibles o primeros signos de edad',
                'acabado' => 'Ligero',
                'volumen' => '15 ml',
                'ingredientes_clave' => 'Retinal, complejo Vita-A y activos de soporte para firmeza y textura.',
                'descripcion' => 'Booster facial de noche enfocado en mejorar textura, apariencia de poros y firmeza. Ideal para rutinas que buscan un acabado de piel mas liso y luminoso.',
                'precio_compra' => 62.00,
                'precio_venta' => 99.90,
                'precio_oferta' => 92.90,
                'stock' => 12,
                'stock_minimo' => 3,
                'destacado' => true,
                'directorio_imagen' => 'Celimax The Vita-A Retinal Shot Tightening Booster',
                'archivo_imagen' => '549329a3-8b13-4396-9c0b-0f0e96bcfe20.png',
            ],
            [
                'codigo' => 'BG-MAM-EYE-001',
                'nombre' => 'MARY & MAY Tranexamic Acid + Glutathione Eye Cream',
                'categoria' => 'Contorno de ojos',
                'marca' => 'MARY & MAY',
                'linea' => 'Brightening Eye Care',
                'presentacion' => 'Crema de contorno de ojos',
                'tipo_piel' => 'Todo tipo de piel; mirada cansada o tono desigual',
                'acabado' => 'Cremoso ligero',
                'volumen' => '30 g',
                'ingredientes_clave' => 'Acido tranexamico, glutation y niacinamida.',
                'descripcion' => 'Crema para el contorno de ojos orientada a luminosidad, tono desigual y apariencia de cansancio. Textura suave para integrar en rutina de dia o noche.',
                'precio_compra' => 28.00,
                'precio_venta' => 49.90,
                'precio_oferta' => 42.90,
                'stock' => 20,
                'stock_minimo' => 4,
                'destacado' => false,
                'directorio_imagen' => 'MARY & MAY Tranexamic Acid + Glutathione Eye Cream',
                'archivo_imagen' => '73b0479f-b6a8-4fe5-95a8-4dd605bd5544.png',
            ],
            [
                'codigo' => 'BG-MIX-SUN-001',
                'nombre' => 'Mixsoon Bean Sun Serum SPF 50+ PA++++',
                'categoria' => 'Protección solar',
                'marca' => 'Mixsoon',
                'linea' => 'Bean',
                'presentacion' => 'Serum protector solar',
                'tipo_piel' => 'Todo tipo de piel; rutina diaria',
                'acabado' => 'Ligero y natural',
                'volumen' => '50 ml',
                'ingredientes_clave' => 'Extracto de frijol fermentado y filtros solares de amplio espectro.',
                'descripcion' => 'Protector solar en formato serum con SPF 50+ PA++++. Pensado para uso diario, textura ligera y sensacion hidratante sin acabado pesado.',
                'precio_compra' => 34.00,
                'precio_venta' => 59.90,
                'precio_oferta' => 52.90,
                'stock' => 15,
                'stock_minimo' => 4,
                'destacado' => true,
                'directorio_imagen' => 'Mixsoon Bean Sun Serum SPF 50+ PA++++',
                'archivo_imagen' => '29a476e9-9948-4ee4-bd1c-2a09595107ef.png',
            ],
            [
                'codigo' => 'BG-SK4-DUO-001',
                'nombre' => 'SKIN1004 Madagascar Centella Double Cleansing Duo Set',
                'categoria' => 'Sets y rutinas',
                'marca' => 'SKIN1004',
                'linea' => 'Madagascar Centella',
                'presentacion' => 'Set de doble limpieza',
                'tipo_piel' => 'Todo tipo de piel; piel sensible',
                'acabado' => 'Rutina de limpieza suave',
                'volumen' => 'Aceite 200 ml + espuma 125 ml',
                'ingredientes_clave' => 'Centella asiatica de Madagascar y agentes limpiadores suaves.',
                'descripcion' => 'Set para doble limpieza facial con aceite limpiador y espuma. Ayuda a retirar maquillaje, protector solar e impurezas sin dejar sensacion tirante.',
                'precio_compra' => 65.00,
                'precio_venta' => 109.90,
                'precio_oferta' => 99.90,
                'stock' => 8,
                'stock_minimo' => 2,
                'destacado' => true,
                'directorio_imagen' => 'SKIN1004 Madagascar Centella Double Cleansing Duo Set',
                'archivo_imagen' => '7ac9bd02-f425-4bd4-990d-ac7fe32df596.png',
            ],
            [
                'codigo' => 'BG-SK4-OIL-001',
                'nombre' => 'SKIN1004 Madagascar Centella Light Cleansing Oil',
                'categoria' => 'Limpieza facial',
                'marca' => 'SKIN1004',
                'linea' => 'Madagascar Centella',
                'presentacion' => 'Aceite limpiador',
                'tipo_piel' => 'Todo tipo de piel; piel sensible',
                'acabado' => 'Ligero',
                'volumen' => '200 ml',
                'ingredientes_clave' => 'Centella asiatica de Madagascar y aceites limpiadores ligeros.',
                'descripcion' => 'Aceite limpiador ligero para retirar protector solar, maquillaje e impurezas. Formula pensada para una primera limpieza comoda y suave.',
                'precio_compra' => 40.00,
                'precio_venta' => 69.90,
                'precio_oferta' => 62.90,
                'stock' => 18,
                'stock_minimo' => 4,
                'destacado' => false,
                'directorio_imagen' => 'SKIN1004 Madagascar Centella Light Cleansing Oil',
                'archivo_imagen' => '5181199a-e61d-45ce-8b12-0dba31aa201b.png',
            ],
        ];
    }
}
