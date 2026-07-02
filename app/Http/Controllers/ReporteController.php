<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Reportes
 * Fase:        4 - Módulos del Sistema
 * Descripción: Panel de reportes con gráficos de
 *              ventas, top productos, métodos de pago
 *              y rendimiento de cajeros.
 * ═══════════════════════════════════════════════════
 */

namespace App\Http\Controllers;

use App\Services\ReporteService;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function __construct(
        private ReporteService $reporteService,
    ) {}

    /**
     * Mostrar panel de reportes.
     */
    public function index(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->format('Y-m-d'));

        // Obtener resumen (esto es asumiendo que ReporteService fue implementado diferente,
        // pero lo recalcularemos aquí para que coincida con la vista)
        $ventasPeriodo = \App\Models\Venta::whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
                                         ->where('estado', 'completada')
                                         ->get();

        $resumen = [
            'ingresos' => $ventasPeriodo->sum('total'),
            'total_ventas' => $ventasPeriodo->count(),
            'ticket_promedio' => $ventasPeriodo->count() > 0 ? $ventasPeriodo->sum('total') / $ventasPeriodo->count() : 0,
            'productos_vendidos' => \App\Models\DetalleVenta::whereIn('venta_id', $ventasPeriodo->pluck('id'))->sum('cantidad')
        ];

        $topProductos = \App\Models\DetalleVenta::selectRaw('nombre_producto, sum(cantidad) as total_vendido, sum(subtotal) as ingresos')
                            ->whereIn('venta_id', $ventasPeriodo->pluck('id'))
                            ->groupBy('nombre_producto')
                            ->orderByDesc('total_vendido')
                            ->limit(5)
                            ->get();

        $ventasPorMetodo = \App\Models\Venta::selectRaw('metodo_pago_id, sum(total) as ingresos, count(*) as cantidad')
                            ->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
                            ->where('estado', 'completada')
                            ->groupBy('metodo_pago_id')
                            ->with('metodoPago')
                            ->get()
                            ->map(function ($venta) {
                                return (object) [
                                    'nombre' => $venta->metodoPago->nombre,
                                    'ingresos' => $venta->ingresos,
                                    'cantidad' => $venta->cantidad
                                ];
                            });

        return view('reportes.index', compact(
            'fechaInicio', 'fechaFin', 'resumen', 'topProductos', 'ventasPorMetodo'
        ));
    }

    /**
     * Mostrar reporte de inventario.
     */
    public function inventario()
    {
        $productos = \App\Models\Producto::where('is_active', true)->get();

        $valorCosto = 0;
        $valorPrecio = 0;
        $stockCritico = 0;

        foreach ($productos as $producto) {
            $valorCosto += $producto->stock * $producto->costo;
            $valorPrecio += $producto->stock * $producto->precio;
            
            if ($producto->stock <= $producto->stock_minimo) {
                $stockCritico++;
            }
        }

        $productosCriticos = \App\Models\Producto::where('is_active', true)
                                ->whereColumn('stock', '<=', 'stock_minimo')
                                ->orderBy('stock')
                                ->get();

        $mermasRecientes = \App\Models\InventarioMovimiento::where('tipo', 'merma')
                                ->with(['producto', 'usuario'])
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();

        return view('reportes.inventario', compact(
            'valorCosto', 'valorPrecio', 'stockCritico', 'productosCriticos', 'mermasRecientes'
        ));
    }

    /**
     * Mostrar reporte de cajas.
     */
    public function cajas(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->format('Y-m-d'));

        $cajas = \App\Models\Caja::whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
                                ->with('usuario')
                                ->orderBy('created_at', 'desc')
                                ->get();

        $resumen = [
            'total_recaudado' => $cajas->sum('monto_cierre'),
            'total_faltantes' => $cajas->where('diferencia', '<', 0)->sum('diferencia'),
            'total_sobrantes' => $cajas->where('diferencia', '>', 0)->sum('diferencia'),
            'cantidad_cajas' => $cajas->count()
        ];

        return view('reportes.cajas', compact('fechaInicio', 'fechaFin', 'cajas', 'resumen'));
    }
}
