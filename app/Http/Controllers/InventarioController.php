<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\InventarioMovimiento;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')->get();
        
        // Calcular sugerencias de producción basadas en los últimos 7 días
        $sugerencias = [];
        $hace7Dias = now()->subDays(7);
        
        foreach ($productos as $producto) {
            $ventas7Dias = InventarioMovimiento::where('producto_id', $producto->id)
                ->where('tipo', 'venta')
                ->where('created_at', '>=', $hace7Dias)
                ->sum('cantidad'); // ventas son negativas, usamos abs o sumamos valor absoluto si lo guardamos negativo. En venta lo guardamos como negativo? Depende de la implementación. Asumamos negativo.
                
            $mermas7Dias = InventarioMovimiento::where('producto_id', $producto->id)
                ->where('tipo', 'merma')
                ->where('created_at', '>=', $hace7Dias)
                ->sum('cantidad');
                
            // Convert to positive for calculations
            $promedioVentas = abs($ventas7Dias) / 7;
            $promedioMermas = abs($mermas7Dias) / 7;
            
            $sugerencias[$producto->id] = [
                'promedio_ventas' => round($promedioVentas, 1),
                'promedio_mermas' => round($promedioMermas, 1),
                'sugerencia_hoy' => ceil($promedioVentas) // Sugerimos producir lo que vendemos en promedio
            ];
        }

        return view('inventario.index', compact('productos', 'sugerencias'));
    }

    public function guardarPlanilla(Request $request)
    {
        $request->validate([
            'produccion' => 'array',
            'produccion.*' => 'nullable|integer|min:1',
            'merma' => 'array',
            'merma.*' => 'nullable|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $countProduccion = 0;
                $countMerma = 0;

                // Procesar Producción
                if ($request->has('produccion')) {
                    foreach ($request->produccion as $producto_id => $cantidad) {
                        if ($cantidad && $cantidad > 0) {
                            $producto = Producto::find($producto_id);
                            if ($producto) {
                                $producto->stock += $cantidad;
                                $producto->save();

                                InventarioMovimiento::create([
                                    'producto_id' => $producto->id,
                                    'tipo' => 'produccion',
                                    'cantidad' => $cantidad,
                                    'motivo' => 'Planilla Diaria (Producción)',
                                    'usuario_id' => auth()->id() ?? 1
                                ]);
                                $countProduccion++;
                            }
                        }
                    }
                }

                // Procesar Mermas
                if ($request->has('merma')) {
                    foreach ($request->merma as $producto_id => $cantidad) {
                        if ($cantidad && $cantidad > 0) {
                            $producto = Producto::find($producto_id);
                            if ($producto) {
                                if ($producto->stock < $cantidad) {
                                    throw new \Exception("No puedes mermar más stock del que existe para el producto: {$producto->nombre}");
                                }

                                $producto->stock -= $cantidad;
                                $producto->save();

                                InventarioMovimiento::create([
                                    'producto_id' => $producto->id,
                                    'tipo' => 'merma',
                                    'cantidad' => -$cantidad,
                                    'motivo' => 'Planilla Diaria (Merma)',
                                    'usuario_id' => auth()->id() ?? 1
                                ]);
                                $countMerma++;
                            }
                        }
                    }
                }

                if ($countProduccion == 0 && $countMerma == 0) {
                    throw new \Exception("No se ingresó ninguna cantidad en la planilla.");
                }
            });

            return redirect()->back()->with('success', 'Planilla de inventario procesada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function historial(Request $request)
    {
        $query = InventarioMovimiento::with(['producto', 'usuario'])->latest();

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('producto_id')) {
            $query->where('producto_id', $request->producto_id);
        }

        $movimientos = $query->paginate(20)->withQueryString();
        $productos = Producto::orderBy('nombre')->get();

        return view('inventario.historial', compact('movimientos', 'productos'));
    }
}
