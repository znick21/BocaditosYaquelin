<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarioMovimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'tipo',
        'cantidad',
        'motivo',
        'usuario_id'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class)->withTrashed();
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
