<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\ConceptoPagoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\GradoController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\SistemaController;

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])
    ->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])
    ->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('mensajes', MensajeController::class)->except(['edit', 'update']);

    // Boleta visible para perfiles academicos y para el estudiante vinculado.
    Route::get('/notas/boleta/{alumno}', [NotaController::class, 'boleta'])->name('notas.boleta');
    Route::get('/alumnos/create', [AlumnoController::class, 'create'])
        ->middleware('role:admin,secretaria')
        ->name('alumnos.create');

    Route::middleware('role:admin,secretaria,docente')->group(function () {
        Route::get('/grados', [GradoController::class, 'index'])->name('grados.index');
        Route::get('/grados/{grado}/secciones', [GradoController::class, 'secciones'])->name('grados.secciones');
        Route::get('/materias', [GradoController::class, 'materias'])->name('materias.index');
        Route::get('/notas', [NotaController::class, 'index'])->name('notas.index');
        Route::post('/notas', [NotaController::class, 'guardar'])->name('notas.guardar');
        Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');
        Route::get('/alumnos/{alumno}', [AlumnoController::class, 'show'])->name('alumnos.show');

        Route::get('/api/grados/{grado}/secciones', function (\App\Models\Grado $grado) {
            return response()->json($grado->secciones);
        })->name('api.secciones');
    });

    Route::middleware('role:admin,secretaria')->group(function () {
        Route::post('/grados', [GradoController::class, 'store'])->name('grados.store');
        Route::put('/grados/{grado}', [GradoController::class, 'update'])->name('grados.update');
        Route::patch('/grados/{grado}', [GradoController::class, 'update']);
        Route::delete('/grados/{grado}', [GradoController::class, 'destroy'])->name('grados.destroy');
        Route::post('/grados/{grado}/secciones', [GradoController::class, 'storeSeccion'])->name('grados.secciones.store');
        Route::put('/secciones/{seccion}', [GradoController::class, 'updateSeccion'])->name('secciones.update');
        Route::delete('/secciones/{seccion}', [GradoController::class, 'destroySeccion'])->name('secciones.destroy');

        Route::post('/materias', [GradoController::class, 'storeMateria'])->name('materias.store');
        Route::put('/materias/{materia}', [GradoController::class, 'updateMateria'])->name('materias.update');
        Route::delete('/materias/{materia}', [GradoController::class, 'destroyMateria'])->name('materias.destroy');
        Route::post('/asignaciones', [GradoController::class, 'storeAsignacion'])->name('asignaciones.store');
        Route::delete('/asignaciones/{asignacion}', [GradoController::class, 'destroyAsignacion'])->name('asignaciones.destroy');

        Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');
        Route::get('/alumnos/{alumno}/edit', [AlumnoController::class, 'edit'])->name('alumnos.edit');
        Route::put('/alumnos/{alumno}', [AlumnoController::class, 'update'])->name('alumnos.update');
        Route::patch('/alumnos/{alumno}', [AlumnoController::class, 'update']);
        Route::delete('/alumnos/{alumno}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');

        Route::resource('matriculas', MatriculaController::class);
        Route::resource('personal', PersonalController::class);
    });

    Route::middleware('role:admin,secretaria,contador')->group(function () {
        Route::resource('pagos', PagoController::class);

        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/pagos', [ReporteController::class, 'pagos'])->name('reportes.pagos');
        Route::get('/reportes/alumnos', [ReporteController::class, 'alumnos'])->name('reportes.alumnos');
        Route::get('/reportes/deudas', [ReporteController::class, 'deudas'])->name('reportes.deudas');
        Route::get('/reportes/exportar/{tipo}', [ReporteController::class, 'exportarCSV'])->name('reportes.exportar');

        Route::get('/api/conceptos/{concepto}', function (\App\Models\ConceptoPago $concepto) {
            return response()->json($concepto);
        })->name('api.concepto');
    });

    Route::middleware('role:admin,contador')->group(function () {
        Route::resource('conceptos', ConceptoPagoController::class)->except(['show']);
        Route::patch('conceptos/{concepto}/toggle', [ConceptoPagoController::class, 'toggleActivo'])->name('conceptos.toggle');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
        Route::post('/configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');

        Route::get('/sistema', [SistemaController::class, 'index'])->name('sistema.index');
        Route::post('/sistema/backup', [SistemaController::class, 'backup'])->name('sistema.backup');
        Route::post('/sistema/restore', [SistemaController::class, 'restore'])->name('sistema.restore');
        Route::post('/sistema/reset', [SistemaController::class, 'reset'])->name('sistema.reset');
    });
});
