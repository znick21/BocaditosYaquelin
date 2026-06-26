@extends('layouts.app')

@section('title', 'Nuevo Producto')
@section('header', 'Nuevo Producto')
@section('subheader', 'Agrega un nuevo producto al inventario y menú.')

@section('actions')
<a href="{{ route('productos.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
    <i class="fas fa-arrow-left mr-2"></i> Volver
</a>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden max-w-4xl mx-auto">
    <form action="{{ route('productos.guardar') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-y-6 gap-x-6 sm:grid-cols-6">
            <!-- Nombre -->
            <div class="sm:col-span-4">
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre del Producto <span class="text-red-500">*</span></label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                @error('nombre') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Categoría -->
            <div class="sm:col-span-2">
                <label for="categoria_id" class="block text-sm font-medium text-gray-700">Categoría <span class="text-red-500">*</span></label>
                <select id="categoria_id" name="categoria_id" required class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                    <option value="">Seleccione...</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                    @endforeach
                </select>
                @error('categoria_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Precio -->
            <div class="sm:col-span-2">
                <label for="precio" class="block text-sm font-medium text-gray-700">Precio de Venta (Bs.) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" min="0" name="precio" id="precio" value="{{ old('precio') }}" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm font-bold text-amber-600">
                @error('precio') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Costo -->
            <div class="sm:col-span-2">
                <label for="costo" class="block text-sm font-medium text-gray-700">Costo Base (Bs.)</label>
                <input type="number" step="0.01" min="0" name="costo" id="costo" value="{{ old('costo') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                <p class="mt-1 text-xs text-gray-500">Para calcular márgenes.</p>
                @error('costo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-6 border-t border-gray-200 pb-2"></div>



            <!-- Stock Mínimo -->
            <div class="sm:col-span-2">
                <label for="stock_minimo" class="block text-sm font-medium text-gray-700">Alerta de Stock Mínimo <span class="text-red-500">*</span></label>
                <input type="number" name="stock_minimo" id="stock_minimo" value="{{ old('stock_minimo', 5) }}" required min="0"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm text-red-600">
                @error('stock_minimo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Es Perecedero Checkbox -->
            <div class="sm:col-span-4 mt-2">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="es_perecedero" name="es_perecedero" type="checkbox" value="1" {{ old('es_perecedero', true) ? 'checked' : '' }} class="focus:ring-amber-500 h-4 w-4 text-amber-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="es_perecedero" class="font-medium text-gray-700">Producto Perecedero</label>
                        <p class="text-gray-500">Desmarca esto si el producto no vence (ej. Sodas).</p>
                    </div>
                </div>
            </div>

            <!-- Días de Duración (Caducidad) -->
            <div class="sm:col-span-2" id="div_dias_duracion">
                <label for="dias_duracion" class="block text-sm font-medium text-gray-700">Días de Caducidad <span class="text-red-500">*</span></label>
                <input type="number" name="dias_duracion" id="dias_duracion" value="{{ old('dias_duracion', 1) }}" min="1"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm text-blue-600">
                @error('dias_duracion') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-6 border-t border-gray-200 pb-2"></div>

            <!-- Descripción -->
            <div class="sm:col-span-6">
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción Corta</label>
                <textarea id="descripcion" name="descripcion" rows="3" 
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">{{ old('descripcion') }}</textarea>
                @error('descripcion') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Imagen -->
            <div class="sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Foto del Producto</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <i class="fas fa-image text-gray-400 text-3xl mb-3"></i>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="imagen" class="relative cursor-pointer bg-white rounded-md font-medium text-amber-600 hover:text-amber-500 focus-within:outline-none">
                                <span>Subir un archivo</span>
                                <input id="imagen" name="imagen" type="file" class="sr-only" accept="image/*">
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG hasta 2MB</p>
                    </div>
                </div>
                @error('imagen') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="pt-4 border-t border-gray-200 flex justify-end">
            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                <i class="fas fa-save mr-2 mt-0.5"></i> Guardar Producto
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('es_perecedero');
        const divDuracion = document.getElementById('div_dias_duracion');
        
        function toggleDuracion() {
            if (checkbox.checked) {
                divDuracion.style.display = 'block';
            } else {
                divDuracion.style.display = 'none';
            }
        }
        
        if (checkbox && divDuracion) {
            checkbox.addEventListener('change', toggleDuracion);
            toggleDuracion();
        }
    });
</script>
@endsection
