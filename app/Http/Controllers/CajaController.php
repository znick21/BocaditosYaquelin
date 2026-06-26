<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Caja
 * Fase:        4 - Módulos del Sistema
 * Descripción: Controlador de apertura/cierre de caja.
 *              Usa CajaService para calcular montos
 *              esperados y diferencias de auditoría.
 * ═══════════════════════════════════════════════════
 */

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Services\CajaService;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function __construct(
        private CajaService $cajaService,
    ) {}

    /**
     * Mostrar panel de caja.
     */
    public function index()
    {
        $cajaAbierta = $this->cajaService->obtenerCajaAbierta(\Illuminate\Support\Facades\Auth::id());

        $historial = Caja::with('usuario')
            ->latest()
            ->paginate(10);

        $ventasCaja = null;
        if ($cajaAbierta) {
            $ventasCaja = $cajaAbierta->ventas()
                ->with('detalles', 'metodoPago')
                ->where('estado', 'completada')
                ->get();
        }

        return view('caja.index', compact('cajaAbierta', 'historial', 'ventasCaja'));
    }

    /**
     * Abrir caja nueva.
     */
    public function abrir(Request $request)
    {
        $request->validate([
            'monto_apertura' => 'required|numeric|min:0',
        ]);

        try {
            $this->cajaService->abrirCaja($request->monto_apertura);
            return back()->with('success', 'Caja abierta exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cerrar caja calculando diferencias.
     */
    public function cerrar(Request $request, Caja $caja)
    {
        $request->validate([
            'monto_cierre' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $this->cajaService->cerrarCaja(
            $caja,
            $request->monto_cierre,
            $request->observaciones
        );

        return back()->with('success', 'Caja cerrada exitosamente.');
    }
}
