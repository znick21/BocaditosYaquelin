<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Configuración del Sistema
 * Descripción: Controlador para editar los datos
 *              generales del negocio, colores, redes.
 * ═══════════════════════════════════════════════════
 */

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    /**
     * Muestra el formulario de configuración.
     */
    public function index()
    {
        $config = Configuracion::obtener();
        return view('configuracion.index', compact('config'));
    }

    /**
     * Actualiza la configuración en la base de datos.
     */
    public function update(Request $request)
    {
        $config = Configuracion::obtener();

        $request->validate([
            'nombre_negocio' => 'required|string|max:255',
            'eslogan' => 'nullable|string|max:255',
            'codigo_pais' => 'required|string|max:10',
            'telefono' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
            'moneda' => 'required|string|max:10',
            'impuesto_porcentaje' => 'required|numeric|min:0|max:100',
            'horario_apertura' => 'nullable|string|max:50',
            'horario_cierre' => 'nullable|string|max:50',
            'color_primario' => 'required|string|max:7',
            'color_secundario' => 'required|string|max:7',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            'redes_sociales' => 'nullable|array',
            'redes_sociales.*.red' => 'required|string|max:50',
            'redes_sociales.*.url' => 'required|url|max:500',
        ]);

        $data = $request->except(['logo', 'favicon']);

        // Procesar las redes sociales para limpiar vacíos si es necesario
        if ($request->has('redes_sociales')) {
            $data['redes_sociales'] = array_values(array_filter($request->redes_sociales, function ($red) {
                return !empty($red['url']);
            }));
        } else {
            $data['redes_sociales'] = [];
        }

        if ($request->hasFile('logo')) {
            if ($config->logo && Storage::disk('public')->exists($config->logo)) {
                Storage::disk('public')->delete($config->logo);
            }
            $data['logo'] = $request->file('logo')->store('configuracion', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($config->favicon && Storage::disk('public')->exists($config->favicon)) {
                Storage::disk('public')->delete($config->favicon);
            }
            $data['favicon'] = $request->file('favicon')->store('configuracion', 'public');
        }

        $config->update($data);

        return redirect()->route('configuracion.index')->with('success', 'Configuración actualizada exitosamente.');
    }
}
