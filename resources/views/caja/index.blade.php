@extends('layouts.app')

@section('title', 'Gestión de Caja')
@section('header', 'Gestión de Caja')
@section('subheader', 'Control de apertura, cierre y arqueo de caja por turnos.')

@section('content')

@if($cajaAbierta)
    <!-- Caja Abierta -->
    <div class="bg-white rounded-xl shadow-sm border border-green-200 overflow-hidden mb-8 relative">
        <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-bl-full -z-10"></div>
        
        <div class="p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 z-10">
            <div>
                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mb-4">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span> Caja Abierta
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Tu turno está activo</h2>
                <p class="mt-1 text-gray-500">Abierta el {{ $cajaAbierta->fecha_apertura->format('d/m/Y a las h:i A') }}</p>
                
                <div class="mt-6 flex flex-wrap gap-4">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 min-w-[150px]">
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Monto Inicial</span>
                        <span class="text-xl font-bold text-gray-900">Bs. {{ number_format($cajaAbierta->monto_apertura, 2) }}</span>
                    </div>
                    <div class="bg-amber-50 rounded-lg p-4 border border-amber-100 min-w-[150px]">
                        <span class="block text-xs font-semibold text-amber-700 uppercase tracking-wider mb-1">Ventas Efectivo</span>
                        <span class="text-xl font-bold text-amber-600">+ Bs. {{ number_format($cajaAbierta->totalVentasEfectivo(), 2) }}</span>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 border border-green-100 min-w-[150px]">
                        <span class="block text-xs font-semibold text-green-700 uppercase tracking-wider mb-1">Total Esperado</span>
                        <span class="text-xl font-bold text-green-700">Bs. {{ number_format($cajaAbierta->monto_apertura + $cajaAbierta->totalVentasEfectivo(), 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="md:text-right" x-data="{ modalCierre: false }">
                <button @click="modalCierre = true" class="w-full md:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-red-600 hover:bg-red-700 shadow-md transition-all hover:shadow-lg">
                    <i class="fas fa-lock mr-2"></i> Cerrar Caja
                </button>
                <a href="{{ route('pos.index') }}" class="mt-3 w-full md:w-auto inline-flex items-center justify-center px-6 py-3 border border-amber-600 text-base font-medium rounded-xl text-amber-700 bg-amber-50 hover:bg-amber-100 transition-colors">
                    <i class="fas fa-cash-register mr-2"></i> Ir al POS
                </a>

                <!-- Modal Cierre de Caja -->
                <div x-show="modalCierre" class="fixed inset-0 z-50 overflow-y-auto text-left" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="modalCierre" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="modalCierre = false" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div x-show="modalCierre" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form action="{{ route('caja.cerrar', $cajaAbierta) }}" method="POST">
                                @csrf
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <i class="fas fa-lock text-red-600"></i>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Arqueo y Cierre de Caja</h3>
                                            <div class="mt-4 space-y-4">
                                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 flex justify-between items-center">
                                                    <span class="text-sm font-medium text-gray-500">Monto Esperado (Sistema):</span>
                                                    <span class="text-lg font-bold text-gray-900">Bs. {{ number_format($cajaAbierta->monto_apertura + $cajaAbierta->totalVentasEfectivo(), 2) }}</span>
                                                </div>
                                                
                                                <div>
                                                    <label for="monto_cierre" class="block text-sm font-medium text-gray-700">Efectivo Físico en Caja (Bs.) <span class="text-red-500">*</span></label>
                                                    <p class="text-xs text-gray-500 mb-2">Cuenta el dinero en tu gaveta e ingrésalo aquí.</p>
                                                    <input type="number" step="0.01" min="0" name="monto_cierre" id="monto_cierre" required
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-3 px-4 focus:outline-none focus:ring-red-500 focus:border-red-500 font-bold text-xl text-center">
                                                </div>

                                                <div>
                                                    <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones (Opcional)</label>
                                                    <textarea id="observaciones" name="observaciones" rows="2" 
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Ej: Faltan 5 Bs que no se encontraron..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                                    <button type="submit" onclick="return confirm('Al cerrar la caja ya no podrás registrar más ventas hasta abrir un nuevo turno. ¿Estás seguro?')" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                        Confirmar Cierre
                                    </button>
                                    <button type="button" @click="modalCierre = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Resumen ventas del turno actual -->
        @if($ventasCaja && $ventasCaja->count() > 0)
        <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">
            <h4 class="text-sm font-bold text-gray-900 mb-3">Ventas registradas en este turno ({{ $ventasCaja->count() }})</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ticket</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Hora</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Método</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($ventasCaja->take(5) as $venta)
                        <tr>
                            <td class="px-3 py-2 font-medium text-gray-900">{{ $venta->numero_venta }}</td>
                            <td class="px-3 py-2 text-gray-500">{{ $venta->created_at->format('H:i') }}</td>
                            <td class="px-3 py-2 text-gray-500"><i class="{{ $venta->metodoPago->icono }}"></i> {{ $venta->metodoPago->nombre }}</td>
                            <td class="px-3 py-2 text-right font-bold text-gray-900">Bs. {{ number_format($venta->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($ventasCaja->count() > 5)
                    <div class="mt-2 text-center text-xs text-gray-500">
                        Mostrando las últimas 5 ventas de un total de {{ $ventasCaja->count() }}.
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

@else
    <!-- Abrir Caja -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8 max-w-2xl mx-auto">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 text-center">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 mb-4">
                <i class="fas fa-key text-2xl text-amber-600"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900">Apertura de Caja</h2>
            <p class="text-gray-500 text-sm mt-1">Inicia tu turno de trabajo registrando el fondo inicial de la gaveta.</p>
        </div>
        
        <form action="{{ route('caja.abrir') }}" method="POST" class="p-6 sm:p-8">
            @csrf
            <div class="mb-6">
                <label for="monto_apertura" class="block text-sm font-medium text-gray-700 text-center mb-2">Monto Inicial en Efectivo (Sencillo) <span class="text-red-500">*</span></label>
                <div class="relative max-w-xs mx-auto">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm font-bold">Bs.</span>
                    </div>
                    <input type="number" step="0.01" min="0" name="monto_apertura" id="monto_apertura" value="0.00" required
                        class="block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-lg text-2xl text-center font-bold focus:ring-amber-500 focus:border-amber-500">
                </div>
                @error('monto_apertura') <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-base font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                Abrir Caja e Iniciar Turno
            </button>
        </form>
    </div>
@endif

<!-- Historial de Cajas -->
<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Historial de Turnos y Arqueos</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Apertura</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Apertura</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Esperado</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cierre Real</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Diferencia</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($historial as $caja)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $caja->fecha_apertura->format('d/m/Y H:i') }}
                        @if($caja->fecha_cierre)
                            <div class="text-xs text-gray-500">Cierre: {{ $caja->fecha_cierre->format('d/m/Y H:i') }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $caja->usuario->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                        Bs. {{ number_format($caja->monto_apertura, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                        Bs. {{ number_format($caja->monto_esperado ?? ($caja->monto_apertura + $caja->totalVentasEfectivo()), 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                        @if($caja->monto_cierre !== null)
                            Bs. {{ number_format($caja->monto_cierre, 2) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($caja->diferencia !== null)
                            @if($caja->diferencia == 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Exacto</span>
                            @elseif($caja->diferencia > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800" title="Sobrante">+ Bs. {{ number_format($caja->diferencia, 2) }}</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800" title="Faltante">Bs. {{ number_format($caja->diferencia, 2) }}</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $caja->estado == 'abierta' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($caja->estado) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                        No hay registros de cajas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($historial->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $historial->links() }}
    </div>
    @endif
</div>

@endsection
