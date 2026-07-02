@extends('layouts.app')

@section('title', 'Productos')
@section('header', 'Gestión de Productos e Inventario')
@section('subheader', 'Administra el catálogo, precios y stock.')

@section('actions')
<a href="{{ route('productos.crear') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
    <i class="fas fa-plus mr-2"></i> Nuevo Producto
</a>
@endsection

@section('content')
<div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-200">
    <form action="{{ route('productos.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label for="buscar" class="block text-sm font-medium text-gray-700 mb-1">Buscar Producto o Código</label>
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" name="buscar" id="buscar" value="{{ request('buscar') }}" class="focus:ring-amber-500 focus:border-amber-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 px-3 border" placeholder="Ej. Torta de chocolate, PROD-01...">
            </div>
        </div>
        <div class="w-full sm:w-64">
            <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">Filtrar por Categoría</label>
            <select id="categoria" name="categoria" class="focus:ring-amber-500 focus:border-amber-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}" {{ request('categoria') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                <i class="fas fa-filter mr-2"></i> Filtrar
            </button>
        </div>
        @if(request()->has('buscar') || request()->has('categoria'))
        <div>
            <a href="{{ route('productos.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                Limpiar
            </a>
        </div>
        @endif
    </form>
</div>

<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto p-4">
        <table id="tablaProductos" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio / Costo</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>

                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($productos as $producto)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                        {{ $producto->codigo }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-md flex items-center justify-center overflow-hidden">
                                @if($producto->imagen)
                                    <img class="h-10 w-10 object-cover" src="{{ asset('storage/' . $producto->imagen) }}" alt="">
                                @else
                                    <i class="fas fa-utensils text-gray-400"></i>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-bold text-gray-900">{{ $producto->nombre }}</div>
                                @if($producto->mostrar_catalogo)
                                    <div class="text-[10px] text-green-600 font-medium uppercase"><i class="fas fa-globe mr-1"></i>En Catálogo</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $producto->categoria->nombre }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="text-sm font-bold text-amber-600">Bs. {{ number_format($producto->precio, 2) }}</div>
                        <div class="text-xs text-gray-500">Costo: Bs. {{ number_format($producto->costo, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $producto->stock <= $producto->stock_minimo ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $producto->stock }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <form action="{{ route('productos.estado', $producto) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium {{ $producto->is_active ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }} hover:opacity-80 transition-opacity">
                                {{ $producto->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('productos.editar', $producto) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-2 rounded-lg transition-colors" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form id="form-eliminar-prod-{{ $producto->id }}" action="{{ route('productos.eliminar', $producto) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button" onclick="confirmarEliminacion('form-eliminar-prod-{{ $producto->id }}', 'este producto')" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                            <p>No se encontraron productos.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables JS & CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tablaProductos').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            "pageLength": 25,
            "order": [[ 1, "asc" ]] // Ordenar por nombre por defecto
        });
    });
</script>
@endsection
