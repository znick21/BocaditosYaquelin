<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PuntoVentaController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\ReporteController;

// ── Rutas Públicas (Landing y Catálogo) ──
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/catalogo/{categoriaId?}', [LandingController::class, 'catalogo'])->name('catalogo');

// ── Autenticación ──
Route::get('/login', [AutenticacionController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AutenticacionController::class, 'iniciarSesion']);
Route::post('/logout', [AutenticacionController::class, 'cerrarSesion'])->name('logout');

// ── Rutas Protegidas (Requieren Login) ──
Route::middleware(['auth'])->group(function () {
    
    // Panel Principal
    Route::get('/panel', [PanelController::class, 'index'])->name('panel');

    // ── Módulo de Caja (Ambos Roles) ──
    Route::prefix('caja')->name('caja.')->group(function () {
        Route::get('/', [CajaController::class, 'index'])->name('index');
        Route::post('/abrir', [CajaController::class, 'abrir'])->name('abrir');
        Route::post('/{caja}/cerrar', [CajaController::class, 'cerrar'])->name('cerrar');
    });

    // ── Punto de Venta (Ambos Roles) ──
    Route::get('/pos', [PuntoVentaController::class, 'index'])->name('pos.index');
    Route::post('/pos/venta', [PuntoVentaController::class, 'registrarVenta'])->name('pos.venta');

    // ── Historial de Ventas ──
    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
    Route::get('/ventas/{venta}', [VentaController::class, 'detalle'])->name('ventas.detalle');

    // ── Rutas Exclusivas para Administradores ──
    Route::middleware(['\App\Http\Middleware\VerificarRol:admin'])->group(function () {
        
        // Categorías
        Route::resource('categorias', CategoriaController::class)->except(['show'])->names([
            'index' => 'categorias.index',
            'create' => 'categorias.crear',
            'store' => 'categorias.guardar',
            'edit' => 'categorias.editar',
            'update' => 'categorias.actualizar',
            'destroy' => 'categorias.eliminar',
        ]);
        Route::post('categorias/{categoria}/estado', [CategoriaController::class, 'cambiarEstado'])->name('categorias.estado');

        // Productos
        Route::resource('productos', ProductoController::class)->except(['show'])->names([
            'index' => 'productos.index',
            'create' => 'productos.crear',
            'store' => 'productos.guardar',
            'edit' => 'productos.editar',
            'update' => 'productos.actualizar',
            'destroy' => 'productos.eliminar',
        ]);
        Route::post('productos/{producto}/estado', [ProductoController::class, 'cambiarEstado'])->name('productos.estado');

        // Sliders
        Route::resource('sliders', \App\Http\Controllers\SliderController::class)->except(['show'])->names([
            'index' => 'sliders.index',
            'create' => 'sliders.crear',
            'store' => 'sliders.guardar',
            'edit' => 'sliders.editar',
            'update' => 'sliders.actualizar',
            'destroy' => 'sliders.eliminar',
        ]);
        Route::post('sliders/{slider}/estado', [\App\Http\Controllers\SliderController::class, 'cambiarEstado'])->name('sliders.estado');

        // Anulación de Ventas
        Route::post('/ventas/{venta}/anular', [VentaController::class, 'anular'])->name('ventas.anular');

        // Historial de Ventas
        Route::get('/ventas', [App\Http\Controllers\VentaController::class, 'index'])->name('ventas.index');
        Route::get('/ventas/{venta}', [App\Http\Controllers\VentaController::class, 'detalle'])->name('ventas.detalle');
        Route::get('/ventas/{venta}/ticket', [App\Http\Controllers\VentaController::class, 'ticket'])->name('ventas.ticket');

        // Inventario (Producción y Mermas)
        Route::get('/inventario', [\App\Http\Controllers\InventarioController::class, 'index'])->name('inventario.index');
        Route::post('/inventario/planilla', [\App\Http\Controllers\InventarioController::class, 'guardarPlanilla'])->name('inventario.planilla');
        Route::get('/inventario/historial', [\App\Http\Controllers\InventarioController::class, 'historial'])->name('inventario.historial');

        // Reportes
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');

        // Configuración General
        Route::get('/configuracion', [App\Http\Controllers\ConfiguracionController::class, 'index'])->name('configuracion.index');
        Route::put('/configuracion', [App\Http\Controllers\ConfiguracionController::class, 'update'])->name('configuracion.update');
    });
});
