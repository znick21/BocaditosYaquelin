<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Productos (CRUD)
 * Fase:        4 - Módulos del Sistema
 * Descripción: CRUD de productos con búsqueda,
 *              filtro por categoría, subida de imagen
 *              y control de estado.
 * ═══════════════════════════════════════════════════
 */

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Listar productos con filtros.
     */
    public function index(Request $request)
    {
        $query = Producto::with('categoria');

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        $productos = $query->latest()->get();
        $categorias = Categoria::activas()->get();

        return view('productos.index', compact('productos', 'categorias'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $categorias = Categoria::activas()->get();
        return view('productos.crear', compact('categorias'));
    }

    /**
     * Guardar nuevo producto.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'precio' => 'required|numeric|min:0.01',
            'costo' => 'nullable|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'dias_duracion' => 'nullable|integer|min:1',
            'descripcion' => 'nullable|string|max:1000',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $datos = $request->only(
            'nombre', 'categoria_id', 'precio', 'costo',
            'stock_minimo', 'descripcion'
        );
        $datos['dias_duracion'] = $request->has('es_perecedero') ? $request->input('dias_duracion', 1) : 0;

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create($datos);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::activas()->get();
        return view('productos.editar', compact('producto', 'categorias'));
    }

    /**
     * Actualizar producto existente.
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'precio' => 'required|numeric|min:0.01',
            'costo' => 'nullable|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'dias_duracion' => 'nullable|integer|min:1',
            'descripcion' => 'nullable|string|max:1000',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $datos = $request->only(
            'nombre', 'categoria_id', 'precio', 'costo',
            'stock_minimo', 'descripcion'
        );
        $datos['dias_duracion'] = $request->has('es_perecedero') ? $request->input('dias_duracion', 1) : 0;

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($producto->imagen);
            }
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($datos);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Eliminar producto.
     */
    public function destroy(Producto $producto)
    {
        try {
            // No eliminamos la imagen físicamente en el soft delete para mantener el historial intacto
            $producto->is_active = false;
            $producto->save();
            $producto->delete();
            
            return redirect()->route('productos.index')
                ->with('success', 'Producto eliminado (archivado) exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('productos.index')
                ->with('error', 'Ocurrió un error al intentar eliminar el producto.');
        }
    }

    /**
     * Cambiar estado activo/inactivo.
     */
    public function cambiarEstado(Producto $producto)
    {
        $producto->update(['is_active' => !$producto->is_active]);
        return redirect()->route('productos.index')
            ->with('success', 'Estado del producto actualizado.');
    }
}
