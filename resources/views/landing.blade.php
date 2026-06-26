@extends('layouts.guest')

@section('content')

<!-- Hero Section / Slider -->
<div class="relative bg-gray-900 h-[60vh] min-h-[500px]" x-data="{ currentSlide: 0, slides: {{ $sliders->count() }} }" x-init="setInterval(() => { currentSlide = currentSlide === slides - 1 ? 0 : currentSlide + 1 }, 5000)">
    @foreach($sliders as $index => $slider)
        <div class="absolute inset-0 transition-opacity duration-1000" x-show="currentSlide === {{ $index }}" x-cloak>
            <!-- Background Image -->
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . ($slider->imagen ?? 'productos/empanada.png')) }}');">
                <div class="absolute inset-0 bg-black bg-opacity-60"></div>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                <div class="text-white max-w-2xl">
                    <span class="inline-block py-1 px-3 rounded-full bg-amber-500/20 text-amber-400 text-sm font-semibold tracking-wider mb-4 border border-amber-500/30">
                        {{ $slider->subtitulo }}
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-4 leading-tight">
                        {{ $slider->titulo }}
                    </h1>
                    <p class="text-lg md:text-xl text-gray-300 mb-8 max-w-xl">
                        {{ $slider->descripcion }}
                    </p>
                    @if($slider->texto_boton)
                        <a href="{{ $slider->enlace_boton }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-gray-900 bg-amber-500 hover:bg-amber-400 transition-colors shadow-lg hover:shadow-xl">
                            {{ $slider->texto_boton }} <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

    <!-- Slider Controls -->
    <div class="absolute bottom-6 left-0 right-0 flex justify-center space-x-3 z-10">
        @foreach($sliders as $index => $slider)
            <button @click="currentSlide = {{ $index }}" class="w-3 h-3 rounded-full transition-all focus:outline-none" :class="currentSlide === {{ $index }} ? 'bg-amber-500 w-8' : 'bg-white/50 hover:bg-white'"></button>
        @endforeach
    </div>
</div>

<!-- Features Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">¿Por qué elegirnos?</h2>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">Ingredientes frescos y recetas tradicionales en cada bocado.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-gray-50 rounded-2xl p-8 text-center border border-gray-100 transition-transform hover:-translate-y-1 hover:shadow-lg">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 text-amber-600 mb-6 text-2xl">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Siempre Frescos</h3>
                <p class="text-gray-600">Nuestros bocaditos se preparan al instante. Calientitos, crujientes y listos para disfrutar en cualquier momento del día.</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="bg-gray-50 rounded-2xl p-8 text-center border border-gray-100 transition-transform hover:-translate-y-1 hover:shadow-lg">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 text-amber-600 mb-6 text-2xl">
                    <i class="fas fa-heart"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Sabor Tradicional</h3>
                <p class="text-gray-600">Mantenemos vivas las recetas cruceñas. El verdadero sabor del cuñapé, masaco y empanadas hechas con amor.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-gray-50 rounded-2xl p-8 text-center border border-gray-100 transition-transform hover:-translate-y-1 hover:shadow-lg">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 text-amber-600 mb-6 text-2xl">
                    <i class="fas fa-motorcycle"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Rápida Atención</h3>
                <p class="text-gray-600">Sabemos que tu tiempo vale oro. Contamos con un sistema de atención ágil para que no hagas largas filas.</p>
            </div>
        </div>
    </div>
</div>

<!-- Highlighted Categories -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Nuestro Menú</h2>
                <p class="mt-2 text-lg text-gray-500">Explora la variedad de nuestros productos.</p>
            </div>
            <a href="{{ route('catalogo') }}" class="hidden sm:inline-flex items-center font-medium text-amber-600 hover:text-amber-500">
                Ver catálogo completo <i class="fas fa-arrow-right ml-2 text-sm"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categorias as $categoria)
                <a href="{{ route('catalogo', $categoria->id) }}" class="group relative rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-xl transition-all border border-gray-200 aspect-[4/3] flex flex-col justify-end p-6">
                    <!-- Decoración visual (reemplazar con imagen real en produccion) -->
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent z-10"></div>
                    
                    @php
                        $catImage = $categoria->productos()->whereNotNull('imagen')->first();
                        $catImageUrl = $catImage ? asset('storage/' . $catImage->imagen) : asset('storage/productos/huminta_v2.png');
                    @endphp
                    <img src="{{ $catImageUrl }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $categoria->nombre }}">

                    <div class="relative z-20">
                        <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center text-white text-xl mb-4 shadow-lg transform transition-transform group-hover:-translate-y-2">
                            <i class="{{ $categoria->icono ?: 'fas fa-star' }}"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-1">{{ $categoria->nombre }}</h3>
                        <p class="text-gray-300 text-sm">{{ $categoria->productos_activos_count }} productos disponibles</p>
                    </div>
                </a>
            @endforeach
        </div>
        
        <div class="mt-8 text-center sm:hidden">
            <a href="{{ route('catalogo') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-amber-700 bg-amber-100 hover:bg-amber-200">
                Ver catálogo completo
            </a>
        </div>
    </div>
</div>

<!-- Destacados -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Los Favoritos</h2>
            <p class="mt-4 text-lg text-gray-500">Los bocaditos que no pueden faltar en tu mesa.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($productosDestacados as $producto)
                <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-lg transition-all group flex flex-col h-full">
                    <div class="aspect-square bg-gray-50 rounded-xl mb-4 flex items-center justify-center text-gray-400 overflow-hidden relative">
                        @if($producto->imagen)
                            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <i class="fas fa-utensils text-4xl group-hover:scale-110 transition-transform"></i>
                        @endif
                        <div class="absolute top-2 right-2 bg-white px-2 py-1 rounded-md shadow-sm text-xs font-bold text-amber-600">
                            Bs. {{ number_format($producto->precio, 2) }}
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col">
                        <span class="text-xs font-semibold text-amber-500 uppercase tracking-wider mb-1">{{ $producto->categoria->nombre }}</span>
                        <h3 class="text-lg font-bold text-gray-900 leading-tight mb-2 flex-1">{{ $producto->nombre }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-amber-600">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
        <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
            <span class="block">¿Listo para hacer tu pedido?</span>
            <span class="block text-amber-200 text-2xl mt-1">Visítanos o contáctanos por WhatsApp.</span>
        </h2>
        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
            <div class="inline-flex rounded-md shadow">
                <a href="#" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-full text-amber-600 bg-white hover:bg-gray-50">
                    <i class="fab fa-whatsapp text-green-500 mr-2 text-xl"></i> Hablar por WhatsApp
                </a>
            </div>
            <div class="ml-3 inline-flex rounded-md shadow">
                <a href="{{ route('catalogo') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-full text-white bg-amber-700 hover:bg-amber-800">
                    Ver Menú
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
