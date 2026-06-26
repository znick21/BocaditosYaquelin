@extends('layouts.app')

@section('title', 'Nuevo Banner')
@section('header', 'Nuevo Banner Principal')
@section('subheader', 'Agrega una nueva imagen gigante al carrusel de inicio.')

@section('actions')
<a href="{{ route('sliders.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
    <i class="fas fa-arrow-left mr-2"></i> Volver
</a>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden max-w-3xl mx-auto">
    <form action="{{ route('sliders.guardar') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <!-- Título -->
            <div class="col-span-2">
                <label for="titulo" class="block text-sm font-medium text-gray-700">Título Principal <span class="text-red-500">*</span></label>
                <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                @error('titulo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Subtítulo -->
            <div class="col-span-2 sm:col-span-1">
                <label for="subtitulo" class="block text-sm font-medium text-gray-700">Subtítulo</label>
                <input type="text" name="subtitulo" id="subtitulo" value="{{ old('subtitulo') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                @error('subtitulo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Orden -->
            <div class="col-span-2 sm:col-span-1">
                <label for="orden" class="block text-sm font-medium text-gray-700">Orden de aparición <span class="text-red-500">*</span></label>
                <input type="number" name="orden" id="orden" value="{{ old('orden', 1) }}" min="0" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                @error('orden') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Imagen -->
            <div class="col-span-2">
                <label for="imagen" class="block text-sm font-medium text-gray-700">Imagen de Fondo <span class="text-red-500">*</span></label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md relative hover:bg-gray-50 transition-colors">
                    <div class="space-y-1 text-center">
                        <i class="fas fa-image text-gray-400 text-4xl mb-3"></i>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="imagen" class="relative cursor-pointer bg-white rounded-md font-medium text-amber-600 hover:text-amber-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-amber-500">
                                <span>Sube un archivo</span>
                                <input id="imagen" name="imagen" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)" required>
                            </label>
                            <p class="pl-1">o arrástralo aquí</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, WEBP hasta 3MB. Tamaño recomendado: 1920x1080px</p>
                    </div>
                    <img id="image-preview" src="#" alt="Vista previa" class="hidden absolute inset-0 w-full h-full object-cover rounded-md opacity-80">
                </div>
                @error('imagen') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Descripción -->
            <div class="col-span-2">
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción (Opcional)</label>
                <textarea id="descripcion" name="descripcion" rows="3" 
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">{{ old('descripcion') }}</textarea>
                @error('descripcion') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="pt-4 border-t border-gray-200 flex justify-end">
            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                <i class="fas fa-save mr-2 mt-0.5"></i> Guardar Banner
            </button>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('image-preview');
            output.src = reader.result;
            output.classList.remove('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
