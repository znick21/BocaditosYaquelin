<?php
$map = [
    'Empanada de Queso Frita' => 'productos/empanada.png',
    'Cuñapé' => 'productos/cunape.png',
    'Sonso de Yuca' => 'productos/sonso.png',
    'Masaco de Plátano' => 'productos/masaco.png',
    'Refresco de Mocochinchi' => 'productos/mocochinchi.png',
    'Chicha Cruceña' => 'productos/chicha.png',
];
foreach($map as $nombre => $imagen) {
    App\Models\Producto::where('nombre', $nombre)->update(['imagen' => $imagen]);
}
echo "Imágenes actualizadas en la DB exitosamente.\n";
