@extends('layouts.app')

@section('title', 'Banners Principales')
@section('header', 'Gestión de Banners (Sliders)')
@section('subheader', 'Administra las imágenes grandes del carrusel de la página principal.')

@section('actions')
<a href="{{ route('sliders.crear') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
    <i class="fas fa-plus mr-2"></i> Nuevo Banner
</a>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orden</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sliders as $slider)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $slider->orden }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="{{ asset('storage/' . $slider->imagen) }}" alt="{{ $slider->titulo }}" class="h-12 w-24 object-cover rounded-md border border-gray-200">
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $slider->titulo }}</div>
                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ $slider->subtitulo }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <form action="{{ route('sliders.estado', $slider) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $slider->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $slider->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('sliders.editar', $slider) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-md transition-colors" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form id="form-eliminar-slider-{{ $slider->id }}" action="{{ route('sliders.eliminar', $slider) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button" onclick="confirmarEliminacion('form-eliminar-slider-{{ $slider->id }}', 'este banner')" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-images text-4xl text-gray-300 mb-3"></i>
                            <p>No hay banners registrados.</p>
                            <a href="{{ route('sliders.crear') }}" class="mt-2 text-amber-600 hover:text-amber-800 font-medium">Agregar el primero</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
