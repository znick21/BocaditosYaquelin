@extends('layouts.app')

@section('title', 'Configuración del Sistema')
@section('header', 'Configuración General')
@section('subheader', 'Personaliza la identidad y parámetros de tu negocio.')

@section('content')

<form action="{{ route('configuracion.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-5xl">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Identidad -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex items-center">
                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mr-3">
                    <i class="fas fa-store"></i>
                </div>
                <h3 class="text-lg leading-6 font-bold text-gray-900">Identidad del Negocio</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre del Negocio <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre_negocio" value="{{ old('nombre_negocio', $config->nombre_negocio) }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Eslogan</label>
                    <input type="text" name="eslogan" value="{{ old('eslogan', $config->eslogan) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                </div>
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Logo (Recomendado: png transparente)</label>
                        <input type="file" name="logo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    </div>
                    @if($config->logo)
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200 overflow-hidden shrink-0 mt-6">
                        <img src="{{ asset('storage/' . $config->logo) }}" class="max-w-full max-h-full object-contain">
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contacto -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex items-center">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3">
                    <i class="fas fa-address-book"></i>
                </div>
                <h3 class="text-lg leading-6 font-bold text-gray-900">Contacto y Ubicación</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Teléfono Fijo</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $config->telefono) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm font-bold">
                                +
                            </span>
                            <input type="text" name="codigo_pais" value="{{ old('codigo_pais', $config->codigo_pais ?? '591') }}" placeholder="591" class="w-16 block border border-r-0 border-gray-300 focus:ring-amber-500 focus:border-amber-500 sm:text-sm py-2 px-2 text-center" required>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $config->whatsapp) }}" placeholder="Ej: 79007680" class="flex-1 block w-full border border-gray-300 rounded-none rounded-r-md focus:ring-amber-500 focus:border-amber-500 sm:text-sm py-2 px-3">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Código de país (ej. 591) y el número sin espacios.</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $config->email) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $config->direccion) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                </div>
            </div>
        </div>

        <!-- Parámetros de Operación -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex items-center">
                <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-3">
                    <i class="fas fa-sliders-h"></i>
                </div>
                <h3 class="text-lg leading-6 font-bold text-gray-900">Parámetros Operativos</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Moneda (Símbolo)</label>
                        <input type="text" name="moneda" value="{{ old('moneda', $config->moneda ?? 'Bs') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Impuesto (%)</label>
                        <input type="number" step="0.01" name="impuesto_porcentaje" value="{{ old('impuesto_porcentaje', $config->impuesto_porcentaje ?? '0') }}" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hora Apertura (Público)</label>
                        <input type="time" name="horario_apertura" value="{{ old('horario_apertura', $config->horario_apertura) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hora Cierre (Público)</label>
                        <input type="time" name="horario_cierre" value="{{ old('horario_cierre', $config->horario_cierre) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Apariencia -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex items-center">
                <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mr-3">
                    <i class="fas fa-palette"></i>
                </div>
                <h3 class="text-lg leading-6 font-bold text-gray-900">Apariencia de la Tienda</h3>
            </div>
            <div class="p-6 space-y-4">
                <p class="text-xs text-gray-500 mb-2">Estos colores definirán la identidad visual de la tienda pública (Landing page).</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Color Primario</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input type="color" name="color_primario" value="{{ old('color_primario', $config->color_primario ?? '#f59e0b') }}" class="h-10 w-14 p-1 rounded border border-gray-300 cursor-pointer">
                            <span class="text-sm font-mono text-gray-500">{{ old('color_primario', $config->color_primario ?? '#f59e0b') }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Color Secundario</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input type="color" name="color_secundario" value="{{ old('color_secundario', $config->color_secundario ?? '#ea580c') }}" class="h-10 w-14 p-1 rounded border border-gray-300 cursor-pointer">
                            <span class="text-sm font-mono text-gray-500">{{ old('color_secundario', $config->color_secundario ?? '#ea580c') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Redes Sociales Dinámicas (Alpine.js) -->
        <div class="md:col-span-2 bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden" 
             x-data="redesSocialesManager()">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center mr-3">
                        <i class="fas fa-hashtag"></i>
                    </div>
                    <h3 class="text-lg leading-6 font-bold text-gray-900">Redes Sociales</h3>
                </div>
                <button type="button" @click="addRed()" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-pink-600 hover:bg-pink-700 focus:outline-none">
                    <i class="fas fa-plus mr-1"></i> Añadir Red
                </button>
            </div>
            
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-4" x-show="redes.length === 0">No tienes redes sociales configuradas. Haz clic en "Añadir Red" para agregar tu perfil de Facebook, Instagram, TikTok u otros.</p>
                
                <div class="space-y-3">
                    <template x-for="(red, index) in redes" :key="red.id">
                        <div class="flex items-start gap-3 bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <div class="w-1/3">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Plataforma</label>
                                <select x-model="red.red" :name="`redes_sociales[${index}][red]`" class="block w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                                    <option value="facebook">Facebook</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="tiktok">TikTok</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="twitter">X (Twitter)</option>
                                    <option value="linkedin">LinkedIn</option>
                                    <option value="website">Página Web</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Enlace / URL completa</label>
                                <input type="url" x-model="red.url" :name="`redes_sociales[${index}][url]`" placeholder="https://..." required class="block w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                            </div>
                            <div class="pt-5">
                                <button type="button" @click="removeRed(index)" class="text-red-500 hover:text-red-700 bg-white p-2 rounded-md shadow-sm border border-red-100 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 px-6 py-4 rounded-xl border border-gray-200 flex justify-end">
        <button type="submit" class="inline-flex justify-center rounded-lg border border-transparent shadow-md px-6 py-3 bg-amber-600 text-base font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
            <i class="fas fa-save mr-2 mt-1"></i> Guardar Configuración
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('redesSocialesManager', () => ({
            redes: @json(old('redes_sociales', $config->redes_sociales ?? [])),
            
            init() {
                // Si la BD guardó un array pero no está en formato correcto, o está vacío
                if (!Array.isArray(this.redes)) {
                    this.redes = [];
                }
                
                // Asegurar que cada red tenga un ID único para Alpine
                this.redes = this.redes.map(r => ({...r, id: Date.now() + Math.random()}));
            },
            
            addRed() {
                this.redes.push({
                    id: Date.now(),
                    red: 'facebook',
                    url: ''
                });
            },
            
            removeRed(index) {
                this.redes.splice(index, 1);
            }
        }));
    });
</script>
@endpush
