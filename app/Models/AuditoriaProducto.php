<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaProducto extends Model
{
    protected $table = 'auditoria_productos';

    // Disable timestamps since the trigger inserts `created_at` and there is no `updated_at`
    public $timestamps = false;

    // The fields inserted by the trigger
    protected $fillable = [
        'accion',
        'producto_id',
        'nombre_producto',
        'precio_viejo',
        'precio_nuevo',
        'costo_viejo',
        'costo_nuevo',
        'stock_viejo',
        'stock_nuevo',
        'usuario_db',
        'created_at'
    ];
    
    // Cast created_at as a date/datetime to use Carbon formatting
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class)->withTrashed();
    }
}
