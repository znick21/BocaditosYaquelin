<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Métodos de Pago (3FN)
 * Fase:        1 - Base de Datos
 * Descripción: Modelo normalizado de métodos de pago.
 *              Reemplaza el ENUM para mayor flexibilidad.
 * ═══════════════════════════════════════════════════
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $table = 'metodos_pago';

    protected $fillable = [
        'nombre', 'descripcion', 'icono', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ── Relaciones ──

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'metodo_pago_id');
    }

    // ── Scopes ──

    public function scopeActivos(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('is_active', true);
    }
}
