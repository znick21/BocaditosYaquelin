<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Detalle de Ventas
 * Fase:        1 - Base de Datos
 * Descripción: Líneas individuales de cada venta.
 *              Almacena snapshot del producto para
 *              integridad histórica de auditoría.
 * ═══════════════════════════════════════════════════
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_ventas';

    protected $fillable = [
        'venta_id', 'producto_id',
        'nombre_producto', 'precio_unitario',
        'cantidad', 'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'precio_unitario' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    // ── Relaciones ──

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
