@extends('layouts.app')
@section('title', 'Mi Aula')
@section('page-title', 'Mi Aula')

@section('content')

<div class="welcome-banner">
    <div style="position:relative;z-index:2;">
        <h1 style="font-size:26px;font-weight:800;margin-bottom:8px;">Hola, {{ auth()->user()->name }}</h1>
        <p style="opacity:.92;font-size:15px;">Este es tu resumen académico del año escolar {{ $anioActual }}.</p>
    </div>
</div>

@if(!$alumno)
    <div class="alert alert-warning">
        <i class="fas fa-link"></i>
        Este usuario de estudiante todavía no está vinculado a un alumno.
    </div>
@else
    <div class="grid grid-4" style="margin-bottom:24px;">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-info">
                <div class="stat-value" style="font-size:20px;">{{ $alumno->codigo }}</div>
                <div class="stat-label">Código de estudiante</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-school"></i></div>
            <div class="stat-info">
                <div class="stat-value" style="font-size:20px;">{{ $matricula?->grado?->nombre ?? 'Sin matrícula' }}</div>
                <div class="stat-label">Grado actual</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-chart-line"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $promedio ? number_format($promedio, 1) : '—' }}</div>
                <div class="stat-label">Promedio registrado</div>
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

    <div class="grid grid-2" style="align-items:start;">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Datos de matrícula</span>
                @if($matricula)
                    <a href="{{ route('notas.boleta', $alumno) }}?anio={{ $anioActual }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-file-alt"></i> Ver boleta
                    </a>
                @endif
            </div>
            <div class="card-body">
                <div class="grid grid-2">
                    <div>
                        <div class="form-label">Estudiante</div>
                        <div style="font-weight:800;">{{ $alumno->nombre_completo }}</div>
                    </div>
                    <div>
                        <div class="form-label">DNI</div>
                        <div style="font-weight:800;">{{ $alumno->dni }}</div>
                    </div>
                    <div>
                        <div class="form-label">Sección</div>
                        <div style="font-weight:800;">{{ $matricula?->seccion?->nombre ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="form-label">Apoderado</div>
                        <div style="font-weight:800;">{{ $alumno->apoderado_nombre ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Últimas calificaciones</span>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead><tr><th>Materia</th><th>Bim.</th><th>Nota</th><th>Estado</th></tr></thead>
                    <tbody>
                    @forelse($notas->take(8) as $nota)
                        <tr>
                            <td>{{ $nota->materia->nombre ?? '—' }}</td>
                            <td>{{ $nota->bimestre }}</td>
                            <td style="font-weight:800;">{{ number_format($nota->nota, 1) }}</td>
                            <td><span class="badge {{ $nota->estado === 'aprobado' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($nota->estado) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;padding:28px;color:var(--muted);">Sin notas registradas todavía.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@endsection
