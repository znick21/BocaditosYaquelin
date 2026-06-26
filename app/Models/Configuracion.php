<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Configuración del Sistema
 * Fase:        1 - Base de Datos
 * Descripción: Modelo de configuración general del
 *              negocio. Tabla de fila única con datos
 *              de identidad, contacto y parámetros.
 * ═══════════════════════════════════════════════════
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracion';

    protected $fillable = [
        'nombre_negocio', 'eslogan', 'logo', 'favicon',
        'telefono', 'whatsapp', 'email', 'direccion',
        'facebook', 'instagram', 'tiktok',
        'moneda', 'impuesto_porcentaje',
        'horario_apertura', 'horario_cierre',
        'color_primario', 'color_secundario',
    ];

    protected function casts(): array
    {
        return [
            'impuesto_porcentaje' => 'decimal:2',
        ];
    }

    // ── Método para obtener la configuración (singleton) ──

    public static function obtener(): self
    {
        return static::firstOrCreate([], [
            'nombre_negocio' => 'Bocaditos Yaquelin',
        ]);
    }
}
