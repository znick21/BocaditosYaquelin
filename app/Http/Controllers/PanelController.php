<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Panel de Control (Dashboard)
 * Fase:        4 - Módulos del Sistema
 * Descripción: Muestra KPIs del día: ventas totales,
 *              productos vendidos, transacciones,
 *              alertas de stock bajo y gráfico semanal.
 * ═══════════════════════════════════════════════════
 */

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Services\ReporteService;
use App\Services\InventarioService;

class PanelController extends Controller
{
    public function __construct(
        private ReporteService $reporteService,
        private InventarioService $inventarioService,
    ) {}

    /**
     * Mostrar el panel principal con KPIs del día.
     */
    public function index()
    {
        // ── KPIs del día ──
        $ventasHoy = Venta::delDia()->completadas()->sum('total');

        $productosVendidos = Venta::delDia()->completadas()
            ->withCount('detalles')
            ->get()
            ->sum('detalles_count');

        $transaccionesHoy = Venta::delDia()->completadas()->count();

        $stockBajo = Producto::where('is_active', true)
            ->whereColumn('stock', '<=', 'stock_minimo')
            ->count();

        // ── Últimas ventas ──
        $ultimasVentas = Venta::with('usuario', 'detalles', 'metodoPago')
            ->completadas()
            ->latest()
            ->take(5)
            ->get();

        // ── Productos con stock bajo ──
        $productosStockBajo = $this->inventarioService->obtenerProductosStockBajo()->take(5);

        // ── Gráfico de ventas semanales ──
        $ventasSemana = $this->reporteService->obtenerVentasPorPeriodo('semana');
        $maxVentaSemana = max(array_column($ventasSemana, 'total')) ?: 1;

        return view('panel', compact(
            'ventasHoy', 'productosVendidos', 'transaccionesHoy',
            'stockBajo', 'ultimasVentas', 'productosStockBajo',
            'ventasSemana', 'maxVentaSemana'
        ));
    }
}
