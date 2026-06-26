<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Landing Page / Catálogo Público
 * Fase:        4 - Módulos del Sistema
 * Descripción: Controlador para la página pública
 *              del negocio. Muestra sliders, menú
 *              de navegación, catálogo de productos
 *              y datos de contacto.
 * ═══════════════════════════════════════════════════
 */

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Slider;
use App\Models\Menu;
use App\Models\Categoria;
use App\Models\Producto;

class LandingController extends Controller
{
    /**
     * Mostrar la landing page pública.
     */
    public function index()
    {
        $config = Configuracion::obtener();
        $sliders = Slider::activos()->get();
        $menuItems = Menu::principales()->where('ubicacion', 'principal')->with('hijos')->get();

        $categorias = Categoria::activas()
            ->withCount(['productosActivos'])
            ->get();

        $productosDestacados = Producto::paraCatalogo()
            ->with('categoria')
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('landing', compact(
            'config', 'sliders', 'menuItems',
            'categorias', 'productosDestacados'
        ));
    }

    /**
     * Mostrar catálogo completo filtrado por categoría.
     */
    public function catalogo(?int $categoriaId = null)
    {
        $config = Configuracion::obtener();
        $menuItems = Menu::principales()->where('ubicacion', 'principal')->with('hijos')->get();
        $categorias = Categoria::activas()->get();

        $query = Producto::paraCatalogo()->with('categoria');

        if ($categoriaId) {
            $query->where('categoria_id', $categoriaId);
        }

        $productos = $query->orderBy('nombre')->get();
        $categoriaActual = $categoriaId ? Categoria::find($categoriaId) : null;

        $topNombres = \App\Models\DetalleVenta::selectRaw('nombre_producto, sum(cantidad) as total_vendido')
            ->groupBy('nombre_producto')
            ->orderByDesc('total_vendido')
            ->limit(6)
            ->pluck('nombre_producto');

        $productosCarrusel = Producto::paraCatalogo()->whereIn('nombre', $topNombres)->get();
        if ($productosCarrusel->isEmpty()) {
            $productosCarrusel = Producto::paraCatalogo()->inRandomOrder()->take(6)->get();
        }

        return view('catalogo', compact(
            'config', 'menuItems', 'categorias',
            'productos', 'categoriaActual', 'productosCarrusel'
        ));
    }
}
