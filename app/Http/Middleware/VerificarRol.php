<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Middleware de Roles
 * Fase:        2 - Seguridad y Acceso
 * Descripción: Verifica que el usuario autenticado
 *              tenga el rol requerido para acceder
 *              a la ruta. Ejemplo: 'rol:admin'
 * ═══════════════════════════════════════════════════
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
