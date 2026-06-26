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
}
