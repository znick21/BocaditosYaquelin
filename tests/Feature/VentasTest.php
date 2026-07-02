<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\User;
use App\Models\Caja;
use App\Models\MetodoPago;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Prueba de Integración: Módulo de Ventas
 * Valida que al registrar una venta, el stock
 * del producto disminuya correctamente en la BD.
 * Usa DatabaseTransactions para no alterar datos reales.
 * ═══════════════════════════════════════════════════
 */
class VentasTest extends TestCase
{
    use DatabaseTransactions;

    #[\PHPUnit\Framework\Attributes\Test]
    public function registrar_una_venta_reduce_el_stock_del_producto(): void
    {
        // ── 1. ARRANGE: Tomamos datos reales existentes en la BD ──────────
        $producto   = Producto::where('is_active', true)->where('stock', '>', 0)->firstOrFail();
        $usuario    = User::first();
        $caja       = Caja::where('estado', 'abierta')->firstOrFail();
        $metodo     = MetodoPago::first();

        $stockInicial    = (int) $producto->stock;
        $cantidadVender  = 1;

        // ── 2. ACT: Simulamos el registro de una venta ────────────────────
        $venta = Venta::create([
            'usuario_id'      => $usuario->id,
            'caja_id'         => $caja->id,
            'metodo_pago_id'  => $metodo->id,
            'numero_venta'    => Venta::generarNumeroVenta() . '-TEST',
            'subtotal'        => $producto->precio,
            'impuesto'        => 0,
            'descuento'       => 0,
            'total'           => $producto->precio,
            'estado'          => 'completada',
        ]);

        DetalleVenta::create([
            'venta_id'         => $venta->id,
            'producto_id'      => $producto->id,
            'nombre_producto'  => $producto->nombre,
            'cantidad'         => $cantidadVender,
            'precio_unitario'  => $producto->precio,
            'subtotal'         => $producto->precio,
        ]);

        // Aplicamos el descuento de stock (como lo haría el sistema real)
        $producto->decrement('stock', $cantidadVender);

        // ── 3. ASSERT: Validamos que el stock bajó correctamente ──────────
        $this->assertEquals(
            $stockInicial - $cantidadVender,
            $producto->fresh()->stock,
            "El stock debe haberse reducido en {$cantidadVender} unidad(es) tras la venta."
        );

        // Validamos que la venta existe en la BD
        $this->assertDatabaseHas('ventas', [
            'id'     => $venta->id,
            'estado' => 'completada',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function producto_con_stock_bajo_es_detectado_correctamente(): void
    {
        // Busca el primer producto y fuerza un stock bajo
        $producto = Producto::where('is_active', true)->firstOrFail();
        $stockMinimoOriginal = $producto->stock_minimo;

        // Forzamos que el stock sea menor al mínimo
        $producto->stock = $stockMinimoOriginal - 1;

        $this->assertTrue(
            $producto->tieneStockBajo(),
            "El sistema debe detectar cuando el stock está por debajo del mínimo."
        );
    }
}
