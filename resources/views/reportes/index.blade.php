@extends('layouts.app')

@section('title', 'Reportes y Analíticas')
@section('header', 'Reportes')
@section('subheader', 'Analiza el rendimiento del negocio.')

@section('content')

<div class="mb-8 bg-white p-4 rounded-xl shadow-sm border border-gray-200">
    <form action="{{ route('reportes.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="block w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
        </div>
        <div class="flex-1">
            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Fin</label>
            <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="block w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
        </div>
        <div>
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none">
                Generar Reporte
            </button>
        </div>
    </form>
</div>

<!-- Resumen General -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <!-- Total Ventas -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                    <i class="fas fa-money-bill-wave text-green-600 text-xl w-6 h-6 flex items-center justify-center"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Ingresos</dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900">Bs. {{ number_format($resumen['ingresos'], 2) }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Promedio -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                    <i class="fas fa-receipt text-blue-600 text-xl w-6 h-6 flex items-center justify-center"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Ticket Promedio</dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900">Bs. {{ number_format($resumen['ticket_promedio'], 2) }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Transacciones -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                    <i class="fas fa-shopping-basket text-purple-600 text-xl w-6 h-6 flex items-center justify-center"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Nº Ventas</dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($resumen['total_ventas']) }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos Vendidos -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-amber-100 rounded-lg p-3">
                    <i class="fas fa-hamburger text-amber-600 text-xl w-6 h-6 flex items-center justify-center"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Items Vendidos</dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format($resumen['productos_vendidos']) }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Top Productos -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Productos Más Vendidos</h3>
            <span class="text-xs text-gray-500">Por unidades vendidas</span>
        </div>
        <ul class="divide-y divide-gray-200">
            @forelse($topProductos as $index => $prod)
            <li class="px-6 py-4 flex items-center hover:bg-gray-50">
                <div class="flex-shrink-0 h-8 w-8 rounded-full {{ $index == 0 ? 'bg-yellow-100 text-yellow-600' : ($index == 1 ? 'bg-gray-100 text-gray-600' : ($index == 2 ? 'bg-amber-100 text-amber-700' : 'bg-blue-50 text-blue-500')) }} flex items-center justify-center font-bold text-sm">
                    {{ $index + 1 }}
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $prod->nombre_producto }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-gray-900">{{ number_format($prod->total_vendido) }} unds.</p>
                    <p class="text-xs text-gray-500">Bs. {{ number_format($prod->ingresos, 2) }}</p>
                </div>
            </li>
            @empty
            <li class="px-6 py-8 text-center text-gray-500 text-sm">No hay datos en este periodo</li>
            @endforelse
        </ul>
    </div>

    <!-- Ventas por Método de Pago -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Ingresos por Método de Pago</h3>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                @php $maxMetodo = $ventasPorMetodo->max('ingresos') ?: 1; @endphp
                
                @forelse($ventasPorMetodo as $metodo)
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700">{{ $metodo->nombre }} ({{ number_format($metodo->cantidad) }} ventas)</span>
                        <span class="font-bold text-gray-900">Bs. {{ number_format($metodo->ingresos, 2) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-amber-600 h-2.5 rounded-full" x-bind:style="'width: ' + {{ ($metodo->ingresos / $maxMetodo) * 100 }} + '%'"></div>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 text-sm py-4">No hay datos en este periodo</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
