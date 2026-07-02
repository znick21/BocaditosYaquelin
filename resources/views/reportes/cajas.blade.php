@extends('layouts.app')

@section('title', 'Reporte de Cajas')
@section('header', 'Reportes')
@section('subheader', 'Historial y auditoría de aperturas y cierres de caja.')

@section('content')

@include('reportes.partials.tabs')

<div class="mb-8 bg-white p-4 rounded-xl shadow-sm border border-gray-200">
    <form action="{{ route('reportes.cajas') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
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
    <!-- Total Recaudado -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                    <i class="fas fa-money-bill-wave text-green-600 text-xl w-6 h-6 flex items-center justify-center"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Recaudado</dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900">Bs. {{ number_format($resumen['total_recaudado'], 2) }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Sobrantes -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                    <i class="fas fa-plus-circle text-blue-600 text-xl w-6 h-6 flex items-center justify-center"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Sobrantes</dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900">Bs. {{ number_format($resumen['total_sobrantes'], 2) }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Faltantes -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-100 rounded-lg p-3">
                    <i class="fas fa-minus-circle text-red-600 text-xl w-6 h-6 flex items-center justify-center"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Faltantes</dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900 text-red-600">Bs. {{ number_format(abs($resumen['total_faltantes']), 2) }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Cantidad de Cajas -->
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                    <i class="fas fa-cash-register text-purple-600 text-xl w-6 h-6 flex items-center justify-center"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Turnos/Cajas</dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900">{{ $resumen['cantidad_cajas'] }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
        <h3 class="text-lg leading-6 font-bold text-gray-900">Historial de Cajas</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Apertura</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Cierre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cajero</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Esperado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cierre</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Diferencia</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($cajas as $caja)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $caja->fecha_apertura ? $caja->fecha_apertura->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $caja->fecha_cierre ? $caja->fecha_cierre->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $caja->usuario->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                        Bs. {{ number_format($caja->monto_esperado, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                        Bs. {{ number_format($caja->monto_cierre, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right 
                        @if($caja->diferencia > 0) text-green-600 
                        @elseif($caja->diferencia < 0) text-red-600 
                        @else text-gray-500 @endif">
                        Bs. {{ number_format($caja->diferencia, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($caja->estado == 'abierta')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Abierta
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Cerrada
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                        No se encontraron registros de caja en este periodo.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
