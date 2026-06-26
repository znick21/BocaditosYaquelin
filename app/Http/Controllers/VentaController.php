<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Historial de Ventas
 * Fase:        4 - Módulos del Sistema
 * Descripción: Listado y detalle de ventas realizadas.
 *              Permite anular ventas (solo admin)
 *              restaurando el stock automáticamente.
 * ═══════════════════════════════════════════════════
 */

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Services\VentaService;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function __construct(
        private VentaService $ventaService,
    ) {}

    /**
     * Listar historial de ventas con filtros.
     */
    public function index(Request $request)
    {
        $query = Venta::with('usuario', 'detalles', 'metodoPago');

        if ($request->filled('fecha')) {
            $query->whereDate('created_at', $request->fecha);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }

        $ventas = $query->latest()->paginate(15);

        return view('ventas.index', compact('ventas'));
    }

    /**
     * Mostrar detalle de una venta.
     */
    public function detalle(Venta $venta)
    {
        $venta->load('usuario', 'detalles.producto', 'caja', 'metodoPago');
        return view('ventas.detalle', compact('venta'));
    }

    /**
     * Anular una venta (solo admin).
     */
    public function anular(Venta $venta)
    {
        try {
            $this->ventaService->anularVenta($venta);
            return back()->with('success', "Venta {$venta->numero_venta} anulada. Stock restaurado.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
