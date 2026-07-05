@extends('layouts.app')

@section('title', 'Auditoría de Productos')
@section('header', 'Auditoría de Base de Datos')
@section('subheader', 'Historial inalterable de cambios generados por Triggers MySQL en la tabla de Productos.')

@section('content')

<div class="mb-8 bg-white p-4 rounded-xl shadow-sm border border-gray-200">
    <form action="{{ route('auditoria.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-medium text-gray-700 mb-1">Buscar por Nombre de Producto</label>
            <input type="text" name="busqueda" value="{{ request('busqueda') }}" placeholder="Ej. Torta de Chocolate..." class="block w-full border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 sm:text-sm">
        </div>
        <div class="flex-1">
            <label class="block text-xs font-medium text-gray-700 mb-1">Tipo de Acción</label>
            <select name="accion" class="block w-full border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 sm:text-sm">
                <option value="">Todas las acciones</option>
                <option value="INSERT" {{ request('accion') == 'INSERT' ? 'selected' : '' }}>Creación (INSERT)</option>
                <option value="UPDATE" {{ request('accion') == 'UPDATE' ? 'selected' : '' }}>Modificación (UPDATE)</option>
                <option value="DELETE" {{ request('accion') == 'DELETE' ? 'selected' : '' }}>Eliminación (DELETE)</option>
            </select>
        </div>
        <div>
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                <i class="fas fa-search mr-2"></i> Filtrar Logs
            </button>
            @if(request()->has('busqueda') || request()->has('accion'))
                <a href="{{ route('auditoria.index') }}" class="ml-2 w-full sm:w-auto inline-flex items-center justify-center px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                    Limpiar
                </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h3 class="text-lg leading-6 font-bold text-gray-900 flex items-center">
            <i class="fas fa-server text-red-600 mr-2"></i> Logs de Triggers (Sólo Lectura)
        </h3>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
            Nivel Base de Datos
        </span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha / Hora</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto (ID)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cambios (Precio / Costo / Stock)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario DB</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($auditorias as $log)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($log->accion == 'INSERT')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">NUEVO PRODUCTO</span>
                        @elseif($log->accion == 'UPDATE')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">MODIFICACIÓN</span>
                        @elseif($log->accion == 'DELETE')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">ELIMINADO DB</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $log->accion }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <strong>{{ $log->nombre_producto }}</strong> <span class="text-gray-400">#{{ $log->producto_id }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($log->accion == 'INSERT')
                            <ul class="list-disc pl-4 text-xs">
                                <li>Precio: Bs. {{ $log->precio_nuevo }}</li>
                                <li>Costo: Bs. {{ $log->costo_nuevo }}</li>
                                <li>Stock: {{ $log->stock_nuevo }}</li>
                            </ul>
                        @elseif($log->accion == 'DELETE')
                            <ul class="list-disc pl-4 text-xs">
                                <li>Precio final: Bs. {{ $log->precio_viejo }}</li>
                                <li>Costo final: Bs. {{ $log->costo_viejo }}</li>
                                <li>Stock restante: {{ $log->stock_viejo }}</li>
                            </ul>
                        @else
                            {{-- UPDATE --}}
                            <ul class="list-none space-y-1 text-xs">
                                @if($log->precio_viejo != $log->precio_nuevo)
                                    <li><span class="text-gray-400 line-through">Bs. {{ $log->precio_viejo }}</span> &rarr; <span class="font-bold text-green-600">Bs. {{ $log->precio_nuevo }}</span> (Precio)</li>
                                @endif
                                
                                @if($log->costo_viejo != $log->costo_nuevo)
                                    <li><span class="text-gray-400 line-through">Bs. {{ $log->costo_viejo }}</span> &rarr; <span class="font-bold text-amber-600">Bs. {{ $log->costo_nuevo }}</span> (Costo)</li>
                                @endif
                                
                                @if($log->stock_viejo != $log->stock_nuevo)
                                    <li><span class="text-gray-400 line-through">{{ $log->stock_viejo }} u.</span> &rarr; <span class="font-bold text-blue-600">{{ $log->stock_nuevo }} u.</span> (Stock)</li>
                                @endif
                            </ul>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-mono">
                        {{ $log->usuario_db }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-shield-alt text-4xl text-gray-300 mb-3 block"></i>
                        Aún no se han registrado cambios a nivel de base de datos desde que se activaron los Triggers.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($auditorias->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $auditorias->links() }}
        </div>
    @endif
</div>

@endsection
