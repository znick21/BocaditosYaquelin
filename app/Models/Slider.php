<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Sliders (Carrusel)
 * Fase:        1 - Base de Datos
 * Descripción: Modelo para las imágenes del carrusel
 *              de la landing page pública.
 * ═══════════════════════════════════════════════════
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'sliders';

    protected $fillable = [
        'titulo', 'subtitulo', 'descripcion',
        'imagen', 'texto_boton', 'enlace_boton',
        'orden', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ── Scopes ──

    public function scopeActivos(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('is_active', true)->orderBy('orden');
    }
}
