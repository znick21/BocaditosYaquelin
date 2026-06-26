<?php

/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Ventas
 * Fase:        1 - Base de Datos
 * Descripción: Modelo de venta (transacción completa).
 *              Vincula cajero, caja y método de pago.
 *              Genera número correlativo YAQ-0001.
 * ═══════════════════════════════════════════════════
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'usuario_id',
        'caja_id',
        'metodo_pago_id',
        'numero_venta',
        'subtotal',
        'impuesto',
        'descuento',
        'total',
        'estado',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'impuesto' => 'decimal:2',
            'descuento' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    // ── Relaciones ──

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function caja()
    {
        return $this->belongsTo(Caja::class, 'caja_id');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }

    // ── Métodos de negocio ──

    public function estaAnulada(): bool
    {
        return $this->estado === 'anulada';
    }

    public static function generarNumeroVenta(): string
    {
        $ultima = static::latest('id')->first();
        $numero = $ultima ? intval(substr($ultima->numero_venta, 4)) + 1 : 1;
        return 'YAQ-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

    // ── Scopes ──

    public function scopeCompletadas(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('estado', 'completada');
    }

    public function scopeDelDia(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->whereDate('created_at', today());
    }
}
