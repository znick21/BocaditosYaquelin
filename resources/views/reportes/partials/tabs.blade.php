<div class="mb-6 border-b border-gray-200">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        <!-- Ventas -->
        <a href="{{ route('reportes.index') }}" 
           class="{{ request()->routeIs('reportes.index') ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            <i class="fas fa-chart-line mr-2"></i> Reporte de Ventas
        </a>

        <!-- Inventario -->
        <a href="{{ route('reportes.inventario') }}" 
           class="{{ request()->routeIs('reportes.inventario') ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            <i class="fas fa-boxes mr-2"></i> Reporte de Inventario
        </a>

        <!-- Cajas -->
        <a href="{{ route('reportes.cajas') }}" 
           class="{{ request()->routeIs('reportes.cajas') ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            <i class="fas fa-cash-register mr-2"></i> Reporte de Cajas
        </a>
    </nav>
</div>
