<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Configuracion;
use Illuminate\Support\Str;

class ConfiguracionController extends Controller
{
    public function index()
    {
        Configuracion::ensureDefaults();

        $empresa = Empresa::first() ?? new Empresa();
        $configs = Configuracion::pluck('valor', 'clave')->toArray();
        return view('configuracion.index', compact('empresa', 'configs'));
    }

    public function actualizarEmpresa(Request $request)
    {
        $data = $request->validate([
            'razon_social' => 'required|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'ruc_nit' => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'sitio_web' => 'nullable|string|max:255',
            'moneda' => 'required|string|max:10',
            'codigo_moneda' => 'required|string|max:5',
            'impuesto' => 'required|numeric|min:0|max:100',
            'impuesto_incluido' => 'nullable|boolean',
            'mensaje_ticket' => 'nullable|string|max:255',
            'terminos_condiciones' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['impuesto_incluido'] = $request->boolean('impuesto_incluido');

        $empresa = Empresa::first();

        if ($request->hasFile('logo')) {
            if ($empresa && $empresa->logo && file_exists(public_path('uploads/empresa/' . $empresa->logo))) {
                @unlink(public_path('uploads/empresa/' . $empresa->logo));
            }
            $data['logo'] = $this->guardarLogoEmpresa($request->file('logo'));
        }

        if ($empresa) {
            $empresa->update($data);
        } else {
            Empresa::create($data);
        }

        return back()->with('success', 'Datos de empresa actualizados correctamente');
    }

    public function actualizarConfig(Request $request)
    {
        foreach ($request->config ?? [] as $clave => $valor) {
            Configuracion::set($clave, $valor);
        }
        return back()->with('success', 'Configuración actualizada');
    }

    private function guardarLogoEmpresa($logo): string
    {
        $directorio = public_path('uploads/empresa');
        if (! is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $base = pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME);
        $base = Str::slug($base) ?: 'logo';
        $nombreLogo = now()->format('YmdHis') . '_' . $base . '_' . Str::random(8) . '.' . $logo->extension();

        $logo->move($directorio, $nombreLogo);

        return $nombreLogo;
    }
}
