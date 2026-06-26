<?php

use App\Models\Producto;
use App\Models\Categoria;

// Ensure category exists
$catComidas = Categoria::firstOrCreate(['nombre' => 'Horneados'], ['is_active' => true]);
$catBebidas = Categoria::firstOrCreate(['nombre' => 'Bebidas Clásicas'], ['is_active' => true]);

$nuevos = [
    [
        'nombre' => 'Salteña de Pollo',
        'descripcion' => 'Deliciosa salteña horneada con relleno jugoso de pollo y huevo.',
        'precio' => 8.00,
        'stock' => 50,
        'stock_minimo' => 10,
        'categoria_id' => $catComidas->id,
        'imagen' => 'productos/saltena_pollo.png',
        'is_active' => true,
        'mostrar_catalogo' => true,
    ],
    [
        'nombre' => 'Huminta al Horno',
        'descripcion' => 'Tradicional pastel de choclo dulce con queso fundido.',
        'precio' => 7.00,
        'stock' => 40,
        'stock_minimo' => 5,
        'categoria_id' => $catComidas->id,
        'imagen' => 'productos/huminta.png',
        'is_active' => true,
        'mostrar_catalogo' => true,
    ],
    [
        'nombre' => 'Tamal al Horno',
        'descripcion' => 'Masa de maíz horneada en chala, rellena de carne condimentada.',
        'precio' => 10.00,
        'stock' => 30,
        'stock_minimo' => 5,
        'categoria_id' => $catComidas->id,
        'imagen' => 'productos/tamal.png',
        'is_active' => true,
        'mostrar_catalogo' => true,
    ],
    [
        'nombre' => 'Jugo de Maracuyá',
        'descripcion' => 'Jugo natural de pura fruta, servido bien frío.',
        'precio' => 6.00,
        'stock' => 100,
        'stock_minimo' => 20,
        'categoria_id' => $catBebidas->id,
        'imagen' => 'productos/maracuya.png',
        'is_active' => true,
        'mostrar_catalogo' => true,
    ],
];

foreach ($nuevos as $p) {
    Producto::updateOrCreate(['nombre' => $p['nombre']], $p);
}

echo "Nuevos productos agregados exitosamente.\n";
