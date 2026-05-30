@extends('layouts.app')
@section('title', 'Grados y Secciones')
@section('page-title', 'Grados y Secciones')

@section('content')

<div class="grid grid-2" style="gap:24px;align-items:start;">

    {{-- Lista de Grados --}}
    <div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <h3 style="font-size:15px;font-weight:700;">Grados Registrados</h3>
            <button type="button" onclick="abrirGradoModal()" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nuevo Grado
            </button>
        </div>

        <div class="card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>Grado</th><th>Nivel</th><th>Secciones</th><th>Matrículas</th><th style="text-align:center;">Acciones</th></tr>
                    </thead>
                    <tbody>
                    @forelse($grados as $g)
                        @php
                            $gradoData = [
                                'id' => $g->id,
                                'nombre' => $g->nombre,
                                'nivel' => $g->nivel,
                                'secciones_count' => $g->secciones_count,
                                'matriculas_count' => $g->matriculas_count,
                                'secciones_url' => route('grados.secciones', $g),
                            ];
                        @endphp
                        <tr>
                            <td style="font-weight:600;">{{ $g->nombre }}</td>
                            <td>
                                @php $nb = match($g->nivel){ 'inicial'=>'badge-info','primaria'=>'badge-primary','secundaria'=>'badge-warning',default=>'badge-secondary' }; @endphp
                                <span class="badge {{ $nb }}">{{ ucfirst($g->nivel) }}</span>
                            </td>
                            <td>
                                <span style="font-weight:700;">{{ $g->secciones_count }}</span>
                                <a href="{{ route('grados.secciones', $g) }}" style="font-size:11px;color:var(--primary-l);margin-left:6px;">Ver →</a>
                            </td>
                            <td><span style="font-weight:700;">{{ $g->matriculas_count }}</span></td>
                            <td>
                                <div style="display:flex;gap:6px;justify-content:center;">
                                    <button type="button"
                                        data-grado-action="ver"
                                        data-grado='@json($gradoData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                        class="btn btn-sm btn-secondary btn-icon" title="Ver grado">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('grados.secciones', $g) }}" class="btn btn-sm btn-secondary btn-icon" title="Gestionar secciones">
                                        <i class="fas fa-door-open"></i>
                                    </a>
                                    <button type="button"
                                        data-grado-action="editar"
                                        data-grado='@json($gradoData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                        class="btn btn-sm btn-secondary btn-icon"><i class="fas fa-edit"></i></button>
                                    @if($g->matriculas_count == 0)
                                    <form method="POST" action="{{ route('grados.destroy', $g) }}" onsubmit="return confirm('¿Eliminar grado?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-icon"><i class="fas fa-trash"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center;padding:32px;color:var(--muted);">Sin grados registrados</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Resumen visual --}}
    <div>
        <div style="margin-bottom:14px;">
            <h3 style="font-size:15px;font-weight:700;">Distribución por Nivel</h3>
        </div>
        @foreach([['inicial','badge-info','fas fa-child'],['primaria','badge-primary','fas fa-school'],['secundaria','badge-warning','fas fa-graduation-cap']] as [$nivel,$badge,$icon])
        @php $count = $grados->where('nivel',$nivel)->count(); @endphp
        <div class="card" style="margin-bottom:12px;padding:16px 20px;display:flex;align-items:center;gap:14px;">
            <div style="width:44px;height:44px;border-radius:12px;background:var(--bg);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">
                <i class="{{ $icon }}" style="color:var(--primary-l);"></i>
            </div>
            <div style="flex:1;">
                <div style="font-weight:700;font-size:14px;">{{ ucfirst($nivel) }}</div>
                <div style="font-size:12px;color:var(--muted);">{{ $count }} grado(s) · {{ $grados->where('nivel',$nivel)->sum('secciones_count') }} secciones</div>
            </div>
            <span class="badge {{ $badge }}">{{ $count }}</span>
        </div>
        @endforeach

        <div style="margin-top:16px;">
            <a href="{{ route('materias.index') }}" class="btn btn-primary" style="width:100%;justify-content:center;">
                <i class="fas fa-book-open"></i> Gestionar Materias y Docentes
            </a>
        </div>
    </div>

</div>

{{-- Modal Crear/Editar Grado --}}
<div id="modal-grado" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:28px;width:420px;box-shadow:0 20px 60px rgba(0,0,0,.3);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 id="modal-grado-title" style="font-size:16px;font-weight:700;">Nuevo Grado</h3>
            <button type="button" onclick="cerrarGradoModal()" style="background:none;border:none;font-size:18px;cursor:pointer;color:var(--muted);">✕</button>
        </div>
        <form id="form-grado" method="POST" action="{{ route('grados.store') }}">
            @csrf
            <input type="hidden" name="_method" id="grado-method" value="POST">
            <div class="form-group">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre" id="grado-nombre" class="form-control" required placeholder="Ej: 1er Grado">
            </div>
            <div class="form-group">
                <label class="form-label">Nivel *</label>
                <select name="nivel" id="grado-nivel" class="form-control" required>
                    <option value="inicial">Inicial</option>
                    <option value="primaria">Primaria</option>
                    <option value="secundaria">Secundaria</option>
                </select>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <button type="button" onclick="cerrarGradoModal()" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Ver Grado --}}
<div id="modal-grado-detalle" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(520px,94vw);padding:0;">
        <div class="card-header">
            <div>
                <span class="card-title"><i class="fas fa-layer-group" style="color:var(--primary);"></i> Detalle del Grado</span>
                <div style="font-size:12px;color:var(--muted);margin-top:2px;">Resumen académico.</div>
            </div>
            <button type="button" onclick="cerrarGradoDetalleModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="card-body">
            <div style="display:grid;gap:12px;">
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;background:#f7fafd;">
                    <div style="font-size:12px;color:var(--muted);font-weight:700;">Grado</div>
                    <div id="grado-detalle-nombre" style="font-size:18px;font-weight:800;margin-top:3px;"></div>
                </div>
                <div class="grid grid-3">
                    <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                        <div style="font-size:12px;color:var(--muted);">Nivel</div>
                        <div id="grado-detalle-nivel" style="font-weight:800;margin-top:3px;"></div>
                    </div>
                    <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                        <div style="font-size:12px;color:var(--muted);">Secciones</div>
                        <div id="grado-detalle-secciones" style="font-weight:800;margin-top:3px;"></div>
                    </div>
                    <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                        <div style="font-size:12px;color:var(--muted);">Matrículas</div>
                        <div id="grado-detalle-matriculas" style="font-weight:800;margin-top:3px;"></div>
                    </div>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:18px;">
                <a id="grado-detalle-secciones-link" href="#" class="btn btn-primary"><i class="fas fa-door-open"></i> Gestionar secciones</a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const gradoRouteBase = "{{ url('grados') }}";
const gradoStoreUrl = "{{ route('grados.store') }}";

function abrirGradoModal(data = null) {
    const form = document.getElementById('form-grado');
    form.reset();
    document.getElementById('modal-grado-title').textContent = data ? 'Editar Grado' : 'Nuevo Grado';
    form.action = data ? gradoRouteBase + '/' + data.id : gradoStoreUrl;
    document.getElementById('grado-method').value = data ? 'PUT' : 'POST';
    document.getElementById('grado-nombre').value = data?.nombre || '';
    document.getElementById('grado-nivel').value = data?.nivel || 'primaria';
    document.getElementById('modal-grado').style.display = 'flex';
    setTimeout(() => document.getElementById('grado-nombre')?.focus(), 60);
}

function cerrarGradoModal() {
    document.getElementById('modal-grado').style.display = 'none';
}

function gradoDataDesdeBoton(button) {
    try {
        return JSON.parse(button.getAttribute('data-grado') || '{}');
    } catch (error) {
        console.error('No se pudo leer la información del grado.', error);
        return null;
    }
}

document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-grado-action]');
    if (!button) return;

    const data = gradoDataDesdeBoton(button);
    if (!data) return;

    if (button.dataset.gradoAction === 'ver') {
        abrirGradoDetalleModal(data);
    }

    if (button.dataset.gradoAction === 'editar') {
        abrirGradoModal(data);
    }
});

function editarGrado(id, nombre, nivel) {
    abrirGradoModal({ id, nombre, nivel });
}

function abrirGradoDetalleModal(data) {
    document.getElementById('grado-detalle-nombre').textContent = data?.nombre || '—';
    document.getElementById('grado-detalle-nivel').textContent = data?.nivel ? data.nivel.charAt(0).toUpperCase() + data.nivel.slice(1) : '—';
    document.getElementById('grado-detalle-secciones').textContent = data?.secciones_count ?? 0;
    document.getElementById('grado-detalle-matriculas').textContent = data?.matriculas_count ?? 0;
    document.getElementById('grado-detalle-secciones-link').href = data?.secciones_url || '#';
    document.getElementById('modal-grado-detalle').style.display = 'flex';
}

function cerrarGradoDetalleModal() {
    document.getElementById('modal-grado-detalle').style.display = 'none';
}
</script>
@endpush
