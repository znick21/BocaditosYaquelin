<?php
/**
 * ═══════════════════════════════════════════════════
 * SISTEMA POS - BOCADITOS YAQUELIN
 * ═══════════════════════════════════════════════════
 * Módulo:      Productos
 * Fase:        1 - Base de Datos
 * Descripción: Modelo de productos (bocaditos, bebidas).
 *              Incluye control de stock con alertas
 *              de mínimo y visibilidad en catálogo.
 * ═══════════════════════════════════════════════════
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'productos';

    protected $fillable = [
        'codigo', 'categoria_id', 'nombre', 'descripcion',
        'precio', 'costo', 'stock', 'stock_minimo', 'dias_duracion',
        'imagen', 'mostrar_catalogo', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'costo' => 'decimal:2',
            'is_active' => 'boolean',
            'mostrar_catalogo' => 'boolean',
        ];
    }

    // ── Boot ──
    protected static function booted()
    {
        static::creating(function ($producto) {
            if (empty($producto->codigo)) {
                $slug = \Illuminate\Support\Str::slug($producto->nombre);
                $parts = explode('-', $slug);
                $codigo = '';
                foreach($parts as $p) {
                    if (strlen($p) > 0) {
                        $codigo .= strtoupper(substr($p, 0, 3));
                    }
                }
                $baseCodigo = substr($codigo, 0, 8);
                $nextId = (Producto::max('id') ?? 0) + 1;
                $producto->codigo = $baseCodigo . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    // ── Relaciones ──

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function inventarioMovimientos()
    {
        return $this->hasMany(InventarioMovimiento::class);
    }

    public function detallesVenta()
    {
        return $this->hasMany(DetalleVenta::class, 'producto_id');
    }

    // ── Métodos de negocio ──

    public function tieneStockBajo(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }

    public function estaAgotado(): bool
    {
        return $this->stock <= 0;
    }

    public function obtenerMargen(): float
    {
        if (!$this->costo || $this->costo == 0) return 0;
        return (($this->precio - $this->costo) / $this->costo) * 100;
    }

    // ── Scopes ──

    public function scopeActivos(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeConStock(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeParaCatalogo(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('mostrar_catalogo', true)
            ->where('is_active', true);
    }
}
