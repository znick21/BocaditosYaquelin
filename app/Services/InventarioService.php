<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Inventario (Servicio)
 * Fase:        4 - Módulos del Sistema
 * Descripción: Control de stock, alertas de mínimo,
 *              verificación de disponibilidad y
 *              restauración de inventario.
 * ═══════════════════════════════════════════════════
 */

namespace App\Services;

use App\Models\Producto;

class InventarioService
{
    /**
     * Verificar si un producto tiene stock suficiente.
     */
    public function verificarDisponibilidad(int $productoId, int $cantidad): bool
    {
        $producto = Producto::find($productoId);
        return $producto && $producto->stock >= $cantidad;
    }

    /**
     * Descontar stock de un producto.
     */
    public function descontarStock(int $productoId, int $cantidad): void
    {
        $producto = Producto::findOrFail($productoId);

        if ($producto->stock < $cantidad) {
            throw new \Exception(
                "Stock insuficiente para '{$producto->nombre}'. Disponible: {$producto->stock}"
            );
        }

        $producto->decrement('stock', $cantidad);
    }

    /**
     * Restaurar stock (usado al anular ventas).
     */
    public function restaurarStock(int $productoId, int $cantidad): void
    {
        Producto::findOrFail($productoId)->increment('stock', $cantidad);
    }

    /**
     * Obtener productos con stock bajo (stock <= stock_minimo).
     */
    public function obtenerProductosStockBajo()
    {
        return Producto::with('categoria')
            ->where('is_active', true)
            ->whereColumn('stock', '<=', 'stock_minimo')
            ->orderBy('stock')
            ->get();
    }

    /**
     * Obtener productos agotados (stock = 0).
     */
    public function obtenerProductosAgotados()
    {
        return Producto::where('is_active', true)
            ->where('stock', '<=', 0)
            ->get();
    }
}
