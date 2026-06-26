<?php

use App\Models\Producto;
use App\Models\Categoria;

$catComidas = Categoria::where('nombre', 'Horneados')->first();

$nuevos = [
    [
        'nombre' => 'Rosquitas',
        'descripcion' => 'Rosquitas crujientes de maíz y queso, horneadas a la perfección.',
        'precio' => 4.00,
        'stock' => 100,
        'stock_minimo' => 20,
        'categoria_id' => $catComidas->id,
        'imagen' => null,
        'is_active' => true,
        'mostrar_catalogo' => true,
    ],
    [
        'nombre' => 'Empanada de Maíz',
        'descripcion' => 'Deliciosa empanada hecha con masa de maíz amarillo y abundante queso.',
        'precio' => 6.00,
        'stock' => 60,
        'stock_minimo' => 15,
        'categoria_id' => $catComidas->id,
        'imagen' => null,
        'is_active' => true,
        'mostrar_catalogo' => true,
    ],
    [
        'nombre' => 'Asadito Cruceño',
        'descripcion' => 'Tradicional asadito frito de carne de res molida con yuca rallada y especias.',
        'precio' => 8.00,
        'stock' => 50,
        'stock_minimo' => 10,
        'categoria_id' => $catComidas->id,
        'imagen' => null,
        'is_active' => true,
        'mostrar_catalogo' => true,
    ],
];

foreach ($nuevos as $p) {
    Producto::updateOrCreate(['nombre' => $p['nombre']], $p);
}

echo "Asadito, Rosquitas y Empanada de maiz agregados exitosamente.\n";
