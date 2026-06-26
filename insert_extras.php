<?php

use App\Models\Producto;
use App\Models\Categoria;

// Ensure category exists
$catComidas = Categoria::firstOrCreate(['nombre' => 'Horneados'], ['is_active' => true]);
$catBebidas = Categoria::firstOrCreate(['nombre' => 'Bebidas Clásicas'], ['is_active' => true]);

$nuevos = [
    [
        'nombre' => 'Pan de Arroz',
        'descripcion' => 'Tradicional pan de arroz con queso al horno, crocante por fuera y suave por dentro.',
        'precio' => 5.00,
        'stock' => 50,
        'stock_minimo' => 10,
        'categoria_id' => $catComidas->id,
        'imagen' => 'productos/pan_arroz.png',
        'is_active' => true,
        'mostrar_catalogo' => true,
    ],
    [
        'nombre' => 'Limonada con Limón Cambita',
        'descripcion' => 'Refrescante limonada preparada con limón cambita y mucho hielo.',
        'precio' => 7.00,
        'stock' => 100,
        'stock_minimo' => 20,
        'categoria_id' => $catBebidas->id,
        'imagen' => 'productos/limonada_cambita.png',
        'is_active' => true,
        'mostrar_catalogo' => true,
    ],
];

foreach ($nuevos as $p) {
    Producto::updateOrCreate(['nombre' => $p['nombre']], $p);
}

// Update huminta and tamal images
Producto::where('nombre', 'Huminta al Horno')->update(['imagen' => 'productos/huminta_v2.png']);
Producto::where('nombre', 'Tamal al Horno')->update(['imagen' => 'productos/tamal_horno_v2.png']);

echo "Productos extra agregados y actualizados exitosamente.\n";
