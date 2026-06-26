<?php

use App\Models\Slider;

$nuevosSliders = [
    [
        'titulo' => 'Horneados Cambas Auténticos',
        'subtitulo' => 'Tradición en cada bocado',
        'descripcion' => 'Disfruta de la mejor selección de cuñapés, empanadas de queso, y masaco, recién salidos del horno.',
        'texto_boton' => 'Descubrir Menú',
        'enlace_boton' => '/catalogo',
        'imagen' => 'placeholder',
        'orden' => 2,
        'is_active' => true,
    ],
    [
        'titulo' => 'Bebidas Tradicionales',
        'subtitulo' => 'Refrescante y natural',
        'descripcion' => 'Acompaña tus horneados con una deliciosa chicha cruceña bien fría o un tradicional mocochinchi.',
        'texto_boton' => 'Ver Bebidas',
        'enlace_boton' => '/catalogo',
        'imagen' => 'placeholder',
        'orden' => 3,
        'is_active' => true,
    ]
];

foreach ($nuevosSliders as $s) {
    Slider::updateOrCreate(['titulo' => $s['titulo']], $s);
}

echo "Sliders agregados exitosamente.\n";
