@extends('layouts.app')

@section('title', 'Reporte de Inventario')
@section('header', 'Reportes')
@section('subheader', 'Analiza el estado y valor de tu inventario.')

@section('content')

@include('reportes.partials.tabs')

<!-- Resumen General -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <!-- Valor Costo -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                    <i class="fas fa-boxes text-blue-600 text-xl w-6 h-6 flex items-center justify-center"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Valor del Inventario (Costo)</dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900">Bs. {{ number_format($valorCosto, 2) }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Valor Precio -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                    <i class="fas fa-money-bill-wave text-green-600 text-xl w-6 h-6 flex items-center justify-center"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Venta Proyectada</dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900">Bs. {{ number_format($valorPrecio, 2) }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Crítico -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-100 rounded-lg p-3">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl w-6 h-6 flex items-center justify-center"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Productos en Riesgo</dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900">{{ $stockCritico }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Productos bajo stock mínimo -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center mr-3">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="text-lg leading-6 font-bold text-gray-900">Stock Bajo Mínimo</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Actual</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Mínimo</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($productosCriticos as $producto)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $producto->nombre }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-bold text-right">
                            {{ $producto->stock }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                            {{ $producto->stock_minimo }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                            Todos los productos tienen stock suficiente.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mermas Recientes -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mr-3">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h3 class="text-lg leading-6 font-bold text-gray-900">Últimas Mermas Registradas</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pérdida</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mermasRecientes as $merma)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $merma->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $merma->producto->nombre }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-bold text-right">
                            {{ abs($merma->cantidad) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                            Bs. {{ number_format(abs($merma->cantidad) * $merma->producto->costo, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            No hay mermas registradas recientemente.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
