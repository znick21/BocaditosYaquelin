<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Sliders (CRUD)
 * Descripción: Gestión del carrusel principal del
 *              Landing Page.
 * ═══════════════════════════════════════════════════
 */

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('orden')->get();
        return view('sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('sliders.crear');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'imagen' => 'required|image|max:3072',
            'orden' => 'required|integer|min:0',
        ]);

        $datos = $request->only('titulo', 'subtitulo', 'descripcion', 'orden');

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('sliders', 'public');
        }

        Slider::create($datos);

        return redirect()->route('sliders.index')->with('success', 'Slider creado exitosamente.');
    }

    public function edit(Slider $slider)
    {
        return view('sliders.editar', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'imagen' => 'nullable|image|max:3072',
            'orden' => 'required|integer|min:0',
        ]);

        $datos = $request->only('titulo', 'subtitulo', 'descripcion', 'orden');

        if ($request->hasFile('imagen')) {
            if ($slider->imagen && Storage::disk('public')->exists($slider->imagen)) {
                Storage::disk('public')->delete($slider->imagen);
            }
            $datos['imagen'] = $request->file('imagen')->store('sliders', 'public');
        }

        $slider->update($datos);

        return redirect()->route('sliders.index')->with('success', 'Slider actualizado exitosamente.');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->imagen && Storage::disk('public')->exists($slider->imagen)) {
            Storage::disk('public')->delete($slider->imagen);
        }
        $slider->delete();
        return redirect()->route('sliders.index')->with('success', 'Slider eliminado exitosamente.');
    }

    public function cambiarEstado(Slider $slider)
    {
        $slider->update(['is_active' => !$slider->is_active]);
        return redirect()->route('sliders.index')->with('success', 'Estado del slider actualizado.');
    }
}
