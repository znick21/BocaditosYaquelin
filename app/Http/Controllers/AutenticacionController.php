<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Autenticación
 * Fase:        2 - Seguridad y Acceso
 * Descripción: Controlador de login/logout.
 *              Maneja el acceso al sistema y registra
 *              el último acceso del usuario.
 * ═══════════════════════════════════════════════════
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutenticacionController extends Controller
{
    /**
     * Mostrar formulario de login.
     */
    public function mostrarLogin()
    {
        if (Auth::check()) {
            return redirect()->route('panel');
        }
        return view('autenticacion.login');
    }

    /**
     * Procesar el inicio de sesión.
     */
    public function iniciarSesion(Request $request)
    {
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credenciales, $request->boolean('recordar'))) {
            $request->session()->regenerate();

            // ── Registrar último acceso ──
            \App\Models\User::where('id', \Illuminate\Support\Facades\Auth::id())->update(['ultimo_acceso' => now()]);

            return redirect()->intended(route('panel'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cerrar sesión.
     */
    public function cerrarSesion(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
