<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Punto de Venta (POS)
 * Fase:        4 - Módulos del Sistema (CRÍTICO)
 * Descripción: Interfaz principal de ventas. Muestra
 *              productos disponibles y procesa ventas
 *              usando VentaService con transacciones
 *              y bloqueo pesimista.
 * ═══════════════════════════════════════════════════
 */

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\MetodoPago;
use App\Models\Caja;
use App\Services\VentaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PuntoVentaController extends Controller
{
    public function __construct(
        private VentaService $ventaService,
    ) {}

    /**
     * Mostrar la interfaz del POS.
     */
    public function index()
    {
        $categorias = Categoria::activas()->get();
        $productos = Producto::with('categoria')
            ->activos()
            ->conStock()
            ->get();
        $metodosPago = MetodoPago::activos()->get();

        // ── Verificar caja abierta del cajero ──
        $cajaAbierta = Caja::where('usuario_id', Auth::id())
            ->where('estado', 'abierta')
            ->first();

        $productosCat = $productos->map(function($p) {
            return [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'precio' => (float) $p->precio,
                'stock' => $p->stock,
                'stock_minimo' => $p->stock_minimo,
                'categoria_id' => $p->categoria_id,
                'categoria_nombre' => $p->categoria->nombre
            ];
        });

        return view('punto-venta.index', compact(
            'categorias', 'productos', 'productosCat', 'metodosPago', 'cajaAbierta'
        ));
    }

    /**
     * Registrar una nueva venta (AJAX con Axios).
     * Retorna JSON para Alpine.js.
     */
    public function registrarVenta(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'metodo_pago_id' => 'required|exists:metodos_pago,id',
            'descuento' => 'nullable|numeric|min:0',
        ]);

        try {
            $venta = $this->ventaService->procesarVenta(
                $request->items,
                $request->metodo_pago_id,
                $request->input('descuento', 0)
            );

            return response()->json([
                'exito' => true,
                'mensaje' => "Venta {$venta->numero_venta} registrada exitosamente.",
                'venta' => $venta->load('detalles', 'metodoPago'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'exito' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
