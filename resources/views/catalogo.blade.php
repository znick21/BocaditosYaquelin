@extends('layouts.guest')

@section('title', 'Catálogo de Productos - Bocaditos Yaquelin')

@section('content')

<!-- Header del Catálogo -->
<div class="relative bg-amber-600 text-white py-16 overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-amber-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-orange-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left flex flex-col sm:flex-row justify-between items-center">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 drop-shadow-md">Nuestro Menú</h1>
            <p class="text-amber-100 text-lg max-w-2xl font-medium">Sabor tradicional con los mejores ingredientes de la región. Todo fresco y listo para disfrutar.</p>
        </div>
        <div class="mt-6 sm:mt-0">
            <a href="https://wa.me/59170000000" target="_blank" class="inline-flex items-center px-6 py-3 border-2 border-white rounded-full text-white font-bold hover:bg-white hover:text-amber-600 transition-colors shadow-lg">
                <i class="fab fa-whatsapp text-xl mr-2"></i> Pedidos Directos
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1 w-full flex flex-col lg:flex-row gap-10">
    
    <!-- Sidebar Categorías -->
    <div class="w-full lg:w-72 flex-shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
            <div class="bg-gray-900 p-4 border-b-4 border-amber-500">
                <h2 class="font-bold text-white tracking-widest text-sm uppercase flex items-center">
                    <i class="fas fa-list-ul mr-2 text-amber-500"></i> Categorías
                </h2>
            </div>
            <nav class="p-3 space-y-1">
                <a href="{{ route('catalogo') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ !$categoriaActual ? 'bg-amber-50 text-amber-600 shadow-inner' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 {{ !$categoriaActual ? 'bg-amber-500 text-white shadow-md' : 'bg-gray-100 text-gray-400' }}">
                        <i class="fas fa-th-large text-xs"></i>
                    </div>
                    Todos los Productos
                </a>
                
                @foreach($categorias as $cat)
                    <a href="{{ route('catalogo', $cat->id) }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ $categoriaActual && $categoriaActual->id == $cat->id ? 'bg-amber-50 text-amber-600 shadow-inner' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 {{ $categoriaActual && $categoriaActual->id == $cat->id ? 'bg-amber-500 text-white shadow-md' : 'bg-gray-100 text-gray-400' }}">
                            <i class="{{ $cat->icono }} text-xs"></i>
                        </div>
                        {{ $cat->nombre }}
                    </a>
                @endforeach
            </nav>
        </div>
    </div>

    <!-- Grid de Productos -->
    <div class="flex-1 overflow-hidden">
        @if(!$categoriaActual && isset($productosCarrusel) && $productosCarrusel->isNotEmpty())
        <!-- Carrusel Productos Estrella -->
        <div class="mb-12 relative" x-data="carruselPopulares()" x-init="iniciar()">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-extrabold text-gray-900 flex items-center">
                    <i class="fas fa-star text-amber-500 mr-2 text-3xl drop-shadow-sm"></i> Productos Populares
                </h2>
                <!-- Controles -->
                <div class="flex space-x-3">
                    <button @click="prev()" @mouseenter="pausar()" @mouseleave="iniciar()" class="w-12 h-12 rounded-full bg-white border border-gray-200 shadow hover:shadow-md flex items-center justify-center text-gray-600 hover:text-amber-600 hover:border-amber-300 transition-all focus:outline-none">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button @click="next()" @mouseenter="pausar()" @mouseleave="iniciar()" class="w-12 h-12 rounded-full bg-white border border-gray-200 shadow hover:shadow-md flex items-center justify-center text-gray-600 hover:text-amber-600 hover:border-amber-300 transition-all focus:outline-none">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Contenedor del Carrusel -->
            <div class="overflow-hidden w-full relative pb-4" x-ref="contenedor">
                <div class="flex gap-6 transition-transform duration-500 ease-out" 
                     :style="`transform: translateX(-${desplazamiento}px)`"
                     @mouseenter="pausar()" @mouseleave="iniciar()">
                     
                    @foreach($productosCarrusel as $producto)
                    <a href="#prod-{{ $producto->id }}" class="min-w-[280px] w-[280px] bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-xl hover:border-amber-300 shrink-0 group relative block transition-all">
                        <div class="absolute top-3 left-3 z-20 bg-amber-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-md flex items-center">
                            <i class="fas fa-fire mr-1 text-yellow-200"></i> Top Ventas
                        </div>
                        <div class="aspect-[4/3] relative overflow-hidden bg-gray-50 flex items-center justify-center">
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <i class="fas fa-utensils text-5xl text-amber-300 group-hover:scale-110 transition-transform duration-700"></i>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
                            
                            <!-- Contenido superpuesto en la imagen -->
                            <div class="absolute bottom-0 left-0 right-0 p-5 text-white z-10 transform translate-y-2 group-hover:translate-y-0 transition-transform">
                                <h3 class="text-xl font-bold leading-tight line-clamp-1 text-white shadow-black drop-shadow-md">{{ $producto->nombre }}</h3>
                                <p class="text-amber-400 font-extrabold mt-1 text-lg drop-shadow-md">Bs. {{ number_format($producto->precio, 2) }}</p>
                            </div>
                        </div>
                    </a>
                    @endforeach

                </div>
            </div>
            
            <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('carruselPopulares', () => ({
                    indice: 0,
                    total: {{ $productosCarrusel->count() }},
                    anchoItem: 280,
                    gap: 24, // 1.5rem (gap-6)
                    intervalo: null,
                    visibleItems: 1,
                    
                    get desplazamiento() {
                        return this.indice * (this.anchoItem + this.gap);
                    },
                    
                    iniciar() {
                        this.calcularVisibles();
                        window.addEventListener('resize', () => this.calcularVisibles());
                        
                        this.intervalo = setInterval(() => {
                            this.next();
                        }, 4000);
                    },
                    
                    pausar() {
                        clearInterval(this.intervalo);
                    },
                    
                    calcularVisibles() {
                        if (!this.$refs.contenedor) return;
                        const anchoContenedor = this.$refs.contenedor.offsetWidth;
                        this.visibleItems = Math.floor(anchoContenedor / (this.anchoItem + this.gap));
                        if(this.visibleItems < 1) this.visibleItems = 1;
                    },
                    
                    next() {
                        if (this.indice < this.total - this.visibleItems) {
                            this.indice++;
                        } else {
                            this.indice = 0;
                        }
                    },
                    
                    prev() {
                        if (this.indice > 0) {
                            this.indice--;
                        } else {
                            this.indice = Math.max(0, this.total - this.visibleItems);
                        }
                    }
                }))
            })
            </script>
        </div>
        @endif

        <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between border-b border-gray-200 pb-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900">
                    {{ $categoriaActual ? $categoriaActual->nombre : 'Todos los Productos' }}
                </h2>
                <p class="text-gray-500 mt-2 font-medium">
                    {{ $categoriaActual && $categoriaActual->descripcion ? $categoriaActual->descripcion : 'Explora nuestra selección completa de sabores únicos.' }}
                </p>
            </div>
            <div class="mt-4 sm:mt-0 text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-semibold">
                {{ $productos->count() }} productos
            </div>
        </div>

        @if($productos->isEmpty())
            <div class="bg-white rounded-3xl border border-gray-100 p-16 text-center shadow-sm">
                <div class="mx-auto w-24 h-24 bg-amber-50 rounded-full flex items-center justify-center text-amber-300 mb-6 text-4xl shadow-inner">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No hay productos disponibles</h3>
                <p class="text-gray-500 max-w-md mx-auto">Actualmente no tenemos productos en esta categoría o están siendo actualizados. Por favor revisa más tarde.</p>
                <a href="{{ route('catalogo') }}" class="mt-6 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-amber-700 bg-amber-100 hover:bg-amber-200 transition-colors">
                    Ver todo el menú
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($productos as $producto)
                    <div id="prod-{{ $producto->id }}" class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 group flex flex-col relative">
                        
                        <!-- Etiqueta de Precio Flotante -->
                        <div class="absolute top-4 right-4 z-20 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-full shadow-lg font-black text-amber-600 border border-white/50">
                            Bs. {{ number_format($producto->precio, 2) }}
                        </div>

                        <!-- Etiqueta Agotado -->
                        @if($producto->estaAgotado())
                            <div class="absolute inset-0 z-30 bg-white/60 backdrop-blur-sm flex items-center justify-center">
                                <span class="bg-red-600 text-white font-black px-6 py-3 rounded-xl shadow-2xl transform -rotate-6 text-lg tracking-widest border-2 border-red-800">¡AGOTADO!</span>
                            </div>
                        @endif

                        <!-- Imagen o Placeholder con Gradiente -->
                        <div class="aspect-[4/3] relative overflow-hidden flex items-center justify-center bg-gradient-to-br from-amber-50 to-orange-100">
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <!-- Placeholder Premium cuando no hay imagen -->
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-amber-600/20 group-hover:scale-110 transition-transform duration-700 group-hover:text-amber-600/30">
                                    <i class="fas fa-utensils text-7xl mb-2 drop-shadow-md"></i>
                                    <span class="font-black tracking-widest uppercase text-sm">Yakelin</span>
                                </div>
                            @endif
                            <!-- Sombra interior abajo -->
                            <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-black/50 to-transparent"></div>
                        </div>
                        
                        <!-- Contenido -->
                        <div class="p-6 flex-1 flex flex-col bg-white relative z-10 -mt-4 rounded-t-3xl">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-bold text-gray-900 leading-tight group-hover:text-amber-600 transition-colors">{{ $producto->nombre }}</h3>
                            </div>
                            
                            @if($producto->descripcion)
                                <p class="text-sm text-gray-500 mb-6 line-clamp-2 flex-1">{{ $producto->descripcion }}</p>
                            @else
                                <div class="flex-1 mb-6"></div>
                            @endif
                            
                            <!-- Acción -->
                            <div class="mt-auto">
                                <a href="https://wa.me/59170000000?text=Hola,%20quisiera%20pedir:%20{{ urlencode($producto->nombre) }}" target="_blank" 
                                   class="w-full flex items-center justify-center px-4 py-3 border border-transparent text-sm font-bold rounded-xl text-green-700 bg-green-50 hover:bg-green-500 hover:text-white transition-all duration-300">
                                    <i class="fab fa-whatsapp text-lg mr-2"></i> Pedir esto
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection
