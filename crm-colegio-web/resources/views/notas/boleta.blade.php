@extends('layouts.app')
@section('title', 'Boleta de Notas')
@section('page-title', 'Boleta de Notas')

@push('styles')
<style>
@media print {
    .sidebar, .topbar, .no-print { display:none!important; }
    .main-content { margin-left:0!important; }
    .page-body { padding:0!important; background:#fff!important; }
    .boleta { box-shadow:none!important; border:1px solid #ccd8e6!important; }
}
</style>
@endpush

@section('content')

<div class="no-print" style="display:flex;gap:12px;margin-bottom:20px;align-items:center;">
    <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
    <button onclick="window.print()" class="btn btn-success" style="margin-left:auto;"><i class="fas fa-print"></i> Imprimir</button>
</div>

<div class="boleta card" style="max-width:860px;margin:0 auto;">
    <div style="padding:28px 32px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:18px;">
        <div style="display:flex;align-items:center;gap:16px;">
            <div style="width:64px;height:64px;border-radius:18px;background:linear-gradient(135deg,var(--primary),var(--info));display:flex;align-items:center;justify-content:center;color:#fff;font-size:26px;">
                <i class="fas fa-school"></i>
            </div>
            <div>
                <div style="font-size:22px;font-weight:900;">{{ $config['nombre_colegio'] }}</div>
                <div style="font-size:13px;color:var(--muted);">Boleta oficial de calificaciones</div>
            </div>
        </div>
        <div style="text-align:right;">
            <div class="badge badge-primary">Año {{ $anio }}</div>
            <div style="margin-top:8px;font-size:12px;color:var(--muted);">Generado: {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <div class="card-body">
        <div class="grid grid-4" style="margin-bottom:24px;">
            <div>
                <div class="form-label">Estudiante</div>
                <div style="font-weight:800;">{{ $matricula->alumno->nombre_completo }}</div>
            </div>
            <div>
                <div class="form-label">Código</div>
                <div style="font-weight:800;">{{ $matricula->alumno->codigo }}</div>
            </div>
            <div>
                <div class="form-label">Grado</div>
                <div style="font-weight:800;">{{ $matricula->grado->nombre }}</div>
            </div>
            <div>
                <div class="form-label">Sección</div>
                <div style="font-weight:800;">{{ $matricula->seccion->nombre }}</div>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Materia</th>
                        @for($b = 1; $b <= $config['num_bimestres']; $b++)
                            <th style="text-align:center;">B{{ $b }}</th>
                        @endfor
                        <th style="text-align:center;">Promedio</th>
                        <th style="text-align:center;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($libroMaterias as $row)
                    <tr>
                        <td style="font-weight:700;">{{ $row['materia']->nombre }}</td>
                        @for($b = 1; $b <= $config['num_bimestres']; $b++)
                            <td style="text-align:center;">{{ $row['bimestres'][$b] !== null ? number_format($row['bimestres'][$b], 1) : '—' }}</td>
                        @endfor
                        <td style="text-align:center;font-weight:900;color:{{ $row['promedio'] !== null ? ($row['promedio'] >= $config['nota_minima'] ? 'var(--success)' : 'var(--danger)') : 'var(--muted)' }};">
                            {{ $row['promedio'] !== null ? number_format($row['promedio'], 2) : '—' }}
                        </td>
                        <td style="text-align:center;">
                            <span class="badge {{ $row['estado'] === 'aprobado' ? 'badge-success' : ($row['estado'] === 'desaprobado' ? 'badge-danger' : 'badge-secondary') }}">
                                {{ ucfirst($row['estado']) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="{{ $config['num_bimestres'] + 3 }}" style="text-align:center;padding:32px;color:var(--muted);">Sin materias activas para mostrar.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @php
            $promedios = $libroMaterias->pluck('promedio')->filter();
            $promedioGeneral = $promedios->count() ? round($promedios->avg(), 2) : null;
        @endphp
        <div style="display:flex;justify-content:flex-end;margin-top:24px;">
            <div style="min-width:260px;background:#f7fafd;border-radius:14px;padding:18px;border:1px solid var(--border);">
                <div style="display:flex;justify-content:space-between;gap:16px;">
                    <span style="color:var(--muted);font-weight:700;">Promedio general</span>
                    <span style="font-size:24px;font-weight:900;color:var(--primary);">{{ $promedioGeneral !== null ? number_format($promedioGeneral, 2) : '—' }}</span>
                </div>
                <div style="font-size:12px;color:var(--muted);margin-top:4px;">Nota mínima aprobatoria: {{ $config['nota_minima'] }}</div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;margin-top:48px;">
            <div style="text-align:center;">
                <div style="height:64px;border-bottom:1.5px solid #334155;margin-bottom:8px;"></div>
                <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;">Tutor(a)</div>
            </div>
            <div style="text-align:center;">
                <div style="height:64px;border-bottom:1.5px solid #334155;margin-bottom:8px;"></div>
                <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;">Dirección académica</div>
            </div>
        </div>
    </div>
</div>

@endsection

