<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Reportes (Servicio)
 * Fase:        4 - Módulos del Sistema
 * Descripción: Consultas agregadas para el módulo
 *              de reportes. Ventas por período, top
 *              productos, ingresos por método de pago
 *              y rendimiento de cajeros.
 * ═══════════════════════════════════════════════════
 */

namespace App\Services;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteService
{
    /**
     * Obtener ventas agrupadas por día en un período.
     */
    public function obtenerVentasPorPeriodo(string $periodo = 'semana'): array
    {
        $dias = match ($periodo) {
            'semana' => 7,
            'mes' => 30,
            default => 7,
        };

        $ventas = [];
        for ($i = $dias - 1; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i);
            $ventas[] = [
                'dia' => $fecha->isoFormat('ddd'),
                'fecha' => $fecha->format('d/m'),
                'total' => (float) Venta::whereDate('created_at', $fecha)
                    ->completadas()
                    ->sum('total'),
            ];
        }

        return $ventas;
    }

    /**
     * Obtener los productos más vendidos.
     */
    public function obtenerTopProductos(int $limite = 5)
    {
        return DetalleVenta::select(
                'nombre_producto',
                DB::raw('SUM(cantidad) as total_cantidad'),
                DB::raw('SUM(subtotal) as total_monto')
            )
            ->whereHas('venta', fn($q) => $q->completadas())
            ->groupBy('nombre_producto')
            ->orderByDesc('total_cantidad')
            ->take($limite)
            ->get();
    }

    /**
     * Obtener ingresos agrupados por método de pago.
     */
    public function obtenerIngresosPorMetodoPago()
    {
        return Venta::select(
                'metodo_pago_id',
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(*) as cantidad')
            )
            ->completadas()
            ->groupBy('metodo_pago_id')
            ->with('metodoPago')
            ->get();
    }

    /**
     * Obtener rendimiento de cada cajero.
     */
    public function obtenerRendimientoCajeros()
    {
        return User::select(
                'users.id', 'users.name',
                DB::raw('COUNT(ventas.id) as total_ventas'),
                DB::raw('COALESCE(SUM(ventas.total), 0) as total_monto')
            )
            ->leftJoin('ventas', function ($join) {
                $join->on('users.id', '=', 'ventas.usuario_id')
                    ->where('ventas.estado', '=', 'completada');
            })
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_monto')
            ->get();
    }
}
