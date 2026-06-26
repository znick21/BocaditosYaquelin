<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Categorías (CRUD)
 * Fase:        4 - Módulos del Sistema
 * Descripción: CRUD completo de categorías para
 *              clasificar los productos del menú.
 *              Solo accesible por administradores.
 * ═══════════════════════════════════════════════════
 */

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Listar todas las categorías.
     */
    public function index()
    {
        $categorias = Categoria::withCount('productos')->latest()->get();
        return view('categorias.index', compact('categorias'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('categorias.crear');
    }

    /**
     * Guardar nueva categoría.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias',
            'descripcion' => 'nullable|string|max:500',
            'icono' => 'nullable|string|max:100',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $datos = $request->only('nombre', 'descripcion', 'icono');

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('categorias', 'public');
        }

        Categoria::create($datos);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.editar', compact('categoria'));
    }

    /**
     * Actualizar categoría existente.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string|max:500',
            'icono' => 'nullable|string|max:100',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $datos = $request->only('nombre', 'descripcion', 'icono');

        if ($request->hasFile('imagen')) {
            if ($categoria->imagen) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($categoria->imagen);
            }
            $datos['imagen'] = $request->file('imagen')->store('categorias', 'public');
        }

        $categoria->update($datos);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Eliminar categoría.
     */
    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->exists()) {
            return redirect()->route('categorias.index')
                ->with('error', 'No puedes eliminar esta categoría porque tiene productos asignados. Mueve o elimina los productos primero.');
        }

        try {
            $categoria->is_active = false;
            $categoria->save();
            $categoria->delete();
            return redirect()->route('categorias.index')
                ->with('success', 'Categoría eliminada (archivada) exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('categorias.index')
                ->with('error', 'Ocurrió un error al intentar eliminar la categoría.');
        }
    }

    /**
     * Cambiar estado activo/inactivo.
     */
    public function cambiarEstado(Categoria $categoria)
    {
        $categoria->update(['is_active' => !$categoria->is_active]);
        return redirect()->route('categorias.index')
            ->with('success', 'Estado de categoría actualizado.');
    }
}
