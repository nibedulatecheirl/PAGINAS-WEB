@extends('layouts.app')
@section('title', 'Panel Docente')
@section('page-title', 'Panel Docente')

@section('content')

<div class="welcome-banner">
    <div style="position:relative;z-index:2;">
        <h1 style="font-size:26px;font-weight:800;margin-bottom:8px;">Panel docente</h1>
        <p style="opacity:.92;font-size:15px;">Aulas, cursos y accesos rápidos para registrar calificaciones.</p>
    </div>
</div>

<div class="grid grid-3" style="margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-book-open"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $asignaciones->pluck('materia_id')->unique()->count() }}</div>
            <div class="stat-label">Materias asignadas</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-door-open"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $asignaciones->pluck('seccion_id')->unique()->count() }}</div>
            <div class="stat-label">Secciones asignadas</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-envelope"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ $mensajesNoLeidos }}</div>
            <div class="stat-label">Mensajes sin leer</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">Mis asignaciones {{ $anioActual }}</span>
        <a href="{{ route('notas.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-clipboard-list"></i> Libro de notas
        </a>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>Materia</th><th>Grado</th><th>Sección</th><th>Acción</th></tr>
            </thead>
            <tbody>
            @forelse($asignaciones as $asignacion)
                <tr>
                    <td style="font-weight:700;">{{ $asignacion->materia->nombre ?? '—' }}</td>
                    <td>{{ $asignacion->seccion->grado->nombre ?? '—' }}</td>
                    <td>Sección {{ $asignacion->seccion->nombre ?? '—' }}</td>
                    <td>
                        <a class="btn btn-sm btn-secondary" href="{{ route('notas.index') }}?seccion_id={{ $asignacion->seccion_id }}&materia_id={{ $asignacion->materia_id }}&anio={{ $anioActual }}">
                            <i class="fas fa-pen"></i> Calificar
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;padding:32px;color:var(--muted);">Este docente todavía no tiene asignaciones.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
