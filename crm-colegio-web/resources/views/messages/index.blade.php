@extends('layouts.app')
@section('title', 'Mensajes')
@section('page-title', 'Bandeja de Mensajes')

@section('content')

<div style="display:flex;gap:20px;">

{{-- Sidebar mensajes --}}
<div style="width:240px;flex-shrink:0;">
    <div class="card" style="margin-bottom:16px;">
        <div class="card-body" style="padding:16px;">
            <button type="button" onclick="abrirMensajeModal()" class="btn btn-primary" style="width:100%;justify-content:center;">
                <i class="fas fa-pen"></i> Nuevo Mensaje
            </button>
        </div>
    </div>
    <div class="card">
        <div style="padding:8px 0;">
            <a href="{{ route('mensajes.index') }}" class="menu-item active" style="border-left:3px solid #4f86bd;background:rgba(79,134,189,0.10);color:#18324d;padding:12px 20px;display:flex;align-items:center;gap:10px;text-decoration:none;font-size:13.5px;font-weight:600;">
                <i class="fas fa-inbox" style="width:18px;text-align:center;"></i> Recibidos
                @if($noLeidos > 0)
                    <span class="badge badge-danger" style="margin-left:auto;">{{ $noLeidos }}</span>
                @endif
            </a>
            <div style="padding:12px 20px;display:flex;align-items:center;gap:10px;color:var(--muted);font-size:13.5px;">
                <i class="fas fa-paper-plane" style="width:18px;text-align:center;"></i> Enviados
            </div>
        </div>
    </div>
</div>

{{-- Lista de mensajes --}}
<div style="flex:1;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Mensajes Recibidos</span>
            @if($noLeidos > 0)
                <span class="badge badge-danger">{{ $noLeidos }} sin leer</span>
            @endif
        </div>

        @forelse($recibidos as $msg)
            @php
                $mensajeData = [
                    'id' => $msg->id,
                    'show_url' => route('mensajes.show', $msg),
                    'archive_action' => route('mensajes.destroy', $msg),
                    'reply_user_id' => $msg->remitente_id,
                    'remitente_nombre' => $msg->remitente->name ?? '—',
                    'remitente_email' => $msg->remitente->email ?? '',
                    'destinatario_nombre' => auth()->user()->name,
                    'asunto' => $msg->asunto,
                    'cuerpo' => $msg->cuerpo,
                    'fecha' => $msg->created_at->format('d/m/Y H:i'),
                    'fecha_humana' => $msg->created_at->diffForHumans(),
                    'leido' => $msg->leido,
                    'leido_en' => $msg->leido_en?->format('d/m/Y H:i'),
                ];
            @endphp
            <button type="button"
               data-mensaje-action="ver"
               data-mensaje='@json($mensajeData, JSON_HEX_APOS | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT)'
               style="display:flex;align-items:flex-start;gap:14px;padding:16px 22px;border:0;border-bottom:1px solid var(--border);text-decoration:none;color:var(--text);transition:background .15s;{{ !$msg->leido ? 'background:#f0f7ff;' : 'background:#fff;' }}width:100%;text-align:left;cursor:pointer;font:inherit;">
                <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#18324d,#4f86bd);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:15px;flex-shrink:0;">
                    {{ strtoupper(substr($msg->remitente->name ?? 'U', 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                        <span style="font-weight:{{ $msg->leido ? '500' : '700' }};font-size:14px;">
                            {{ $msg->remitente->name ?? '—' }}
                        </span>
                        <span style="font-size:11px;color:var(--muted);white-space:nowrap;">
                            {{ $msg->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <div style="font-size:13.5px;font-weight:{{ $msg->leido ? '400' : '600' }};margin-bottom:3px;">
                        {{ $msg->asunto }}
                    </div>
                    <div style="font-size:12px;color:var(--muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ Str::limit(strip_tags($msg->cuerpo), 80) }}
                    </div>
                </div>
                @if(!$msg->leido)
                    <div style="width:8px;height:8px;background:#4f86bd;border-radius:50%;flex-shrink:0;margin-top:6px;"></div>
                @endif
            </button>
        @empty
            <div style="text-align:center;padding:64px;color:var(--muted);">
                <i class="fas fa-inbox" style="font-size:48px;opacity:.2;display:block;margin-bottom:16px;"></i>
                <p style="font-size:15px;">Tu bandeja está vacía</p>
            </div>
        @endforelse

        @if($recibidos->hasPages())
        <div style="padding:16px 22px;border-top:1px solid var(--border);">
            {{ $recibidos->links() }}
        </div>
        @endif
    </div>
</div>

</div>

<div id="modal-mensaje-detalle" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(760px,96vw);max-height:92vh;overflow:auto;padding:0;">
        <div class="card-header" style="position:sticky;top:0;z-index:2;">
            <div>
                <span id="mensaje-detalle-asunto" class="card-title"><i class="fas fa-envelope-open" style="color:var(--primary);"></i></span>
                <div id="mensaje-detalle-fecha" style="font-size:12px;color:var(--muted);margin-top:2px;"></div>
            </div>
            <button type="button" onclick="cerrarMensajeDetalleModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="card-body">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:18px;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div id="mensaje-detalle-avatar" style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#18324d,#4f86bd);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:16px;"></div>
                    <div>
                        <div id="mensaje-detalle-remitente" style="font-weight:800;"></div>
                        <div id="mensaje-detalle-email" style="font-size:12px;color:var(--muted);"></div>
                    </div>
                </div>
                <div id="mensaje-detalle-leido" style="font-size:12px;color:var(--muted);"></div>
            </div>
            <div id="mensaje-detalle-cuerpo" style="padding:18px;border:1px solid var(--border);border-radius:12px;background:#f7fafd;min-height:180px;font-size:14.5px;line-height:1.75;white-space:pre-wrap;"></div>
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end;padding:18px 22px;border-top:1px solid var(--border);background:#f7fafd;position:sticky;bottom:0;">
            <form id="form-mensaje-archivar" method="POST" action="">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-secondary"><i class="fas fa-archive"></i> Archivar</button>
            </form>
            <button type="button" id="mensaje-detalle-responder" class="btn btn-primary"><i class="fas fa-reply"></i> Responder</button>
        </div>
    </div>
</div>

<div id="modal-mensaje" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:18px;">
    <div style="width:min(720px,96vw);max-height:92vh;overflow:auto;padding:0;">
        <div class="card-header" style="position:sticky;top:0;z-index:2;">
            <div>
                <span class="card-title"><i class="fas fa-envelope" style="color:var(--primary);"></i> Nuevo Mensaje</span>
                <div style="font-size:12px;color:var(--muted);margin-top:2px;">Redacta y envía un mensaje interno.</div>
            </div>
            <button type="button" onclick="cerrarMensajeModal()" class="btn btn-secondary btn-icon" title="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('mensajes.store') }}">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Para *</label>
                    <select name="destinatario_id" id="mensaje-destinatario" class="form-control {{ isset($errors) && $errors->has('destinatario_id') ? 'is-invalid':'' }}" required>
                        <option value="">Seleccionar destinatario...</option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->id }}" {{ old('destinatario_id') == $u->id ? 'selected':'' }}>
                                {{ $u->name }} — {{ $u->role_label }}
                            </option>
                        @endforeach
                    </select>
                    @if(isset($errors) && $errors->has('destinatario_id')) <div class="invalid-feedback">{{ $errors->first('destinatario_id') }}</div> @endif
                </div>
                <div class="form-group">
                    <label class="form-label">Asunto *</label>
                    <input type="text" name="asunto" id="mensaje-asunto" class="form-control {{ isset($errors) && $errors->has('asunto') ? 'is-invalid':'' }}"
                        value="{{ old('asunto') }}" required placeholder="Escribe el asunto...">
                    @if(isset($errors) && $errors->has('asunto')) <div class="invalid-feedback">{{ $errors->first('asunto') }}</div> @endif
                </div>
                <div class="form-group">
                    <label class="form-label">Mensaje *</label>
                    <textarea name="cuerpo" id="mensaje-cuerpo" class="form-control {{ isset($errors) && $errors->has('cuerpo') ? 'is-invalid':'' }}"
                        rows="8" required placeholder="Escribe tu mensaje aquí...">{{ old('cuerpo') }}</textarea>
                    @if(isset($errors) && $errors->has('cuerpo')) <div class="invalid-feedback">{{ $errors->first('cuerpo') }}</div> @endif
                </div>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;padding:18px 22px;border-top:1px solid var(--border);background:#f7fafd;position:sticky;bottom:0;">
                <button type="button" onclick="cerrarMensajeModal()" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Enviar Mensaje</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const mensajeTieneErrores = @json(isset($errors) && $errors->any());

function abrirMensajeModal(data = null) {
    const modal = document.getElementById('modal-mensaje');
    if (!modal) return;

    if (data) {
        document.getElementById('mensaje-destinatario').value = data.destinatario_id || '';
        document.getElementById('mensaje-asunto').value = data.asunto || '';
        document.getElementById('mensaje-cuerpo').value = data.cuerpo || '';
    }

    modal.style.display = 'flex';
    setTimeout(() => document.getElementById('mensaje-destinatario')?.focus(), 60);
}

function cerrarMensajeModal() {
    document.getElementById('modal-mensaje').style.display = 'none';
}

function mensajeTexto(value) {
    return value || '—';
}

function mensajeDataDesdeBoton(button) {
    try {
        return JSON.parse(button.getAttribute('data-mensaje') || '{}');
    } catch (error) {
        console.error('No se pudo leer la información del mensaje.', error);
        return null;
    }
}

function abrirMensajeDetalleModal(data) {
    document.getElementById('mensaje-detalle-asunto').textContent = mensajeTexto(data?.asunto);
    document.getElementById('mensaje-detalle-fecha').textContent = data?.fecha ? data.fecha + ' · ' + mensajeTexto(data?.fecha_humana) : '';
    document.getElementById('mensaje-detalle-avatar').textContent = (data?.remitente_nombre || 'U').charAt(0).toUpperCase();
    document.getElementById('mensaje-detalle-remitente').textContent = mensajeTexto(data?.remitente_nombre);
    document.getElementById('mensaje-detalle-email').textContent = mensajeTexto(data?.remitente_email);
    document.getElementById('mensaje-detalle-cuerpo').textContent = mensajeTexto(data?.cuerpo);
    document.getElementById('mensaje-detalle-leido').textContent = data?.leido
        ? 'Leído' + (data?.leido_en ? ' el ' + data.leido_en : '')
        : 'No leído';
    document.getElementById('form-mensaje-archivar').action = data?.archive_action || '';
    document.getElementById('mensaje-detalle-responder').onclick = function () {
        cerrarMensajeDetalleModal();
        abrirMensajeModal({
            destinatario_id: data?.reply_user_id || '',
            asunto: data?.asunto ? 'Re: ' + data.asunto.replace(/^Re:\\s*/i, '') : '',
            cuerpo: ''
        });
    };
    document.getElementById('modal-mensaje-detalle').style.display = 'flex';

    if (data?.show_url) {
        fetch(data.show_url, { credentials: 'same-origin' }).catch(() => {});
    }
}

function cerrarMensajeDetalleModal() {
    document.getElementById('modal-mensaje-detalle').style.display = 'none';
}

document.addEventListener('click', function (event) {
    const button = event.target.closest('[data-mensaje-action]');
    if (!button) return;

    const data = mensajeDataDesdeBoton(button);
    if (!data) return;

    if (button.dataset.mensajeAction === 'ver') {
        abrirMensajeDetalleModal(data);
    }
});

if (mensajeTieneErrores) {
    abrirMensajeModal();
}
</script>
@endpush

