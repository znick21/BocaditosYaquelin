<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Caja (Servicio)
 * Fase:        4 - Módulos del Sistema
 * Descripción: Lógica de apertura y cierre de caja.
 *              Calcula montos esperados y diferencias
 *              para auditoría financiera.
 * ═══════════════════════════════════════════════════
 */

namespace App\Services;

use App\Models\Caja;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CajaService
{
    /**
     * Abrir una nueva caja para el usuario actual.
     */
    public function abrirCaja(float $montoInicial): Caja
    {
        // ── Verificar que no tenga caja abierta ──
        $existente = $this->obtenerCajaAbierta(Auth::id());
        if ($existente) {
            throw new \Exception('Ya tienes una caja abierta.');
        }

        return Caja::create([
            'usuario_id' => Auth::id(),
            'monto_apertura' => $montoInicial,
            'estado' => 'abierta',
            'fecha_apertura' => Carbon::now(),
        ]);
    }

    /**
     * Cerrar una caja calculando montos esperados y diferencia.
     */
    public function cerrarCaja(Caja $caja, float $montoReal, ?string $observaciones = null): Caja
    {
        $montoEsperado = $this->calcularMontoEsperado($caja);
        $diferencia = $montoReal - $montoEsperado;

        $caja->update([
            'monto_cierre' => $montoReal,
            'monto_esperado' => $montoEsperado,
            'diferencia' => $diferencia,
            'estado' => 'cerrada',
            'observaciones' => $observaciones,
            'fecha_cierre' => Carbon::now(),
        ]);

        return $caja->fresh();
    }

    /**
     * Calcular el monto esperado en caja.
     * Fórmula: apertura + ventas en efectivo del turno.
     */
    public function calcularMontoEsperado(Caja $caja): float
    {
        $ventasEfectivo = $caja->totalVentasEfectivo();
        return (float) $caja->monto_apertura + $ventasEfectivo;
    }

    /**
     * Obtener la caja abierta de un usuario.
     */
    public function obtenerCajaAbierta(int $usuarioId): ?Caja
    {
        return Caja::where('usuario_id', $usuarioId)
            ->where('estado', 'abierta')
            ->first();
    }
}
