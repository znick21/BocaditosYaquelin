@extends('layouts.app')

@section('title', 'Punto de Venta')

@push('styles')
<style>
    /* Ocultar scrollbars para diseño más limpio en el POS */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush

@section('content')

@if(!$cajaAbierta)
<div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-md shadow-sm mb-6 max-w-3xl mx-auto mt-10">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-cash-register text-red-500 text-3xl"></i>
        </div>
        <div class="ml-4">
            <h3 class="text-lg font-bold text-red-800">Caja Cerrada</h3>
            <div class="mt-2 text-red-700">
                <p>No tienes una caja abierta. Debes abrir tu turno para poder registrar ventas.</p>
            </div>
            <div class="mt-4">
                <a href="{{ route('caja.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700">
                    Ir a Gestión de Caja
                </a>
            </div>
        </div>
    </div>
</div>
@else

<div x-data="posApp()" class="flex flex-col lg:flex-row h-[calc(100vh-8rem)] gap-4 -m-4 sm:-m-6 lg:-m-8 p-4 sm:p-6 lg:p-8 bg-gray-100">
    
    <!-- Lado Izquierdo: Catálogo de Productos -->
    <div class="flex-1 flex flex-col min-w-0 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- Buscador y Categorías -->
        <div class="p-4 border-b border-gray-200 bg-white z-10">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input x-model="search" type="text" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 sm:text-sm transition-colors" placeholder="Buscar por nombre o código...">
                <button x-show="search.length > 0" @click="search = ''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
            
            <!-- Pestañas de Categorías -->
            <div class="mt-4 flex overflow-x-auto space-x-2 no-scrollbar pb-1">
                <button @click="filtroCategoria = null" :class="filtroCategoria === null ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors">
                    Todos
                </button>
                @foreach($categorias as $cat)
                    <button @click="filtroCategoria = {{ $cat->id }}" :class="filtroCategoria === {{ $cat->id }} ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors">
                        <i class="{{ $cat->icono }} mr-1"></i> {{ $cat->nombre }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Grid de Productos -->
        <div class="flex-1 overflow-y-auto p-4 bg-gray-50">
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                <template x-for="producto in productosFiltrados()" :key="producto.id">
                    <div @click="agregarAlCarrito(producto)" 
                         class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden cursor-pointer hover:shadow-md hover:border-amber-300 transition-all transform active:scale-95 flex flex-col h-40 relative group">
                        
                        <div class="absolute inset-0 bg-amber-500 opacity-0 group-hover:opacity-5 transition-opacity pointer-events-none"></div>

                        <!-- Info Producto -->
                        <div class="p-3 flex-1 flex flex-col">
                            <span x-text="producto.categoria_nombre" class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1"></span>
                            <h3 x-text="producto.nombre" class="text-sm font-bold text-gray-900 leading-tight mb-auto line-clamp-2"></h3>
                            
                            <div class="mt-2 flex justify-between items-end">
                                <span class="text-xs font-medium" :class="producto.stock <= producto.stock_minimo ? 'text-red-600' : 'text-green-600'" x-text="producto.stock + ' disp'"></span>
                                <span class="font-extrabold text-amber-600">Bs. <span x-text="formatMoney(producto.precio)"></span></span>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Mensaje cuando no hay resultados -->
                <div x-show="productosFiltrados().length === 0" class="col-span-full py-12 text-center text-gray-500" x-cloak>
                    <i class="fas fa-search text-4xl text-gray-300 mb-3"></i>
                    <p>No se encontraron productos.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lado Derecho: Carrito (Ticket) -->
    <div class="w-full lg:w-96 flex flex-col bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex-shrink-0">
        
        <!-- Header Carrito -->
        <div class="p-4 bg-gray-900 text-white flex justify-between items-center z-10">
            <h2 class="font-bold flex items-center">
                <i class="fas fa-receipt mr-2 text-amber-500"></i> Nuevo Pedido
            </h2>
            <button @click="limpiarCarrito()" x-show="carrito.length > 0" class="text-xs text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-trash-alt mr-1"></i> Limpiar
            </button>
        </div>

        <!-- Lista de Items -->
        <div class="flex-1 overflow-y-auto p-2 bg-gray-50">
            
            <div x-show="carrito.length === 0" class="h-full flex flex-col items-center justify-center text-gray-400 p-6 text-center" x-cloak>
                <i class="fas fa-shopping-cart text-5xl mb-4 text-gray-200"></i>
                <p class="text-sm">Selecciona productos del catálogo para agregarlos a la venta.</p>
            </div>

            <ul class="space-y-2">
                <template x-for="(item, index) in carrito" :key="item.producto_id">
                    <li class="bg-white p-3 rounded-lg shadow-sm border border-gray-100 flex flex-col animate-fade-in-up">
                        <div class="flex justify-between items-start mb-2">
                            <span x-text="item.nombre" class="font-bold text-gray-900 text-sm leading-tight pr-4"></span>
                            <button @click="eliminarItem(index)" class="text-gray-400 hover:text-red-500 transition-colors flex-shrink-0">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <div class="flex justify-between items-center mt-auto">
                            <!-- Controles de Cantidad -->
                            <div class="flex items-center border border-gray-200 rounded-md overflow-hidden bg-gray-50">
                                <button @click="decrementarItem(index)" class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-200 hover:text-gray-900 transition-colors">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <input type="number" x-model.number="item.cantidad" min="1" :max="item.stock" @change="validarCantidad(index)" class="w-10 h-8 text-center text-sm font-bold bg-white border-x border-gray-200 focus:outline-none focus:ring-0 p-0">
                                <button @click="incrementarItem(index)" class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-200 hover:text-gray-900 transition-colors" :disabled="item.cantidad >= item.stock" :class="{'opacity-50 cursor-not-allowed': item.cantidad >= item.stock}">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>

                            <div class="text-right">
                                <div class="text-xs text-gray-500" x-text="'Bs. ' + formatMoney(item.precio) + ' c/u'"></div>
                                <div class="font-bold text-gray-900" x-text="'Bs. ' + formatMoney(item.cantidad * item.precio)"></div>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </div>

        <!-- Totales y Cobro -->
        <div class="border-t border-gray-200 bg-white p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-10">
            
            <!-- Método de Pago -->
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Método de Pago</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach($metodosPago as $metodo)
                        <button type="button" @click="metodoPagoId = {{ $metodo->id }}" 
                            :class="metodoPagoId === {{ $metodo->id }} ? 'bg-amber-100 border-amber-500 text-amber-800 ring-1 ring-amber-500' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50'" 
                            class="border rounded-md py-2 px-1 text-center text-xs font-medium transition-all">
                            <i class="{{ $metodo->icono }} block mb-1 text-lg" :class="metodoPagoId === {{ $metodo->id }} ? 'text-amber-600' : 'text-gray-400'"></i>
                            {{ $metodo->nombre }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Resumen Totales -->
            <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span x-text="'Bs. ' + formatMoney(subtotal)"></span>
                </div>
                <!-- Opcional: Descuento -->
                <div class="flex justify-between text-sm text-gray-600 items-center">
                    <span>Descuento</span>
                    <div class="flex items-center">
                        <span class="mr-1 text-xs">Bs.</span>
                        <input type="number" x-model.number="descuento" min="0" :max="subtotal" class="w-16 text-right border-b border-gray-300 focus:outline-none focus:border-amber-500 bg-transparent py-0 px-1 text-sm">
                    </div>
                </div>
                <div class="flex justify-between text-2xl font-black text-gray-900 pt-2 border-t border-gray-200 border-dashed">
                    <span>Total</span>
                    <span class="text-amber-600" x-text="'Bs. ' + formatMoney(total)"></span>
                </div>
            </div>

            <!-- Botón Cobrar -->
            <button @click="procesarVenta()" :disabled="carrito.length === 0 || procesando" 
                class="w-full py-4 rounded-xl text-white font-bold text-lg uppercase tracking-wider flex items-center justify-center transition-all shadow-lg"
                :class="carrito.length === 0 ? 'bg-gray-300 cursor-not-allowed shadow-none' : (procesando ? 'bg-amber-400 cursor-wait' : 'bg-amber-600 hover:bg-amber-700 hover:shadow-xl active:transform active:translate-y-1')">
                <i class="fas fa-spinner fa-spin mr-2" x-show="procesando" x-cloak></i>
                <i class="fas fa-check-circle mr-2" x-show="!procesando"></i>
                <span x-text="procesando ? 'Procesando...' : 'Cobrar Bs. ' + formatMoney(total)"></span>
            </button>
        </div>
    </div>
    
    <!-- Modal Venta Exitosa -->
    <div x-show="modalExito" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalExito" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="modalExito" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                        <i class="fas fa-check text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">¡Venta Exitosa!</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 mb-2" x-text="mensajeExito"></p>
                        <div class="bg-gray-50 rounded-lg p-3 inline-block">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Cambio a entregar</p>
                            <p class="text-2xl font-bold text-gray-900">Bs. 0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <button type="button" @click="cerrarModalExito()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-amber-600 text-base font-medium text-white hover:bg-amber-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Nueva Venta
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-print mr-2 mt-1"></i> Imprimir Ticket
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Cargar Axios para peticiones HTTP desde Alpine -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script id="productos-data" type="application/json">
    {!! json_encode($productosCat) !!}
</script>
<script id="pos-config" type="application/json">
{
    "metodoPagoDefault": {!! json_encode($metodosPago->first()->id ?? null) !!},
    "rutaVenta": {!! json_encode(route('pos.venta')) !!}
}
</script>
<script>
    // Configurar Axios con token CSRF de Laravel
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const posConfig = JSON.parse(document.getElementById('pos-config').textContent);

    function posApp() {
        return {
            // Datos del servidor (inyectados desde blade)
            productosCat: JSON.parse(document.getElementById('productos-data').textContent),
            
            // Estado de la UI
            search: '',
            filtroCategoria: null,
            
            // Estado del Carrito
            carrito: [],
            metodoPagoId: posConfig.metodoPagoDefault,
            descuento: 0,
            
            // Estado del Proceso
            procesando: false,
            modalExito: false,
            mensajeExito: '',

            // Computed Properties (Funciones getter en Alpine)
            get subtotal() {
                return this.carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
            },
            
            get total() {
                let t = this.subtotal - (this.descuento || 0);
                return t > 0 ? t : 0;
            },

            productosFiltrados() {
                return this.productosCat.filter(p => {
                    const matchSearch = p.nombre.toLowerCase().includes(this.search.toLowerCase());
                    const matchCat = this.filtroCategoria === null || p.categoria_id === this.filtroCategoria;
                    return matchSearch && matchCat;
                });
            },

            // Métodos
            formatMoney(value) {
                return parseFloat(value).toFixed(2);
            },

            agregarAlCarrito(producto) {
                if(producto.stock <= 0) {
                    alert('Producto agotado');
                    return;
                }

                let index = this.carrito.findIndex(item => item.producto_id === producto.id);
                if (index > -1) {
                    if (this.carrito[index].cantidad < producto.stock) {
                        this.carrito[index].cantidad++;
                    } else {
                        alert('No hay más stock disponible');
                    }
                } else {
                    this.carrito.push({
                        producto_id: producto.id,
                        nombre: producto.nombre,
                        precio: producto.precio,
                        cantidad: 1,
                        stock: producto.stock
                    });
                }
            },

            incrementarItem(index) {
                if (this.carrito[index].cantidad < this.carrito[index].stock) {
                    this.carrito[index].cantidad++;
                }
            },

            decrementarItem(index) {
                if (this.carrito[index].cantidad > 1) {
                    this.carrito[index].cantidad--;
                } else {
                    this.eliminarItem(index);
                }
            },

            eliminarItem(index) {
                this.carrito.splice(index, 1);
            },

            validarCantidad(index) {
                let val = parseInt(this.carrito[index].cantidad);
                if (isNaN(val) || val < 1) {
                    this.carrito[index].cantidad = 1;
                } else if (val > this.carrito[index].stock) {
                    this.carrito[index].cantidad = this.carrito[index].stock;
                    alert('Cantidad ajustada al stock máximo disponible');
                } else {
                    this.carrito[index].cantidad = val;
                }
            },

            limpiarCarrito() {
                if(confirm('¿Estás seguro de limpiar el carrito?')) {
                    this.carrito = [];
                    this.descuento = 0;
                }
            },

            async procesarVenta() {
                if (this.carrito.length === 0) return;
                
                this.procesando = true;
                
                try {
                    // Mapear items para backend (solo id y cantidad)
                    const payload = {
                        items: this.carrito.map(item => ({
                            producto_id: item.producto_id,
                            cantidad: item.cantidad
                        })),
                        metodo_pago_id: this.metodoPagoId,
                        descuento: this.descuento || 0
                    };

                    const response = await axios.post(posConfig.rutaVenta, payload);
                    
                    if(response.data.exito) {
                        this.mensajeExito = response.data.mensaje;
                        this.modalExito = true;
                        
                        // Actualizar stock local en base al carrito
                        this.carrito.forEach(item => {
                            let p = this.productosCat.find(x => x.id === item.producto_id);
                            if(p) p.stock -= item.cantidad;
                        });
                        
                        this.carrito = [];
                        this.descuento = 0;
                    }
                } catch (error) {
                    let msg = error.response?.data?.error || 'Error de conexión al servidor.';
                    alert('Error: ' + msg);
                } finally {
                    this.procesando = false;
                }
            },

            cerrarModalExito() {
                this.modalExito = false;
            }
        }
    }
</script>
@endpush
@endif
@endsection
