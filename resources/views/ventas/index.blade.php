@extends('layouts.app')

@section('title', 'Historial de Ventas')
@section('header', 'Historial de Ventas')
@section('subheader', 'Consulta todas las transacciones realizadas.')

@section('content')
<!-- Filtros -->
<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
    <form action="{{ route('ventas.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
        
        <div class="sm:w-48">
            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha</label>
            <input type="date" name="fecha" value="{{ request('fecha') }}" class="block w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
        </div>
        
        <div class="sm:w-48">
            <label class="block text-xs font-medium text-gray-700 mb-1">Estado</label>
            <select name="estado" class="block w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                <option value="">Todos</option>
                <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completadas</option>
                <option value="anulada" {{ request('estado') == 'anulada' ? 'selected' : '' }}>Anuladas</option>
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-gray-900 focus:outline-none">
                Filtrar
            </button>
            @if(request()->hasAny(['fecha', 'estado', 'usuario_id']))
                <a href="{{ route('ventas.index') }}" class="ml-2 mb-2 text-sm text-gray-500 hover:text-gray-700">Limpiar</a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comprobante</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha / Cajero</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ver</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($ventas as $venta)
                <tr class="hover:bg-gray-50 transition-colors {{ $venta->estaAnulada() ? 'bg-red-50/30 opacity-75' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">{{ $venta->numero_venta }}</div>
                        <div class="text-xs text-gray-500">{{ $venta->detalles->count() }} ítems</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $venta->created_at->format('d/m/Y H:i') }}</div>
                        <div class="text-xs text-gray-500">{{ $venta->usuario->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="inline-flex items-center text-sm text-gray-700">
                            <i class="{{ $venta->metodoPago->icono }} mr-2 text-gray-400"></i>
                            {{ $venta->metodoPago->nombre }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($venta->estaAnulada())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Anulada
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Completada
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-sm font-bold {{ $venta->estaAnulada() ? 'text-red-600 line-through' : 'text-amber-600' }}">
                            Bs. {{ number_format($venta->total, 2) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('ventas.detalle', $venta) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-2 rounded-md transition-colors">
                            Detalle
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-receipt text-4xl text-gray-300 mb-3"></i>
                            <p>No se encontraron ventas para los filtros aplicados.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ventas->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $ventas->links() }}
    </div>
    @endif
</div>
@endsection
