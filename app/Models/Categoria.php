<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Categorías
 * Fase:        1 - Base de Datos
 * Descripción: Modelo de categorías para clasificar
 *              los productos del menú.
 * ═══════════════════════════════════════════════════
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categorias';

    protected $fillable = [
        'codigo',
        'nombre', 
        'descripcion', 
        'icono',
        'imagen', 
        'orden', 
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ── Relaciones ──

    // ── Boot ──
    protected static function booted()
    {
        static::creating(function ($categoria) {
            if (empty($categoria->codigo)) {
                $slug = \Illuminate\Support\Str::slug($categoria->nombre);
                $parts = explode('-', $slug);
                $codigo = '';
                foreach($parts as $p) {
                    if (strlen($p) > 0) {
                        $codigo .= strtoupper(substr($p, 0, 3));
                    }
                }
                $baseCodigo = substr($codigo, 0, 8);
                $nextId = (Categoria::max('id') ?? 0) + 1;
                $categoria->codigo = $baseCodigo . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }

    public function productosActivos()
    {
        return $this->hasMany(Producto::class, 'categoria_id')
            ->where('is_active', true);
    }

    // ── Scopes ──

    public function scopeActivas(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('is_active', true)->orderBy('orden');
    }
}
