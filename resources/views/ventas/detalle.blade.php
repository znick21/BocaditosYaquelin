@extends('layouts.app')

@section('title', 'Detalle de Venta')
@section('header', 'Detalle de Venta')
@section('subheader', 'Ticket: ' . $venta->numero_venta)

@section('actions')
<a href="{{ route('ventas.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
    <i class="fas fa-arrow-left mr-2"></i> Volver
</a>
@endsection

@section('content')

@if($venta->estaAnulada())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md shadow-sm flex items-start">
        <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-3"></i>
        <div>
            <h3 class="text-sm font-bold text-red-800">Esta venta está anulada</h3>
            <p class="text-sm text-red-700 mt-1">{{ $venta->observaciones }}</p>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Lado Izquierdo: Resumen -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Info Principal -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Información General</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <span class="block text-xs font-medium text-gray-500 uppercase">Fecha y Hora</span>
                    <span class="block text-sm font-medium text-gray-900">{{ $venta->created_at->format('d/m/Y h:i A') }}</span>
                </div>
                <div>
                    <span class="block text-xs font-medium text-gray-500 uppercase">Cajero</span>
                    <span class="block text-sm font-medium text-gray-900">{{ $venta->usuario->name }}</span>
                </div>
                <div>
                    <span class="block text-xs font-medium text-gray-500 uppercase">Caja</span>
                    <span class="block text-sm font-medium text-gray-900">Apertura: {{ $venta->caja->fecha_apertura->format('d/m/Y') }}</span>
                </div>
                <div>
                    <span class="block text-xs font-medium text-gray-500 uppercase">Método de Pago</span>
                    <span class="block text-sm font-medium text-gray-900">
                        <i class="{{ $venta->metodoPago->icono }} mr-1"></i> {{ $venta->metodoPago->nombre }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Acciones Admin -->
        @if(auth()->user()->isAdmin() && !$venta->estaAnulada())
        <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden">
            <div class="bg-red-50 px-6 py-4 border-b border-red-200">
                <h3 class="text-lg font-bold text-red-800">Acciones de Administrador</h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-4">Anular esta venta restaurará el stock de los productos vendidos.</p>
                <form action="{{ route('ventas.anular', $venta) }}" method="POST" onsubmit="return confirm('¿Está seguro de anular esta venta? El stock será restaurado.');">
                    @csrf
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <i class="fas fa-ban mr-2"></i> Anular Venta
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>

    <!-- Lado Derecho: Detalle de Ítems -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900">Detalle del Pedido</h3>
            <a href="{{ route('ventas.ticket', $venta) }}" target="_blank" class="text-sm text-amber-600 hover:text-amber-700 font-medium bg-amber-50 px-3 py-1 rounded-md">
                <i class="fas fa-print mr-1"></i> Imprimir Recibo
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">P. Unitario</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($venta->detalles as $detalle)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $detalle->nombre_producto }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm text-gray-900">{{ $detalle->cantidad }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm text-gray-500">Bs. {{ number_format($detalle->precio_unitario, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm font-bold text-gray-900">Bs. {{ number_format($detalle->subtotal, 2) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-700">Subtotal</td>
                        <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">Bs. {{ number_format($venta->subtotal, 2) }}</td>
                    </tr>
                    @if($venta->descuento > 0)
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-red-500">Descuento</td>
                        <td class="px-6 py-3 text-right text-sm font-medium text-red-500">- Bs. {{ number_format($venta->descuento, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-lg font-black text-gray-900 uppercase">Total</td>
                        <td class="px-6 py-4 text-right text-xl font-black text-amber-600">Bs. {{ number_format($venta->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
@endsection
