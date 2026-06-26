<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Menús de Navegación
 * Fase:        1 - Base de Datos
 * Descripción: Modelo de menú de navegación con
 *              soporte para submenús (autorreferencial).
 * ═══════════════════════════════════════════════════
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = [
        'nombre', 'enlace', 'icono',
        'ubicacion', 'padre_id', 'orden', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ── Relaciones ──

    public function padre()
    {
        return $this->belongsTo(Menu::class, 'padre_id');
    }

    public function hijos()
    {
        return $this->hasMany(Menu::class, 'padre_id')->orderBy('orden');
    }

    // ── Scopes ──

    public function scopePrincipales(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->whereNull('padre_id')
            ->where('is_active', true)
            ->orderBy('orden');
    }

    public function scopePorUbicacion(\Illuminate\Database\Eloquent\Builder $query, string $ubicacion)
    {
        return $query->where('ubicacion', $ubicacion)
            ->where('is_active', true)
            ->orderBy('orden');
    }
}
