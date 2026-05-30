<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $configs = Configuracion::allAgrupadas();
        return view('configuracion.index', compact('configs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'colegio_nombre' => 'required|string|max:150',
            'colegio_ruc' => 'nullable|string|max:20',
            'colegio_direccion' => 'nullable|string|max:200',
            'colegio_telefono' => 'nullable|string|max:30',
            'colegio_email' => 'nullable|email|max:150',
            'colegio_director' => 'nullable|string|max:150',
            'anio_escolar' => 'required|integer|min:2020|max:2035',
            'moneda' => 'required|string|max:5',
            'nota_minima' => 'required|numeric|min:0|max:20',
            'nota_maxima' => 'required|numeric|min:1|max:20',
            'num_bimestres' => 'required|integer|min:2|max:4',
        ]);

        if ((float) $request->nota_minima > (float) $request->nota_maxima) {
            return back()->withInput()->with('error', 'La nota minima no puede ser mayor que la nota maxima.');
        }

        $data = $request->except(['_token', '_method', 'logo']);

        // Guardar cada valor de texto
        foreach ($data as $clave => $valor) {
            Configuracion::set($clave, $valor);
        }

        // Manejar subida de logo
        if ($request->hasFile('logo')) {
            $request->validate([
                'logo' => 'image|mimes:jpeg,png,jpg,svg|max:2048'
            ]);

            $path = $request->file('logo')->store('public/logos');
            $url = Storage::url($path);
            
            Configuracion::set('logo_url', $url);
        }

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}
