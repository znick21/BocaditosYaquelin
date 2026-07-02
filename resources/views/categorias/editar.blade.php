@extends('layouts.app')

@section('title', 'Editar Categoría')
@section('header', 'Editar Categoría')
@section('subheader', 'Modificar los datos de: ' . $categoria->nombre)

@section('actions')
<a href="{{ route('categorias.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
    <i class="fas fa-arrow-left mr-2"></i> Volver
</a>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden max-w-3xl mx-auto">
    <form action="{{ route('categorias.actualizar', $categoria) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">


            <!-- Nombre -->
            <div class="col-span-2 sm:col-span-1">
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre de la Categoría <span class="text-red-500">*</span></label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $categoria->nombre) }}" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                @error('nombre') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Icono -->
            <div class="col-span-2 sm:col-span-1">
                <label for="icono" class="block text-sm font-medium text-gray-700">Ícono representativo</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-amber-500 sm:text-lg">
                        <i class="{{ $categoria->icono ?: 'fas fa-icons' }}" id="icon-preview"></i>
                    </span>
                    <select name="icono" id="icono" onchange="document.getElementById('icon-preview').className = this.value || 'fas fa-icons'"
                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-amber-500 focus:border-amber-500 sm:text-sm border-gray-300">
                        <option value="" {{ empty($categoria->icono) ? 'selected' : '' }}>Ninguno (Por defecto)</option>
                        <option value="fas fa-star" {{ old('icono', $categoria->icono) == 'fas fa-star' ? 'selected' : '' }}>⭐ Estrellita (Destacado)</option>
                        <option value="fas fa-bread-slice" {{ old('icono', $categoria->icono) == 'fas fa-bread-slice' ? 'selected' : '' }}>🍞 Horneados / Pan</option>
                        <option value="fas fa-hamburger" {{ old('icono', $categoria->icono) == 'fas fa-hamburger' ? 'selected' : '' }}>🍔 Salados / Comida Rápida</option>
                        <option value="fas fa-cookie" {{ old('icono', $categoria->icono) == 'fas fa-cookie' ? 'selected' : '' }}>🍪 Galletas / Dulces</option>
                        <option value="fas fa-birthday-cake" {{ old('icono', $categoria->icono) == 'fas fa-birthday-cake' ? 'selected' : '' }}>🎂 Tortas / Postres</option>
                        <option value="fas fa-coffee" {{ old('icono', $categoria->icono) == 'fas fa-coffee' ? 'selected' : '' }}>☕ Café / Calientes</option>
                        <option value="fas fa-glass-whiskey" {{ old('icono', $categoria->icono) == 'fas fa-glass-whiskey' ? 'selected' : '' }}>🥤 Bebidas / Refrescos</option>
                        <option value="fas fa-utensils" {{ old('icono', $categoria->icono) == 'fas fa-utensils' ? 'selected' : '' }}>🍽️ Platos Fuertes</option>
                        <option value="fas fa-pizza-slice" {{ old('icono', $categoria->icono) == 'fas fa-pizza-slice' ? 'selected' : '' }}>🍕 Pizzas</option>
                        <option value="fas fa-ice-cream" {{ old('icono', $categoria->icono) == 'fas fa-ice-cream' ? 'selected' : '' }}>🍦 Helados</option>
                        <option value="fas fa-leaf" {{ old('icono', $categoria->icono) == 'fas fa-leaf' ? 'selected' : '' }}>🌿 Saludable / Natural</option>
                    </select>
                </div>
                @error('icono') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Descripción -->
            <div class="col-span-2">
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción (Opcional)</label>
                <textarea id="descripcion" name="descripcion" rows="3" 
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                @error('descripcion') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="pt-4 border-t border-gray-200 flex justify-end">
            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                <i class="fas fa-save mr-2 mt-0.5"></i> Actualizar Categoría
            </button>
        </div>
    </form>
</div>
@endsection
