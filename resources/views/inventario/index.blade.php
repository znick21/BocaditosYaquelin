@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 font-bold">Planilla Diaria de Inventario ✨</h1>
            <p class="text-sm text-gray-500 mt-1">Ingreso masivo y rápido de producción y bajas (mermas).</p>
            <p class="text-md font-medium text-amber-600 mt-2"><i class="fas fa-calendar-alt mr-1"></i> {{ ucfirst(\Carbon\Carbon::now()->translatedFormat('l, d \d\e F \d\e Y')) }}</p>
        </div>
        <div class="flex items-center gap-4 text-sm text-gray-500">
            <span><i class="fas fa-magic text-amber-500"></i> Sugerencias basadas en los últimos 7 días</span>
            <a href="{{ route('inventario.historial') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                <i class="fas fa-history mr-2"></i> Ver Historial
            </a>
        </div>
    </div>

    <!-- Mensajes de Alerta -->
    @if(session('success'))
        <div class="mb-4 bg-green-50 text-green-600 p-4 rounded-lg flex items-center border border-green-100">
            <i class="fas fa-check-circle mr-3"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 text-red-600 p-4 rounded-lg flex items-center border border-red-100">
            <i class="fas fa-exclamation-circle mr-3"></i> {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 bg-red-50 text-red-600 p-4 rounded-lg flex items-center border border-red-100">
            <i class="fas fa-exclamation-circle mr-3"></i> {{ $errors->first() }}
        </div>
    @endif

    <div class="mb-4">
        <div class="relative rounded-md shadow-sm max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" id="buscadorInventario" class="focus:ring-amber-500 focus:border-amber-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 border" placeholder="Buscar por código o nombre del producto...">
        </div>
    </div>

    <form id="formPlanilla" action="{{ route('inventario.planilla') }}" method="POST">
        @csrf
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left" id="tablaPlanilla">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 w-16">Código</th>
                            <th class="px-6 py-3 w-1/4">Producto</th>
                            <th class="px-6 py-3 text-center border-l border-gray-200">Stock Actual</th>
                            <th class="px-6 py-3 text-center bg-amber-50 text-amber-800">Sugerencia (Basado en Ventas)</th>
                            <th class="px-6 py-3 text-center border-l-2 border-gray-300">Ingreso / Producción (+)<br><span class="text-[10px] font-normal lowercase">¿Cuánto horneaste o compraste?</span></th>
                            <th class="px-6 py-3 text-center border-l border-gray-200">Merma / Baja (-)<br><span class="text-[10px] font-normal lowercase">¿Cuánto vas a botar?</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($productos as $producto)
                            @if($producto->is_active)
                                @php
                                    $sug = $sugerencias[$producto->id];
                                    $faltaProducir = max(0, $sug['sugerencia_hoy'] - $producto->stock);
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-mono text-gray-500 text-xs">
                                        {{ $producto->codigo }}
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900 flex items-center">
                                        @if($producto->imagen)
                                            <img src="{{ Storage::url($producto->imagen) }}" class="w-8 h-8 rounded object-cover mr-3" alt="{{ $producto->nombre }}">
                                        @else
                                            <div class="w-8 h-8 rounded bg-gray-200 mr-3 flex items-center justify-center text-gray-400"><i class="fas fa-cookie"></i></div>
                                        @endif
                                        <div>
                                            <div class="text-sm">{{ $producto->nombre }}</div>
                                            @if($producto->dias_duracion == 0)
                                                <span class="text-[10px] text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded font-bold uppercase tracking-wider mt-1 inline-block">No perecedero</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center border-l border-gray-200">
                                        <span class="px-2 py-1 rounded text-xs font-bold {{ $producto->stock <= $producto->stock_minimo ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $producto->stock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center bg-amber-50">
                                        <span class="text-lg font-bold {{ $faltaProducir > 0 ? 'text-amber-600' : 'text-gray-400' }}">
                                            {{ $faltaProducir > 0 ? '+ '.$faltaProducir : 'Suficiente' }}
                                        </span>
                                    </td>
                                    <!-- Campos de Input -->
                                    <td class="px-6 py-2 border-l-2 border-gray-300">
                                        <input type="number" name="produccion[{{ $producto->id }}]" min="1" class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-amber-500 focus:ring-amber-500" placeholder="0">
                                    </td>
                                    <td class="px-6 py-2 border-l border-gray-200">
                                        <input type="number" name="merma[{{ $producto->id }}]" min="1" max="{{ $producto->stock }}" class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="0" {{ $producto->stock <= 0 ? 'disabled' : '' }}>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end sticky bottom-4 z-10">
            <button type="button" onclick="confirmarPlanilla()" class="bg-gray-900 hover:bg-black text-white font-bold py-4 px-10 rounded-xl shadow-xl transition-transform transform hover:scale-105 flex items-center gap-2 text-lg">
                <i class="fas fa-save"></i> Guardar Planilla del Día
            </button>
        </div>
    </form>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buscador = document.getElementById('buscadorInventario');
        const filas = document.querySelectorAll('#tablaPlanilla tbody tr');

        if(buscador) {
            buscador.addEventListener('input', function(e) {
                const termino = e.target.value.toLowerCase();

                filas.forEach(fila => {
                    const codigo = fila.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    const nombre = fila.querySelector('td:nth-child(2) .text-sm').textContent.toLowerCase();

                    if (codigo.includes(termino) || nombre.includes(termino)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        }
    });

    function confirmarPlanilla() {
        // Contar cuántos inputs tienen valor
        let tieneProduccion = false;
        let tieneMerma = false;

        document.querySelectorAll('input[name^="produccion"]').forEach(input => {
            if (input.value && input.value > 0) tieneProduccion = true;
        });
        document.querySelectorAll('input[name^="merma"]').forEach(input => {
            if (input.value && input.value > 0) tieneMerma = true;
        });

        if (!tieneProduccion && !tieneMerma) {
            Swal.fire({
                icon: 'warning',
                title: 'Planilla Vacía',
                text: 'No has ingresado ninguna cantidad de producción ni de merma.',
                confirmButtonColor: '#d33'
            });
            return;
        }

        Swal.fire({
            title: '¿Guardar Planilla del Día?',
            text: "Se registrarán los ingresos de producción y las bajas por merma en el historial de inventario. Esta acción actualizará el stock de los productos afectados.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b', // amber-500
            cancelButtonColor: '#6b7280', // gray-500
            confirmButtonText: '<i class="fas fa-save"></i> Sí, guardar planilla',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formPlanilla').submit();
            }
        });
    }
</script>
@endsection
