@extends('layouts.app')

@section('title', 'Categorías')
@section('header', 'Gestión de Categorías')
@section('subheader', 'Administra las categorías del menú de productos.')

@section('actions')
<a href="{{ route('categorias.crear') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
    <i class="fas fa-plus mr-2"></i> Nueva Categoría
</a>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto p-4">
        <table id="tablaCategorias" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icono</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Productos</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categorias as $categoria)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                        {{ $categoria->codigo }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                            <i class="{{ $categoria->icono ?? 'fas fa-tag' }}"></i>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $categoria->nombre }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-500 line-clamp-1">{{ $categoria->descripcion ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $categoria->productos_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <form action="{{ route('categorias.estado', $categoria) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium {{ $categoria->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} hover:opacity-80 transition-opacity">
                                {{ $categoria->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('categorias.editar', $categoria) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-2 rounded-lg transition-colors" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form id="form-eliminar-cat-{{ $categoria->id }}" action="{{ route('categorias.eliminar', $categoria) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button" onclick="confirmarEliminacion('form-eliminar-cat-{{ $categoria->id }}', 'esta categoría')" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-lg transition-colors" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-tags text-4xl text-gray-300 mb-3"></i>
                            <p>No hay categorías registradas.</p>
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
        $('#tablaCategorias').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            "pageLength": 25,
            "order": [[ 2, "asc" ]] // Ordenar por nombre por defecto (columna 2 porque 0=Código, 1=Icono)
        });
    });
</script>
@endsection
