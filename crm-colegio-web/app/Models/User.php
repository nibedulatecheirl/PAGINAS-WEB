<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'personal_id', 'alumno_id', 'avatar', 'activo',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'activo'            => 'boolean',
    ];

    public const ROLE_LABELS = [
        'admin'      => 'Administrador',
        'secretaria' => 'Secretaria',
        'docente'    => 'Docente',
        'contador'   => 'Contador',
        'estudiante' => 'Estudiante',
    ];

    public const MODULE_ROLES = [
        'dashboard'     => ['admin', 'secretaria', 'docente', 'contador', 'estudiante'],
        'grados'        => ['admin', 'secretaria', 'docente'],
        'materias'      => ['admin', 'secretaria', 'docente'],
        'notas'         => ['admin', 'secretaria', 'docente'],
        'alumnos'       => ['admin', 'secretaria', 'docente'],
        'matriculas'    => ['admin', 'secretaria'],
        'pagos'         => ['admin', 'secretaria', 'contador'],
        'personal'      => ['admin', 'secretaria'],
        'mensajes'      => ['admin', 'secretaria', 'docente', 'contador', 'estudiante'],
        'configuracion' => ['admin'],
        'sistema'       => ['admin'],
        'conceptos'     => ['admin', 'contador'],
        'reportes'      => ['admin', 'secretaria', 'contador'],
    ];

    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class, 'alumno_id');
    }

    public function hasAnyRole(array|string $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        return in_array($this->role, $roles, true);
    }

    public function canAccess(string $module): bool
    {
        return in_array($this->role, self::MODULE_ROLES[$module] ?? [], true);
    }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLE_LABELS[$this->role] ?? ucfirst($this->role);
    }

    public function mensajesEnviados(): HasMany
    {
        return $this->hasMany(Mensaje::class, 'remitente_id');
    }

    public function mensajesRecibidos(): HasMany
    {
        return $this->hasMany(Mensaje::class, 'destinatario_id');
    }

    public function mensajesNoLeidos(): int
    {
        return $this->mensajesRecibidos()->where('leido', false)->count();
    }
}
