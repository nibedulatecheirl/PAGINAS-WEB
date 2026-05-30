@extends('layouts.app')
@section('title', 'Libro de Notas')
@section('page-title', 'Libro de Notas')

@push('styles')
<style>
    .notas-filter-card { margin-bottom: 24px; }
    .notas-filter-grid {
        display: grid;
        grid-template-columns: minmax(130px, .65fr) minmax(240px, 1.4fr) minmax(220px, 1.2fr) auto;
        gap: 16px;
        align-items: end;
    }
    .notas-context { margin-bottom: 18px; }
    .notas-context .card-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
    }
    .notas-context-main {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }
    .notas-context-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        line-height: 1.25;
    }
    .notas-context-subtitle {
        font-size: 12.5px;
        color: var(--muted);
        margin-top: 4px;
    }
    .notas-context-chips {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    .notas-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 10px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: #f7fafd;
        color: #4a596b;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }
    .bimestre-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 8px;
        border: 1px solid var(--border);
        border-radius: 14px;
        background: #edf4fa;
        margin-bottom: 18px;
    }
    .bimestre-tab {
        min-height: 42px;
        padding: 9px 14px;
        border-radius: 10px !important;
    }
    .notas-panel { margin-bottom: 22px; }
    .notas-panel .card { overflow: hidden; }
    .notas-panel-header {
        align-items: flex-start;
        gap: 16px;
    }
    .notas-panel-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .notas-panel-caption {
        font-size: 12px;
        color: var(--muted);
        margin-top: 4px;
    }
    .notas-panel-meta {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    .notas-table table { min-width: 720px; }
    .notas-table thead th { padding: 14px 18px; }
    .notas-table tbody td { padding: 16px 18px; }
    .nota-input {
        max-width: 112px;
        min-height: 46px;
        margin: 0 auto;
        text-align: center;
        font-size: 16px;
        font-weight: 800;
    }
    .notas-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 18px;
    }
    .notas-summary {
        display: grid;
        grid-template-columns: repeat(4, minmax(140px, 1fr));
        gap: 14px;
        margin-top: 20px;
    }
    .notas-summary-card {
        padding: 16px;
        border-radius: 12px;
        border: 1px solid var(--border);
        background: #f7fafd;
        text-align: center;
    }
    .notas-summary-card strong {
        display: block;
        font-size: 26px;
        line-height: 1;
        margin-bottom: 6px;
    }
    .notas-summary-card span {
        font-size: 12px;
        font-weight: 700;
    }
    .notas-summary-card.success { background: #e2f6ee; border-color: #b7e8d4; color: var(--success); }
    .notas-summary-card.danger { background: #fde8ea; border-color: #f3b9bf; color: var(--danger); }
    .notas-summary-card.muted { color: var(--muted); }
    .notas-summary-card.primary { background: #e5eef8; border-color: #c2d7ea; color: var(--primary); }
    .notas-empty {
        padding: 52px 28px;
        text-align: center;
        color: var(--muted);
    }
    .notas-empty i {
        font-size: 48px;
        opacity: .22;
        display: block;
        margin-bottom: 16px;
    }

    @media (max-width: 1080px) {
        .notas-filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 720px) {
        .notas-filter-grid { grid-template-columns: 1fr; }
        .notas-context .card-body { align-items: flex-start; flex-direction: column; }
        .notas-context-actions { width: 100%; }
        .notas-context-actions .btn { width: 100%; justify-content: center; }
        .notas-panel-header { flex-direction: column; }
        .notas-panel-meta { justify-content: flex-start; }
        .notas-summary { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media print {
        .notas-filter-card,
        .bimestre-tabs,
        .notas-context-actions,
        .notas-panel-actions { display: none !important; }
        .notas-context { margin-bottom: 12px; }
        .notas-table table { min-width: 0; }
        .notas-panel { margin-bottom: 0; }
    }
</style>
@endpush

@section('content')

<div class="card notas-filter-card no-print">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-clipboard-list" style="color:var(--primary);"></i> Seleccionar libro</span>
        <span style="font-size:12px;color:var(--muted);font-weight:700;">Año, aula y materia</span>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('notas.index') }}" class="notas-filter-grid">
            <div class="form-group" style="margin:0;">
                <label class="form-label">Año Escolar</label>
                <select name="anio" class="form-control">
                    @for($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $anio == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group" style="margin:0;">
                <label class="form-label">Sección *</label>
                <select name="seccion_id" class="form-control" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($secciones as $s)
                        <option value="{{ $s->id }}" {{ $seccionId == $s->id ? 'selected' : '' }}>
                            {{ $s->grado->nombre }} — Sec. {{ $s->nombre }} ({{ ucfirst($s->turno) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin:0;">
                <label class="form-label">Materia *</label>
                <select name="materia_id" class="form-control" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($materias as $m)
                        <option value="{{ $m->id }}" {{ $materiaId == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="min-height:46px;">
                <i class="fas fa-search"></i> Ver Notas
            </button>
        </form>
    </div>
</div>

@if($seccion && $materia)

<div class="card notas-context">
    <div class="card-body">
        <div class="notas-context-main">
            <div class="stat-icon blue" style="width:54px;height:54px;border-radius:14px;font-size:21px;">
                <i class="fas fa-book-open"></i>
            </div>
            <div style="min-width:0;">
                <div class="notas-context-title">
                    {{ $materia->nombre }} — {{ $seccion->grado->nombre }}, Sección {{ $seccion->nombre }}
                </div>
                <div class="notas-context-subtitle">Libro de calificaciones del año escolar {{ $anio }}</div>
                <div class="notas-context-chips">
                    <span class="notas-chip"><i class="fas fa-users"></i>{{ $libroNotas->count() }} alumno(s)</span>
                    <span class="notas-chip"><i class="fas fa-layer-group"></i>{{ $numBimestres }} bimestre(s)</span>
                    <span class="notas-chip"><i class="fas fa-check-circle"></i>Nota mínima {{ number_format($notaMinima, 0) }}</span>
                    <span class="notas-chip"><i class="fas fa-star"></i>Máxima {{ number_format($notaMaxima, 0) }}</span>
                </div>
            </div>
        </div>
        <div class="notas-context-actions no-print">
            <a href="{{ route('notas.index') }}?seccion_id={{ $seccionId }}&materia_id={{ $materiaId }}&anio={{ $anio }}&imprimir=1"
               class="btn btn-secondary btn-sm" onclick="window.print();return false;">
                <i class="fas fa-print"></i> Imprimir
            </a>
        </div>
    </div>
</div>

<div class="bimestre-tabs no-print" id="tabs-bimestre">
    @for($b = 1; $b <= $numBimestres; $b++)
        <button type="button" onclick="cambiarBimestre({{ $b }})" id="tab-{{ $b }}"
            class="btn bimestre-tab {{ $b == 1 ? 'btn-primary' : 'btn-secondary' }}">
            {{ $b }}° Bimestre
        </button>
    @endfor
    <button type="button" onclick="cambiarBimestre(0)" id="tab-0" class="btn bimestre-tab btn-secondary">
        <i class="fas fa-table"></i> Resumen
    </button>
</div>

@for($b = 1; $b <= $numBimestres; $b++)
@php
    $registradasBimestre = $libroNotas->filter(fn ($row) => $row['bimestres'][$b] && $row['bimestres'][$b]->nota !== null)->count();
@endphp
<div id="panel-{{ $b }}" class="panel-bimestre notas-panel" style="{{ $b > 1 ? 'display:none;' : '' }}">
    <div class="card">
        <div class="card-header notas-panel-header">
            <div>
                <div class="notas-panel-title">
                    <i class="fas fa-edit"></i> Ingreso de Notas — {{ $b }}° Bimestre
                </div>
                <div class="notas-panel-caption">Registra notas de 0 a {{ number_format($notaMaxima, 0) }}. La aprobación inicia en {{ number_format($notaMinima, 0) }}.</div>
            </div>
            <div class="notas-panel-meta">
                <span class="notas-chip"><i class="fas fa-pen"></i>{{ $registradasBimestre }}/{{ $libroNotas->count() }} registradas</span>
            </div>
        </div>

        <form method="POST" action="{{ route('notas.guardar') }}">
            @csrf
            <input type="hidden" name="seccion_id" value="{{ $seccionId }}">
            <input type="hidden" name="materia_id" value="{{ $materiaId }}">
            <input type="hidden" name="anio_escolar" value="{{ $anio }}">
            <input type="hidden" name="bimestre" value="{{ $b }}">

            <div class="card-body">
                <div class="table-wrapper notas-table">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:56px;">#</th>
                                <th>Alumno</th>
                                <th style="text-align:center;width:150px;">Nota</th>
                                <th style="text-align:center;width:150px;">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($libroNotas as $i => $row)
                            @php $notaObj = $row['bimestres'][$b]; @endphp
                            <tr>
                                <td style="color:var(--muted);font-size:12px;font-weight:700;">{{ $i + 1 }}</td>
                                <td>
                                    <div style="font-weight:700;">{{ $row['alumno']->nombre_completo }}</div>
                                    <div style="font-size:11px;color:var(--muted);margin-top:2px;">{{ $row['alumno']->codigo }}</div>
                                </td>
                                <td style="text-align:center;">
                                    <input type="number"
                                        name="notas[{{ $row['alumno']->id }}]"
                                        value="{{ $notaObj?->nota ?? '' }}"
                                        min="0" max="{{ $notaMaxima }}" step="0.5"
                                        class="form-control nota-input"
                                        placeholder="—"
                                        oninput="actualizarEstado(this, {{ $notaMinima }})">
                                </td>
                                <td style="text-align:center;">
                                    @if($notaObj)
                                        <span data-estado-nota class="badge {{ $notaObj->nota >= $notaMinima ? 'badge-success' : 'badge-danger' }}">
                                            {{ $notaObj->nota >= $notaMinima ? 'Aprobado' : 'Desaprobado' }}
                                        </span>
                                    @else
                                        <span data-estado-nota class="badge badge-secondary">Pendiente</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="notas-actions notas-panel-actions no-print">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar {{ $b }}° Bimestre
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endfor

<div id="panel-0" class="notas-panel" style="display:none;">
    <div class="card">
        <div class="card-header notas-panel-header">
            <div>
                <div class="notas-panel-title">
                    <i class="fas fa-table"></i> Resumen General de Notas
                </div>
                <div class="notas-panel-caption">Promedio por alumno y estado final de la materia.</div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-wrapper notas-table">
                <table>
                    <thead>
                        <tr>
                            <th style="width:56px;">#</th>
                            <th>Alumno</th>
                            @for($b = 1; $b <= $numBimestres; $b++)
                                <th style="text-align:center;">B{{ $b }}</th>
                            @endfor
                            <th style="text-align:center;">Promedio</th>
                            <th style="text-align:center;">Estado</th>
                            <th class="no-print" style="text-align:center;">Boleta</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($libroNotas as $i => $row)
                        <tr>
                            <td style="color:var(--muted);font-size:12px;font-weight:700;">{{ $i + 1 }}</td>
                            <td>
                                <div style="font-weight:700;">{{ $row['alumno']->nombre_completo }}</div>
                                <div style="font-size:11px;color:var(--muted);margin-top:2px;">{{ $row['alumno']->codigo }}</div>
                            </td>
                            @for($b = 1; $b <= $numBimestres; $b++)
                                @php $n = $row['bimestres'][$b]; @endphp
                                <td style="text-align:center;">
                                    @if($n)
                                        <span style="font-weight:800;color:{{ $n->nota >= $notaMinima ? 'var(--success)' : 'var(--danger)' }};">
                                            {{ number_format($n->nota, 1) }}
                                        </span>
                                    @else
                                        <span style="color:var(--muted);">—</span>
                                    @endif
                                </td>
                            @endfor
                            <td style="text-align:center;font-weight:800;font-size:15px;color:{{ $row['promedio'] !== null ? ($row['promedio'] >= $notaMinima ? 'var(--success)' : 'var(--danger)') : 'var(--muted)' }};">
                                {{ $row['promedio'] !== null ? number_format($row['promedio'], 2) : '—' }}
                            </td>
                            <td style="text-align:center;">
                                @if($row['estado'] === 'aprobado')
                                    <span class="badge badge-success">Aprobado</span>
                                @elseif($row['estado'] === 'desaprobado')
                                    <span class="badge badge-danger">Desaprobado</span>
                                @else
                                    <span class="badge badge-secondary">Pendiente</span>
                                @endif
                            </td>
                            <td class="no-print" style="text-align:center;">
                                <button type="button"
                                   data-boleta-url="{{ route('notas.boleta', $row['alumno']->id) }}?anio={{ $anio }}"
                                   class="btn btn-sm btn-secondary btn-icon" title="Ver boleta">
                                    <i class="fas fa-file-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @php
                $aprobados = $libroNotas->where('estado','aprobado')->count();
                $desaprobados = $libroNotas->where('estado','desaprobado')->count();
                $pendientes = $libroNotas->where('estado','pendiente')->count();
                $total = $libroNotas->count();
            @endphp
            @if($total > 0)
                <div class="notas-summary">
                    <div class="notas-summary-card success">
                        <strong>{{ $aprobados }}</strong>
                        <span>Aprobados</span>
                    </div>
                    <div class="notas-summary-card danger">
                        <strong>{{ $desaprobados }}</strong>
                        <span>Desaprobados</span>
                    </div>
                    <div class="notas-summary-card muted">
                        <strong>{{ $pendientes }}</strong>
                        <span>Pendientes</span>
                    </div>
                    <div class="notas-summary-card primary">
                        <strong>{{ $total > 0 ? round(($aprobados / $total) * 100) : 0 }}%</strong>
                        <span>Aprobación</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@else
<div class="card notas-empty">
    <i class="fas fa-clipboard-list"></i>
    <p style="font-size:15px;font-weight:700;">Selecciona una sección y materia para ver el libro de notas.</p>
</div>
@endif

<div id="modal-boleta" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(980px,96vw);height:min(860px,92vh);padding:0;display:flex;flex-direction:column;">
        <div class="card-header" style="flex-shrink:0;">
            <div>
                <span class="card-title"><i class="fas fa-file-alt" style="color:var(--primary);"></i> Boleta de Calificaciones</span>
                <div style="font-size:12px;color:var(--muted);margin-top:2px;">Vista rápida del reporte.</div>
            </div>
            <button type="button" onclick="cerrarBoletaModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <iframe id="boleta-frame" src="about:blank" style="width:100%;flex:1;border:0;background:#fff;"></iframe>
    </div>
</div>

@endsection

@push('scripts')
<script>
function cambiarBimestre(b) {
    document.querySelectorAll('.panel-bimestre').forEach(panel => panel.style.display = 'none');
    const resumen = document.getElementById('panel-0');
    if (resumen) resumen.style.display = 'none';

    for (let i = 0; i <= {{ $numBimestres }}; i++) {
        const tab = document.getElementById('tab-' + i);
        if (tab) {
            tab.classList.remove('btn-primary');
            tab.classList.add('btn-secondary');
        }
    }

    const panel = document.getElementById('panel-' + b);
    if (panel) panel.style.display = 'block';
    const tab = document.getElementById('tab-' + b);
    if (tab) {
        tab.classList.remove('btn-secondary');
        tab.classList.add('btn-primary');
    }
}

function actualizarEstado(input, notaMinima) {
    const val = parseFloat(input.value);
    const row = input.closest('tr');
    const estadoBadge = row?.querySelector('[data-estado-nota]');
    if (!estadoBadge) return;

    if (Number.isNaN(val) || input.value === '') {
        estadoBadge.className = 'badge badge-secondary';
        estadoBadge.textContent = 'Pendiente';
    } else if (val >= notaMinima) {
        estadoBadge.className = 'badge badge-success';
        estadoBadge.textContent = 'Aprobado';
    } else {
        estadoBadge.className = 'badge badge-danger';
        estadoBadge.textContent = 'Desaprobado';
    }
}

function abrirBoletaModal(url) {
    document.getElementById('boleta-frame').src = url;
    document.getElementById('modal-boleta').style.display = 'flex';
}

function cerrarBoletaModal() {
    document.getElementById('modal-boleta').style.display = 'none';
    document.getElementById('boleta-frame').src = 'about:blank';
}

document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-boleta-url]');
    if (!button) return;
    abrirBoletaModal(button.dataset.boletaUrl);
});
</script>
@endpush
