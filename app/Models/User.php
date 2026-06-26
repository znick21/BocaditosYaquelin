<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Usuarios (Nativo Laravel - EN INGLÉS)
 * Fase:        1 - Base de Datos
 * Descripción: Modelo de usuario. Se mantiene en
 *              inglés por ser parte del core de
 *              Laravel Auth para evitar conflictos.
 *              Los campos personalizados (telefono,
 *              direccion, etc.) sí están en español.
 * ═══════════════════════════════════════════════════
 */

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'telefono', 'direccion', 'foto',
        'is_active', 'ultimo_acceso',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'ultimo_acceso' => 'datetime',
        ];
    }

    // ── Role helpers (inglés - nativo Laravel) ──

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCajero(): bool
    {
        return $this->role === 'cajero';
    }

    // ── Relationships (inglés - nativo Laravel) ──

    public function sales()
    {
        return $this->hasMany(Venta::class, 'usuario_id');
    }

    public function cashRegisters()
    {
        return $this->hasMany(Caja::class, 'usuario_id');
    }
}
