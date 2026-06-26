@extends('layouts.app')

@section('title', 'Panel de Control')
@section('header', 'Panel de Control')
@section('subheader', 'Resumen de actividad del día ' . date('d/m/Y'))

@section('actions')
<a href="{{ route('pos.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
    <i class="fas fa-cash-register mr-2"></i> Nuevo Pedido
</a>
@endsection

@section('content')
<!-- KPIs del día -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    
    <!-- Ventas Hoy -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                    <i class="fas fa-dollar-sign text-green-600 text-xl w-6 h-6 flex items-center justify-center"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Ventas de Hoy</dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900">Bs. {{ number_format($ventasHoy, 2) }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('ventas.index', ['fecha' => date('Y-m-d')]) }}" class="font-medium text-amber-600 hover:text-amber-500">Ver todas las ventas <i class="fas fa-arrow-right text-xs ml-1"></i></a>
            </div>
        </div>
    </div>

    <!-- Productos Vendidos -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                    <i class="fas fa-hamburger text-blue-600 text-xl w-6 h-6 flex items-center justify-center"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Productos Vendidos</dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900">{{ $productosVendidos }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm text-gray-500">
                Unidades entregadas hoy
            </div>
        </div>
    </div>

    <!-- Transacciones -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                    <i class="fas fa-receipt text-purple-600 text-xl w-6 h-6 flex items-center justify-center"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Transacciones</dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900">{{ $transaccionesHoy }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm text-gray-500">
                Tickets emitidos hoy
            </div>
        </div>
    </div>

    <!-- Alerta Stock -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 {{ $stockBajo > 0 ? 'bg-red-100' : 'bg-gray-100' }} rounded-lg p-3">
                    <i class="fas fa-exclamation-triangle {{ $stockBajo > 0 ? 'text-red-600' : 'text-gray-400' }} text-xl w-6 h-6 flex items-center justify-center"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Alertas de Stock</dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900">{{ $stockBajo }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('productos.index') }}" class="font-medium {{ $stockBajo > 0 ? 'text-red-600 hover:text-red-500' : 'text-gray-500 hover:text-gray-700' }}">Revisar inventario <i class="fas fa-arrow-right text-xs ml-1"></i></a>
                @else
                    <span class="text-gray-500 font-medium">Productos por agotarse</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Gráfico / Tabla Resumen Semanal -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Ventas de los últimos 7 días</h3>
        </div>
        <div class="p-6">
            <!-- Gráfico de barras simple (CSS) -->
            <div class="h-64 flex items-end space-x-2 sm:space-x-4">
                @foreach($ventasSemana as $dia)
                    @php 
                        $porcentaje = $maxVentaSemana > 0 ? ($dia['total'] / $maxVentaSemana) * 100 : 0; 
                        $esHoy = $loop->last;
                    @endphp
                    <div class="flex-1 flex flex-col items-center group relative">
                        <!-- Tooltip -->
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity absolute bottom-full mb-2 bg-gray-900 text-white text-xs rounded py-1 px-2 whitespace-nowrap z-10 pointer-events-none">
                            Bs. {{ number_format($dia['total'], 2) }}
                        </div>
                        
                        <!-- Barra -->
                        <div class="w-full {{ $esHoy ? 'bg-amber-500' : 'bg-amber-200 group-hover:bg-amber-300' }} rounded-t-sm transition-all" x-bind:style="'height: ' + {{ max($porcentaje, 2) }} + '%'"></div>
                        
                        <!-- Etiqueta -->
                        <div class="mt-2 text-xs {{ $esHoy ? 'font-bold text-gray-900' : 'text-gray-500' }}">{{ $dia['dia'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="space-y-8">
        
        <!-- Productos por agotarse -->
        @if($productosStockBajo->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-red-50">
                    <h3 class="text-lg leading-6 font-medium text-red-800 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i> Stock Crítico
                    </h3>
                </div>
                <ul class="divide-y divide-gray-200">
                    @foreach($productosStockBajo as $producto)
                        <li class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $producto->nombre }}</p>
                                <p class="text-xs text-gray-500">{{ $producto->categoria->nombre }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $producto->stock == 0 ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                                    {{ $producto->stock }} en stock
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Últimas Ventas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Últimas Ventas</h3>
                <a href="{{ route('ventas.index') }}" class="text-sm text-amber-600 hover:text-amber-500 font-medium">Ver todas</a>
            </div>
            @if($ultimasVentas->isEmpty())
                <div class="p-6 text-center text-gray-500 text-sm">
                    No hay ventas registradas aún.
                </div>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($ultimasVentas as $venta)
                        <li class="px-6 py-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-bold text-gray-900">{{ $venta->numero_venta }}</span>
                                <span class="text-sm font-bold text-green-600">Bs. {{ number_format($venta->total, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span>{{ $venta->created_at->format('H:i') }} • {{ $venta->usuario->name }}</span>
                                <span class="flex items-center">
                                    <i class="{{ $venta->metodoPago->icono }} mr-1"></i> {{ $venta->metodoPago->nombre }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        
    </div>
</div>
@endsection
