<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Ventas (Servicio)
 * Fase:        4 - Módulos del Sistema
 * Descripción: Lógica transaccional de ventas.
 *              Implementa bloqueo pesimista (FOR UPDATE)
 *              para evitar venta de productos agotados
 *              cuando múltiples cajeros operan al
 *              mismo tiempo (concurrencia).
 * ═══════════════════════════════════════════════════
 */

namespace App\Services;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Caja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaService
{
    /**
     * Procesar una nueva venta con bloqueo pesimista.
     *
     * @param array $items       Array de ['producto_id' => int, 'cantidad' => int]
     * @param int   $metodoPagoId  ID del método de pago (FK normalizada)
     * @param float $descuento   Descuento a aplicar en Bs
     * @return Venta
     * @throws \Exception Si no hay stock suficiente o no hay caja abierta
     */
    public function procesarVenta(array $items, int $metodoPagoId, float $descuento = 0): Venta
    {
        // ── Verificar caja abierta ──
        $caja = $this->obtenerCajaAbierta();
        if (!$caja) {
            throw new \Exception('Debes abrir una caja antes de registrar ventas.');
        }

        return DB::transaction(function () use ($items, $metodoPagoId, $descuento, $caja) {
            $subtotal = 0;
            $detalles = [];

            foreach ($items as $item) {
                // ── Bloqueo pesimista: evita concurrencia ──
                $producto = Producto::lockForUpdate()->find($item['producto_id']);

                if (!$producto || $producto->stock < $item['cantidad']) {
                    throw new \Exception(
                        "Stock insuficiente para '{$producto->nombre}'. Disponible: {$producto->stock}"
                    );
                }

                $itemSubtotal = $producto->precio * $item['cantidad'];
                $subtotal += $itemSubtotal;

                // ── Preparar detalle (snapshot) ──
                $detalles[] = [
                    'producto_id' => $producto->id,
                    'nombre_producto' => $producto->nombre,
                    'precio_unitario' => $producto->precio,
                    'cantidad' => $item['cantidad'],
                    'subtotal' => $itemSubtotal,
                ];

                // ── Descontar stock ──
                $producto->decrement('stock', $item['cantidad']);
            }

            $total = $subtotal - $descuento;

            // ── Crear la venta ──
            $venta = Venta::create([
                'usuario_id' => Auth::id(),
                'caja_id' => $caja->id,
                'metodo_pago_id' => $metodoPagoId,
                'numero_venta' => Venta::generarNumeroVenta(),
                'subtotal' => $subtotal,
                'impuesto' => 0,
                'descuento' => $descuento,
                'total' => $total,
                'estado' => 'completada',
            ]);

            // ── Crear los detalles de la venta y movimientos de inventario ──
            foreach ($detalles as $detalle) {
                $venta->detalles()->create($detalle);

                \App\Models\InventarioMovimiento::create([
                    'producto_id' => $detalle['producto_id'],
                    'tipo' => 'venta',
                    'cantidad' => -$detalle['cantidad'],
                    'motivo' => 'Venta #' . $venta->numero_venta,
                    'usuario_id' => Auth::id()
                ]);
            }

            return $venta->load('detalles');
        });
    }

    /**
     * Anular una venta y restaurar el stock.
     */
    public function anularVenta(Venta $venta): Venta
    {
        if ($venta->estaAnulada()) {
            throw new \Exception('Esta venta ya está anulada.');
        }

        return DB::transaction(function () use ($venta) {
            // ── Restaurar stock de cada producto y registrar movimiento ──
            foreach ($venta->detalles as $detalle) {
                $detalle->producto->increment('stock', $detalle->cantidad);

                \App\Models\InventarioMovimiento::create([
                    'producto_id' => $detalle->producto_id,
                    'tipo' => 'ajuste',
                    'cantidad' => $detalle->cantidad,
                    'motivo' => 'Anulación Venta #' . $venta->numero_venta,
                    'usuario_id' => Auth::id()
                ]);
            }

            $venta->update([
                'estado' => 'anulada',
                'observaciones' => 'Anulada por ' . Auth::user()->name
                    . ' el ' . now()->format('d/m/Y H:i'),
            ]);

            return $venta->fresh();
        });
    }

    /**
     * Obtener la caja abierta del usuario actual.
     */
    private function obtenerCajaAbierta(): ?Caja
    {
        return Caja::where('usuario_id', Auth::id())
            ->where('estado', 'abierta')
            ->first();
    }
}
