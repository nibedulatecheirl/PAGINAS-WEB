@extends('layouts.app')
@section('title', 'Alumnos')
@section('page-title', 'Gestión de Alumnos')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <div>
        <p style="color:var(--muted);font-size:13px;">Total: <strong>{{ $alumnos->total() }}</strong> alumnos encontrados</p>
    </div>
    @if(auth()->user()->hasAnyRole(['admin','secretaria']))
        <button type="button" onclick="abrirAlumnoModal()" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Alumno
        </button>
    @endif
</div>

{{-- Filtros --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 22px;">
        <form method="GET" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            <div style="flex:1;min-width:200px;">
                <label class="form-label" style="margin-bottom:4px;">Buscar</label>
                <div style="position:relative;">
                    <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--muted);"></i>
                    <input type="text" name="buscar" class="form-control" style="padding-left:34px;"
                        placeholder="Nombre, apellido o DNI..." value="{{ request('buscar') }}">
                </div>
            </div>
            <div style="min-width:160px;">
                <label class="form-label" style="margin-bottom:4px;">Estado</label>
                <select name="estado" class="form-control">
                    <option value="">Todos</option>
                    <option value="activo"     {{ request('estado')=='activo'     ? 'selected':'' }}>Activo</option>
                    <option value="inactivo"   {{ request('estado')=='inactivo'   ? 'selected':'' }}>Inactivo</option>
                    <option value="trasladado" {{ request('estado')=='trasladado' ? 'selected':'' }}>Trasladado</option>
                    <option value="egresado"   {{ request('estado')=='egresado'   ? 'selected':'' }}>Egresado</option>
                </select>
            </div>
            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabla --}}
<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Alumno</th>
                    <th>DNI</th>
                    <th>Grado / Sección</th>
                    <th>Apoderado</th>
                    <th>Estado</th>
                    <th style="text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($alumnos as $alumno)
                @php
                    $matricula = $alumno->matriculaActiva();
                    $alumnoData = [
                        "action" => route("alumnos.update", $alumno),
                        "codigo" => $alumno->codigo,
                        "dni" => $alumno->dni,
                        "nombres" => $alumno->nombres,
                        "apellidos" => $alumno->apellidos,
                        "nombre_completo" => $alumno->nombre_completo,
                        "genero" => $alumno->genero,
                        "fecha_nacimiento" => $alumno->fecha_nacimiento?->format("Y-m-d"),
                        "fecha_nacimiento_label" => $alumno->fecha_nacimiento?->format("d/m/Y"),
                        "telefono" => $alumno->telefono,
                        "email" => $alumno->email,
                        "direccion" => $alumno->direccion,
                        "estado" => $alumno->estado,
                        "apoderado_nombre" => $alumno->apoderado_nombre,
                        "apoderado_parentesco" => $alumno->apoderado_parentesco,
                        "apoderado_dni" => $alumno->apoderado_dni,
                        "apoderado_telefono" => $alumno->apoderado_telefono,
                        "apoderado_email" => $alumno->apoderado_email,
                        "matricula_grado" => $matricula?->grado?->nombre,
                        "matricula_seccion" => $matricula?->seccion?->nombre,
                        "matricula_anio" => $matricula?->anio_escolar,
                    ];
                @endphp
                <tr>
                    <td>
                        <span style="font-family:monospace;font-size:12px;background:#eef3f8;padding:3px 8px;border-radius:6px;">
                            {{ $alumno->codigo }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#18324d,#4f86bd);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:13px;flex-shrink:0;">
                                {{ strtoupper(substr($alumno->nombres,0,1)) }}{{ strtoupper(substr($alumno->apellidos,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;">{{ $alumno->nombre_completo }}</div>
                                <div style="font-size:11px;color:var(--muted);">{{ $alumno->genero === 'M' ? 'Masculino' : 'Femenino' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-family:monospace;">{{ $alumno->dni }}</td>
                    <td>
                        @if($matricula)
                            <span style="font-size:13px;">{{ $matricula->grado->nombre ?? '—' }}</span>
                            <span style="font-size:11px;color:var(--muted);"> / Sec. {{ $matricula->seccion->nombre ?? '—' }}</span>
                        @else
                            <span style="color:var(--muted);font-size:12px;">Sin matrícula</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size:13px;">{{ $alumno->apoderado_nombre ?? '—' }}</div>
                        <div style="font-size:11px;color:var(--muted);">{{ $alumno->apoderado_telefono ?? '' }}</div>
                    </td>
                    <td>
                        @php
                            $estadoClass = match($alumno->estado) {
                                'activo'     => 'badge-success',
                                'inactivo'   => 'badge-secondary',
                                'trasladado' => 'badge-warning',
                                'egresado'   => 'badge-info',
                                default      => 'badge-secondary'
                            };
                        @endphp
                        <span class="badge {{ $estadoClass }}">{{ ucfirst($alumno->estado) }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <button type="button"
                                data-alumno-action="ver"
                                data-alumno='@json($alumnoData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                class="btn btn-sm btn-secondary btn-icon" title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if(auth()->user()->hasAnyRole(['admin','secretaria']))
                                <button type="button"
                                    data-alumno-action="editar"
                                    data-alumno='@json($alumnoData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
                                    class="btn btn-sm btn-secondary btn-icon" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" action="{{ route('alumnos.destroy', $alumno) }}"
                                      onsubmit="return confirm('¿Desactivar este alumno?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Desactivar">
                                        <i class="fas fa-user-slash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:48px;color:var(--muted);">
                        <i class="fas fa-user-graduate" style="font-size:36px;margin-bottom:12px;display:block;opacity:.3;"></i>
                        No se encontraron alumnos.
                        @if(auth()->user()->hasAnyRole(['admin','secretaria']))
                            <br><button type="button" onclick="abrirAlumnoModal()" style="color:var(--primary-l);background:none;border:none;cursor:pointer;font-weight:700;">Registrar el primero</button>
                        @endif
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($alumnos->hasPages())
    <div style="padding:16px 22px;border-top:1px solid var(--border);">
        {{ $alumnos->links() }}
    </div>
    @endif
</div>

<div id="modal-alumno-detalle" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(820px,96vw);max-height:92vh;overflow:auto;padding:0;">
        <div class="card-header" style="position:sticky;top:0;z-index:2;">
            <div>
                <span class="card-title"><i class="fas fa-user-graduate" style="color:var(--primary);"></i> Detalle del Alumno</span>
                <div id="alumno-detalle-codigo" style="font-size:12px;color:var(--muted);margin-top:2px;"></div>
            </div>
            <button type="button" onclick="cerrarAlumnoDetalleModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="card-body">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:18px;">
                <div id="alumno-detalle-avatar" style="width:54px;height:54px;border-radius:14px;background:linear-gradient(135deg,#18324d,#4f86bd);display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:18px;flex-shrink:0;"></div>
                <div>
                    <div id="alumno-detalle-nombre" style="font-size:18px;font-weight:800;"></div>
                    <div id="alumno-detalle-sub" style="font-size:12px;color:var(--muted);margin-top:3px;"></div>
                </div>
            </div>
            <div class="grid grid-3">
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">DNI</div>
                    <div id="alumno-detalle-dni" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Nacimiento</div>
                    <div id="alumno-detalle-nacimiento" style="font-weight:800;margin-top:3px;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);">Estado</div>
                    <div id="alumno-detalle-estado" style="font-weight:800;margin-top:3px;"></div>
                </div>
            </div>
            <div class="grid grid-2" style="margin-top:14px;">
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);font-weight:700;margin-bottom:8px;">Contacto</div>
                    <div id="alumno-detalle-contacto" style="font-size:13px;line-height:1.7;"></div>
                </div>
                <div style="padding:14px;border:1px solid var(--border);border-radius:12px;">
                    <div style="font-size:12px;color:var(--muted);font-weight:700;margin-bottom:8px;">Matrícula actual</div>
                    <div id="alumno-detalle-matricula" style="font-size:13px;line-height:1.7;"></div>
                </div>
            </div>
            <div style="padding:14px;border:1px solid var(--border);border-radius:12px;margin-top:14px;">
                <div style="font-size:12px;color:var(--muted);font-weight:700;margin-bottom:8px;">Apoderado</div>
                <div id="alumno-detalle-apoderado" style="font-size:13px;line-height:1.7;"></div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->hasAnyRole(['admin','secretaria']))
<div id="modal-alumno" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(920px,96vw);max-height:92vh;overflow:auto;padding:0;">
        <div class="card-header" style="position:sticky;top:0;z-index:2;">
            <div>
                <span id="modal-alumno-title" class="card-title"><i class="fas fa-user-graduate" style="color:var(--primary);"></i> Nuevo Alumno</span>
                <div id="modal-alumno-subtitle" style="font-size:12px;color:var(--muted);margin-top:2px;">Registro rápido de datos personales y apoderado.</div>
            </div>
            <button type="button" onclick="cerrarAlumnoModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>

        <form id="form-alumno-modal" method="POST" action="{{ route('alumnos.store') }}">
            @csrf
            <input type="hidden" name="_method" id="alumno-method" value="POST">
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr;gap:20px;">
                    <section>
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                            <div class="stat-icon blue" style="width:38px;height:38px;border-radius:10px;font-size:15px;"><i class="fas fa-id-card"></i></div>
                            <div>
                                <div style="font-weight:800;">Datos personales</div>
                                <div style="font-size:12px;color:var(--muted);">Información básica del estudiante.</div>
                            </div>
                        </div>
                        <div class="grid grid-2">
                            <div class="form-group">
                                <label class="form-label">DNI *</label>
                                <input type="text" name="dni" id="alumno-dni" class="form-control" maxlength="20" required placeholder="12345678">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Género *</label>
                                <select name="genero" id="alumno-genero" class="form-control" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-2">
                            <div class="form-group">
                                <label class="form-label">Nombres *</label>
                                <input type="text" name="nombres" id="alumno-nombres" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Apellidos *</label>
                                <input type="text" name="apellidos" id="alumno-apellidos" class="form-control" required>
                            </div>
                        </div>
                        <div class="grid grid-3">
                            <div class="form-group">
                                <label class="form-label">Fecha de Nacimiento *</label>
                                <input type="date" name="fecha_nacimiento" id="alumno-fecha_nacimiento" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telefono" id="alumno-telefono" class="form-control" placeholder="987654321">
                            </div>
                            <div class="form-group" id="alumno-estado-group" style="display:none;">
                                <label class="form-label">Estado</label>
                                <select name="estado" id="alumno-estado" class="form-control" disabled>
                                    @foreach(['activo','inactivo','trasladado','egresado'] as $estado)
                                        <option value="{{ $estado }}">{{ ucfirst($estado) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="alumno-email-group">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" id="alumno-email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" id="alumno-direccion" class="form-control">
                        </div>
                    </section>

                    <section style="border-top:1px solid var(--border);padding-top:20px;">
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                            <div class="stat-icon green" style="width:38px;height:38px;border-radius:10px;font-size:15px;"><i class="fas fa-user-friends"></i></div>
                            <div>
                                <div style="font-weight:800;">Datos del apoderado</div>
                                <div style="font-size:12px;color:var(--muted);">Contacto familiar o responsable.</div>
                            </div>
                        </div>
                        <div class="grid grid-2">
                            <div class="form-group">
                                <label class="form-label">Nombre del Apoderado</label>
                                <input type="text" name="apoderado_nombre" id="alumno-apoderado_nombre" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Parentesco</label>
                                <select name="apoderado_parentesco" id="alumno-apoderado_parentesco" class="form-control">
                                    <option value="">Seleccionar...</option>
                                    @foreach(['Padre','Madre','Abuelo/a','Tío/a','Otro'] as $parentesco)
                                        <option value="{{ $parentesco }}">{{ $parentesco }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-3">
                            <div class="form-group">
                                <label class="form-label">DNI Apoderado</label>
                                <input type="text" name="apoderado_dni" id="alumno-apoderado_dni" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Teléfono Apoderado</label>
                                <input type="text" name="apoderado_telefono" id="alumno-apoderado_telefono" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Apoderado</label>
                                <input type="email" name="apoderado_email" id="alumno-apoderado_email" class="form-control">
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;padding:18px 22px;border-top:1px solid var(--border);background:#f7fafd;position:sticky;bottom:0;">
                <button type="button" onclick="cerrarAlumnoModal()" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Alumno</button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
const alumnoStoreUrl = "{{ route('alumnos.store') }}";
const alumnoFields = [
    'dni','nombres','apellidos','genero','fecha_nacimiento','telefono','email','direccion','estado',
    'apoderado_nombre','apoderado_parentesco','apoderado_dni','apoderado_telefono','apoderado_email'
];

function abrirAlumnoModal(data = null) {
    const modal = document.getElementById('modal-alumno');
    const form = document.getElementById('form-alumno-modal');
    if (!modal || !form) return;

    form.reset();
    document.getElementById('alumno-method').value = data ? 'PUT' : 'POST';
    form.action = data?.action || alumnoStoreUrl;
    document.getElementById('modal-alumno-title').innerHTML = data
        ? '<i class="fas fa-edit" style="color:var(--primary);"></i> Editar Alumno'
        : '<i class="fas fa-user-graduate" style="color:var(--primary);"></i> Nuevo Alumno';
    document.getElementById('modal-alumno-subtitle').textContent = data?.codigo
        ? 'Código: ' + data.codigo
        : 'Registro rápido de datos personales y apoderado.';

    const estadoGroup = document.getElementById('alumno-estado-group');
    const estadoInput = document.getElementById('alumno-estado');
    estadoGroup.style.display = data ? '' : 'none';
    estadoInput.disabled = !data;

    alumnoFields.forEach(field => {
        const input = document.getElementById('alumno-' + field);
        if (input) input.value = data?.[field] || '';
    });
    if (!data) document.getElementById('alumno-fecha_nacimiento').value = '';

    modal.style.display = 'flex';
    setTimeout(() => document.getElementById('alumno-dni')?.focus(), 60);
}

function cerrarAlumnoModal() {
    const modal = document.getElementById('modal-alumno');
    if (modal) modal.style.display = 'none';
}

function alumnoDataDesdeBoton(button) {
    try {
        return JSON.parse(button.getAttribute('data-alumno') || '{}');
    } catch (error) {
        console.error('No se pudo leer la información del alumno.', error);
        return null;
    }
}

document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-alumno-action]');
    if (!button) return;

    const data = alumnoDataDesdeBoton(button);
    if (!data) return;

    if (button.dataset.alumnoAction === 'ver') {
        abrirAlumnoDetalleModal(data);
    }

    if (button.dataset.alumnoAction === 'editar') {
        abrirAlumnoModal(data);
    }
});

function alumnoTexto(value) {
    return value || '—';
}
function alumnoHtml(value) {
    return String(alumnoTexto(value))
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function abrirAlumnoDetalleModal(data) {
    document.getElementById('alumno-detalle-codigo').textContent = data?.codigo ? 'Código: ' + data.codigo : '';
    document.getElementById('alumno-detalle-avatar').textContent = ((data?.nombres || '?').charAt(0) + (data?.apellidos || '?').charAt(0)).toUpperCase();
    document.getElementById('alumno-detalle-nombre').textContent = data?.nombre_completo || ((data?.nombres || '') + ' ' + (data?.apellidos || '')).trim();
    document.getElementById('alumno-detalle-sub').textContent = data?.genero === 'M' ? 'Masculino' : (data?.genero === 'F' ? 'Femenino' : '—');
    document.getElementById('alumno-detalle-dni').textContent = alumnoTexto(data?.dni);
    document.getElementById('alumno-detalle-nacimiento').textContent = alumnoTexto(data?.fecha_nacimiento_label);
    document.getElementById('alumno-detalle-estado').textContent = data?.estado ? data.estado.charAt(0).toUpperCase() + data.estado.slice(1) : '—';
    document.getElementById('alumno-detalle-contacto').innerHTML =
        '<strong>Teléfono:</strong> ' + alumnoHtml(data?.telefono) + '<br>' +
        '<strong>Email:</strong> ' + alumnoHtml(data?.email) + '<br>' +
        '<strong>Dirección:</strong> ' + alumnoHtml(data?.direccion);
    document.getElementById('alumno-detalle-matricula').innerHTML =
        data?.matricula_grado
            ? '<strong>Grado:</strong> ' + alumnoHtml(data.matricula_grado) + '<br><strong>Sección:</strong> ' + alumnoHtml(data.matricula_seccion) + '<br><strong>Año:</strong> ' + alumnoHtml(data.matricula_anio)
            : 'Sin matrícula activa';
    document.getElementById('alumno-detalle-apoderado').innerHTML =
        '<strong>Nombre:</strong> ' + alumnoHtml(data?.apoderado_nombre) + '<br>' +
        '<strong>Parentesco:</strong> ' + alumnoHtml(data?.apoderado_parentesco) + '<br>' +
        '<strong>DNI:</strong> ' + alumnoHtml(data?.apoderado_dni) + '<br>' +
        '<strong>Teléfono:</strong> ' + alumnoHtml(data?.apoderado_telefono) + '<br>' +
        '<strong>Email:</strong> ' + alumnoHtml(data?.apoderado_email);
    document.getElementById('modal-alumno-detalle').style.display = 'flex';
}

function cerrarAlumnoDetalleModal() {
    document.getElementById('modal-alumno-detalle').style.display = 'none';
}
</script>
@endpush
