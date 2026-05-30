<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\Seccion;
use App\Models\Materia;
use App\Models\Matricula;
use App\Models\Configuracion;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $anio = $request->get('anio', Configuracion::anioEscolar());

        if ($user->role === 'docente' && $user->personal_id) {
            $asignacionesDocente = Asignacion::where('personal_id', $user->personal_id)
                ->where('anio_escolar', $anio)
                ->get();
            $secciones = Seccion::with('grado')->whereIn('id', $asignacionesDocente->pluck('seccion_id'))->get();
            $materias  = Materia::activas()->whereIn('id', $asignacionesDocente->pluck('materia_id'))->get();
        } else {
            $secciones = Seccion::with('grado')->get();
            $materias  = Materia::activas()->get();
        }

        $seccionId = $request->get('seccion_id');
        $materiaId = $request->get('materia_id');
        $numBimestres = Configuracion::numBimestres();
        $notaMinima = Configuracion::notaMinima();
        $notaMaxima = Configuracion::notaMaxima();

        $libroNotas = collect();
        $seccion    = null;
        $materia    = null;

        if ($seccionId && $materiaId) {
            $seccion = Seccion::with('grado')->find($seccionId);
            $materia = Materia::find($materiaId);

            // Obtener alumnos matriculados en esta sección/año
            $matriculas = Matricula::where('seccion_id', $seccionId)
                ->where('anio_escolar', $anio)
                ->where('estado', 'activo')
                ->with('alumno')
                ->get();

            foreach ($matriculas as $matricula) {
                $notasBimestres = [];
                for ($b = 1; $b <= $numBimestres; $b++) {
                    $notasBimestres[$b] = Nota::where([
                        'alumno_id'   => $matricula->alumno_id,
                        'materia_id'  => $materiaId,
                        'seccion_id'  => $seccionId,
                        'anio_escolar'=> $anio,
                        'bimestre'    => $b,
                    ])->first();
                }

                $notasValidas = collect($notasBimestres)
                    ->filter()
                    ->pluck('nota')
                    ->filter(fn ($nota) => $nota !== null);
                $promedio     = $notasValidas->count() > 0 ? round($notasValidas->avg(), 2) : null;

                $libroNotas->push([
                    'alumno'   => $matricula->alumno,
                    'bimestres'=> $notasBimestres,
                    'promedio' => $promedio,
                    'estado'   => $promedio !== null ? ($promedio >= $notaMinima ? 'aprobado' : 'desaprobado') : 'pendiente',
                ]);
            }
        }

        return view('notas.index', compact(
            'secciones',
            'materias',
            'seccion',
            'materia',
            'anio',
            'libroNotas',
            'seccionId',
            'materiaId',
            'numBimestres',
            'notaMinima',
            'notaMaxima'
        ));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'notas'       => 'required|array',
            'seccion_id'  => 'required|exists:secciones,id',
            'materia_id'  => 'required|exists:materias,id',
            'anio_escolar'=> 'required|integer',
            'bimestre'    => 'required|integer|min:1|max:' . Configuracion::numBimestres(),
            'notas.*'     => 'nullable|numeric|min:0|max:' . Configuracion::notaMaxima(),
        ]);

        $user = auth()->user();
        if ($user->role === 'docente') {
            $asignado = Asignacion::where('personal_id', $user->personal_id)
                ->where('seccion_id', $request->seccion_id)
                ->where('materia_id', $request->materia_id)
                ->where('anio_escolar', $request->anio_escolar)
                ->exists();

            if (!$asignado) {
                abort(403, 'Solo puedes registrar notas de tus asignaciones.');
            }
        }

        $notaMin = Configuracion::notaMinima();

        foreach ($request->notas as $alumnoId => $valor) {
            $keys = [
                'alumno_id'   => $alumnoId,
                'materia_id'  => $request->materia_id,
                'seccion_id'  => $request->seccion_id,
                'anio_escolar'=> $request->anio_escolar,
                'bimestre'    => $request->bimestre,
            ];

            if ($valor === null || $valor === '') {
                Nota::where($keys)->delete();
                continue;
            }

            $valorNum = (float) $valor;
            Nota::updateOrCreate(
                $keys,
                [
                    'nota'            => $valorNum,
                    'estado'          => $valorNum >= $notaMin ? 'aprobado' : 'desaprobado',
                    'registrado_por'  => auth()->id(),
                ]
            );
        }

        return back()->with('success', "Notas del Bimestre {$request->bimestre} guardadas correctamente.");
    }

    public function boleta(int $alumnoId, Request $request)
    {
        $user = auth()->user();
        if ($user->role === 'estudiante' && (int) $user->alumno_id !== (int) $alumnoId) {
            abort(403, 'Solo puedes ver tu propia boleta.');
        }
        if (!$user->hasAnyRole(['admin', 'secretaria', 'docente', 'estudiante'])) {
            abort(403, 'No tienes permiso para ver boletas.');
        }

        $anio     = $request->get('anio', Configuracion::anioEscolar());
        $matricula= Matricula::where('alumno_id', $alumnoId)
            ->where('anio_escolar', $anio)
            ->where('estado', 'activo')
            ->with(['alumno','grado','seccion'])
            ->firstOrFail();

        $materias = Materia::activas()->get();
        $notaMin  = Configuracion::notaMinima();
        $numBimestres = Configuracion::numBimestres();

        $libroMaterias = $materias->map(function ($materia) use ($alumnoId, $matricula, $anio, $notaMin, $numBimestres) {
            $bimestres = [];
            for ($b = 1; $b <= $numBimestres; $b++) {
                $nota = Nota::where([
                    'alumno_id'  => $alumnoId,
                    'materia_id' => $materia->id,
                    'seccion_id' => $matricula->seccion_id,
                    'anio_escolar'=> $anio,
                    'bimestre'   => $b,
                ])->first();
                $bimestres[$b] = $nota?->nota;
            }
            $validas  = collect($bimestres)->filter(fn ($nota) => $nota !== null);
            $promedio = $validas->count() > 0 ? round($validas->avg(), 2) : null;

            return [
                'materia'  => $materia,
                'bimestres'=> $bimestres,
                'promedio' => $promedio,
                'estado'   => $promedio !== null ? ($promedio >= $notaMin ? 'aprobado' : 'desaprobado') : 'pendiente',
            ];
        });

        $config = [
            'nombre_colegio' => Configuracion::nombreColegio(),
            'nota_minima'    => $notaMin,
            'num_bimestres'  => $numBimestres,
        ];

        return view('notas.boleta', compact('matricula', 'libroMaterias', 'anio', 'config'));
    }
}
