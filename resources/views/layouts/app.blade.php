<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POS') - Bocaditos Yaquelin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        amber: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmarEliminacion(formId, tipo = 'este elemento') {
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Quieres eliminar ${tipo}? Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- Sidebar Overlay (Mobile) -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden" @click="sidebarOpen = false" x-cloak></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-auto flex flex-col h-screen">
        
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 border-b border-gray-200 px-4">
            <span class="text-xl font-bold text-amber-600 truncate flex items-center">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-8 w-8 mr-2 rounded-full object-cover border border-amber-500 shadow-sm">
                Bocaditos Yaquelin
            </span>
        </div>

        <!-- Menú -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <a href="{{ route('panel') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('panel') ? 'bg-amber-50 text-amber-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-chart-pie w-6"></i>
                Panel de Control
            </a>

            <div class="pt-4 pb-2 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Caja y Ventas</div>
            
            <a href="{{ route('pos.index') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('pos.*') ? 'bg-amber-50 text-amber-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-cash-register w-6"></i>
                Punto de Venta
            </a>
            
            <a href="{{ route('caja.index') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('caja.*') ? 'bg-amber-50 text-amber-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-box w-6"></i>
                Gestión de Caja
            </a>

            <a href="{{ route('ventas.index') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('ventas.*') ? 'bg-amber-50 text-amber-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-receipt w-6"></i>
                Historial de Ventas
            </a>

            @if(auth()->user()->isAdmin())
                <div class="pt-4 pb-2 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Administración</div>
                
                <a href="{{ route('categorias.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('categorias.*') ? 'bg-amber-100 text-amber-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-tags mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('categorias.*') ? 'text-amber-600' : 'text-gray-400 group-hover:text-gray-500' }} text-center"></i>
                    Categorías
                </a>

                <a href="{{ route('productos.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('productos.*') ? 'bg-amber-100 text-amber-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-box mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('productos.*') ? 'text-amber-600' : 'text-gray-400 group-hover:text-gray-500' }} text-center"></i>
                    Productos
                </a>

                <a href="{{ route('inventario.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('inventario.*') ? 'bg-amber-100 text-amber-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-clipboard-list mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('inventario.*') ? 'text-amber-600' : 'text-gray-400 group-hover:text-gray-500' }} text-center"></i>
                    Producción y Mermas
                </a>

                <a href="{{ route('sliders.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('sliders.*') ? 'bg-amber-100 text-amber-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-images mr-3 flex-shrink-0 h-5 w-5 {{ request()->routeIs('sliders.*') ? 'text-amber-600' : 'text-gray-400 group-hover:text-gray-500' }} text-center"></i>
                    Banners Principales
                </a>

                <a href="{{ route('reportes.index') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('reportes.*') ? 'bg-amber-50 text-amber-600 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-chart-line w-6"></i>
                    Reportes
                </a>
            @endif
        </nav>

        <!-- User profile bottom -->
        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="ml-3 flex-1 overflow-hidden">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Cerrar Sesión">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        
        <!-- Header (Mobile menu & Top bar) -->
        <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center lg:hidden">
                <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
            <div class="flex-1 lg:flex-none"></div>
            
            <div class="flex items-center space-x-4">
                <!-- Reloj -->
                <div class="hidden sm:block text-sm text-gray-500" x-data="{ time: new Date().toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'}) }" x-init="setInterval(() => time = new Date().toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'}), 10000)">
                    <i class="far fa-clock mr-1"></i> <span x-text="time"></span>
                </div>
                <a href="{{ route('landing') }}" target="_blank" class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                    <i class="fas fa-external-link-alt mr-1"></i> Ver Tienda
                </a>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto bg-gray-50 p-4 sm:p-6 lg:p-8">
            
            <!-- Alertas Flash -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md flex items-start" x-data="{ show: true }" x-show="show">
                    <i class="fas fa-check-circle text-green-500 mt-0.5 mr-3"></i>
                    <div class="flex-1">
                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md flex items-start" x-data="{ show: true }" x-show="show">
                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
                    <div class="flex-1">
                        <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <!-- Título de Página -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">@yield('header')</h1>
                    @hasSection('subheader')
                        <p class="text-sm text-gray-500 mt-1">@yield('subheader')</p>
                    @endif
                </div>
                <div class="mt-4 sm:mt-0">
                    @yield('actions')
                </div>
            </div>

            <!-- Contenido dinámico -->
            @yield('content')

        </main>
    </div>

    @stack('scripts')
</body>
</html>
