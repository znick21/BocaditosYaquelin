@extends('layouts.app')

@section('title', 'Historial de Inventario')
@section('header', 'Historial de Movimientos')
@section('subheader', 'Consulta el registro de producción, mermas, ajustes y ventas.')

@section('actions')
<a href="{{ route('inventario.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
    <i class="fas fa-clipboard-list mr-2"></i> Ir a Planilla Diaria
</a>
@endsection

@section('content')
<div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-200">
    <form action="{{ route('inventario.historial') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
        
        <div class="w-full sm:w-48">
            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Desde Fecha</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ request('fecha_inicio') }}" class="focus:ring-amber-500 focus:border-amber-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
        </div>
        
        <div class="w-full sm:w-48">
            <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">Hasta Fecha</label>
            <input type="date" name="fecha_fin" id="fecha_fin" value="{{ request('fecha_fin') }}" class="focus:ring-amber-500 focus:border-amber-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
        </div>

        <div class="w-full sm:w-48">
            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Movimiento</label>
            <select id="tipo" name="tipo" class="focus:ring-amber-500 focus:border-amber-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                <option value="">Todos</option>
                <option value="produccion" {{ request('tipo') == 'produccion' ? 'selected' : '' }}>Producción (+)</option>
                <option value="merma" {{ request('tipo') == 'merma' ? 'selected' : '' }}>Merma (-)</option>
                <option value="venta" {{ request('tipo') == 'venta' ? 'selected' : '' }}>Venta (-)</option>
                <option value="ajuste" {{ request('tipo') == 'ajuste' ? 'selected' : '' }}>Ajuste (+/-)</option>
            </select>
        </div>
        
        <div class="flex-1 w-full">
            <label for="producto_id" class="block text-sm font-medium text-gray-700 mb-1">Producto</label>
            <select id="producto_id" name="producto_id" class="focus:ring-amber-500 focus:border-amber-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                <option value="">Todos los productos</option>
                @foreach($productos as $prod)
                    <option value="{{ $prod->id }}" {{ request('producto_id') == $prod->id ? 'selected' : '' }}>{{ $prod->nombre }} ({{ $prod->codigo }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                <i class="fas fa-filter mr-2"></i> Filtrar
            </button>
        </div>
        @if(request()->has('fecha_inicio') || request()->has('fecha_fin') || request()->has('tipo') || request()->has('producto_id'))
        <div>
            <a href="{{ route('inventario.historial') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                Limpiar
            </a>
        </div>
        @endif
    </form>
</div>

<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto p-4">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha / Hora</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motivo</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($movimientos as $mov)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $mov->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $mov->producto ? $mov->producto->nombre : 'Producto Eliminado' }}</div>
                        <div class="text-xs text-gray-500">{{ $mov->producto ? $mov->producto->codigo : '' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($mov->tipo == 'produccion')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Producción
                            </span>
                        @elseif($mov->tipo == 'merma')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Merma
                            </span>
                        @elseif($mov->tipo == 'venta')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                Venta
                            </span>
                        @elseif($mov->tipo == 'ajuste')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Ajuste
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold {{ $mov->cantidad > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $mov->cantidad > 0 ? '+' : '' }}{{ $mov->cantidad }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $mov->motivo }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex items-center">
                        <i class="fas fa-user-circle text-gray-400 mr-2 text-lg"></i>
                        {{ $mov->usuario ? $mov->usuario->name : 'Sistema' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-history text-4xl text-gray-300 mb-3"></i>
                            <p>No se encontraron movimientos con estos filtros.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($movimientos->hasPages())
    <div class="px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $movimientos->links() }}
    </div>
    @endif
</div>
@endsection
