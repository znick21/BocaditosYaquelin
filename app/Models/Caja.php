<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Caja
 * Fase:        1 - Base de Datos
 * Descripción: Modelo de caja (apertura/cierre de turno).
 *              Registra montos y calcula diferencias
 *              para auditoría financiera.
 * ═══════════════════════════════════════════════════
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = 'cajas';

    protected $fillable = [
        'usuario_id', 'monto_apertura', 'monto_cierre',
        'monto_esperado', 'diferencia', 'estado',
        'observaciones', 'fecha_apertura', 'fecha_cierre',
    ];

    protected function casts(): array
    {
        return [
            'monto_apertura' => 'decimal:2',
            'monto_cierre' => 'decimal:2',
            'monto_esperado' => 'decimal:2',
            'diferencia' => 'decimal:2',
            'fecha_apertura' => 'datetime',
            'fecha_cierre' => 'datetime',
        ];
    }

    // ── Relaciones ──

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'caja_id');
    }

    // ── Métodos de negocio ──

    public function estaAbierta(): bool
    {
        return $this->estado === 'abierta';
    }

    public function totalVentasEfectivo(): float
    {
        return (float) $this->ventas()
            ->where('estado', 'completada')
            ->whereHas('metodoPago', fn($q) => $q->where('nombre', 'Efectivo'))
            ->sum('total');
    }
}
