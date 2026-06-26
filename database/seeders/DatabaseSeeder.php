<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Configuracion;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\MetodoPago;
use App\Models\Menu;
use App\Models\Slider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Configuración General
        Configuracion::create([
            'nombre_negocio' => 'Bocaditos Yaquelin',
            'eslogan' => 'El verdadero sabor cruceño',
            'telefono' => '+591 70000000',
            'whatsapp' => '+591 70000000',
            'email' => 'contacto@bocaditosyaquelin.com',
            'direccion' => 'Av. Banzer, Santa Cruz de la Sierra',
            'moneda' => 'Bs',
            'color_primario' => '#f59e0b', // Amber 500
            'color_secundario' => '#ea580c', // Orange 600
        ]);

        // 2. Usuarios
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@yakelin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Cajero Turno Mañana',
            'email' => 'cajero@yakelin.com',
            'password' => Hash::make('password'),
            'role' => 'cajero',
            'is_active' => true,
        ]);

        // 3. Métodos de Pago
        MetodoPago::create(['nombre' => 'Efectivo', 'icono' => 'fas fa-money-bill-wave']);
        MetodoPago::create(['nombre' => 'QR Simple', 'icono' => 'fas fa-qrcode']);
        MetodoPago::create(['nombre' => 'Tarjeta', 'icono' => 'fas fa-credit-card']);

        // 4. Categorías
        $catSalados = Categoria::create(['nombre' => 'Bocaditos Salados', 'descripcion' => 'Empanadas, cuñapés y más.', 'icono' => 'fas fa-hamburger']);
        $catDulces = Categoria::create(['nombre' => 'Bocaditos Dulces', 'descripcion' => 'Masacos, tortas y postres.', 'icono' => 'fas fa-cookie']);
        $catBebidas = Categoria::create(['nombre' => 'Bebidas Tradicionales', 'descripcion' => 'Refrescos naturales.', 'icono' => 'fas fa-glass-whiskey']);

        // 5. Productos
        Producto::create([
            'categoria_id' => $catSalados->id,
            'nombre' => 'Empanada de Queso Frita',
            'precio' => 5.00,
            'costo' => 2.50,
            'stock' => 50,
            'stock_minimo' => 10,
            'mostrar_catalogo' => true,
        ]);

        Producto::create([
            'categoria_id' => $catSalados->id,
            'nombre' => 'Cuñapé',
            'precio' => 4.00,
            'costo' => 2.00,
            'stock' => 100,
            'stock_minimo' => 20,
            'mostrar_catalogo' => true,
        ]);

        Producto::create([
            'categoria_id' => $catSalados->id,
            'nombre' => 'Sonso de Yuca',
            'precio' => 6.00,
            'costo' => 3.00,
            'stock' => 30,
            'stock_minimo' => 5,
            'mostrar_catalogo' => true,
        ]);

        Producto::create([
            'categoria_id' => $catDulces->id,
            'nombre' => 'Masaco de Plátano',
            'precio' => 7.00,
            'costo' => 3.50,
            'stock' => 20,
            'stock_minimo' => 5,
            'mostrar_catalogo' => true,
        ]);

        Producto::create([
            'categoria_id' => $catBebidas->id,
            'nombre' => 'Refresco de Mocochinchi',
            'precio' => 5.00,
            'costo' => 1.50,
            'stock' => 80,
            'stock_minimo' => 15,
            'mostrar_catalogo' => true,
        ]);

        Producto::create([
            'categoria_id' => $catBebidas->id,
            'nombre' => 'Chicha Cruceña',
            'precio' => 5.00,
            'costo' => 1.50,
            'stock' => 80,
            'stock_minimo' => 15,
            'mostrar_catalogo' => true,
        ]);

        // 6. Sliders (Carrusel Público)
        Slider::create([
            'titulo' => '¡Bienvenido a Bocaditos Yaquelin!',
            'subtitulo' => 'El mejor sabor tradicional.',
            'descripcion' => 'Disfruta de nuestras empanadas, cuñapés y bebidas frescas preparadas al instante.',
            'imagen' => 'default_slider1.jpg', // Requiere placeholder
            'texto_boton' => 'Ver Menú',
            'enlace_boton' => '/catalogo',
            'orden' => 1
        ]);

        // 7. Menús
        Menu::create(['nombre' => 'Inicio', 'enlace' => '/', 'orden' => 1]);
        Menu::create(['nombre' => 'Catálogo', 'enlace' => '/catalogo', 'orden' => 2]);
        Menu::create(['nombre' => 'Ingresar (POS)', 'enlace' => '/login', 'orden' => 3]);
    }
}
