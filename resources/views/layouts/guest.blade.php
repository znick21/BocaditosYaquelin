<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bocaditos Yaquelin')</title>
    
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
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col">

    <!-- Header / Navbar -->
    <header class="bg-white shadow-sm z-50 relative" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('landing') }}" class="flex items-center gap-3">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-12 h-12 rounded-full object-cover shadow-md border-2 border-amber-500">
                        <div>
                            <span class="text-xl font-bold text-gray-900 block leading-tight">Bocaditos Yaquelin</span>
                            <span class="text-xs text-amber-600 font-medium tracking-wide uppercase">Sabor Tradicional</span>
                        </div>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="{{ route('landing') }}" class="text-gray-600 hover:text-amber-600 font-medium transition-colors">Inicio</a>
                    <a href="{{ route('catalogo') }}" class="text-gray-600 hover:text-amber-600 font-medium transition-colors">Catálogo</a>
                    
                    @auth
                        <a href="{{ route('panel') }}" class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-full text-white bg-amber-600 hover:bg-amber-700 shadow-sm transition-all hover:shadow-md">
                            <i class="fas fa-columns mr-2"></i> Ir al Panel
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-all hover:shadow-md">
                            <i class="fas fa-sign-in-alt mr-2"></i> Ingresar
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                        <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="fas fa-times text-xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" class="md:hidden border-t border-gray-200 bg-white absolute w-full shadow-lg" x-transition x-cloak>
            <div class="pt-2 pb-4 space-y-1 px-4">
                <a href="{{ route('landing') }}" class="block pl-3 pr-4 py-3 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-amber-600 hover:bg-amber-50 hover:border-amber-500">Inicio</a>
                <a href="{{ route('catalogo') }}" class="block pl-3 pr-4 py-3 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-amber-600 hover:bg-amber-50 hover:border-amber-500">Catálogo</a>
                
                @auth
                    <a href="{{ route('panel') }}" class="block pl-3 pr-4 py-3 border-l-4 border-transparent text-base font-medium text-amber-600 hover:bg-amber-50 hover:border-amber-500">Ir al Panel</a>
                @else
                    <a href="{{ route('login') }}" class="block pl-3 pr-4 py-3 border-l-4 border-transparent text-base font-medium text-amber-600 hover:bg-amber-50 hover:border-amber-500">Ingresar</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col relative w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-auto">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <span class="text-xl font-bold text-white block mb-4">
                        <i class="fas fa-hamburger mr-2 text-amber-500"></i>Bocaditos Yaquelin
                    </span>
                    <p class="text-gray-400 text-sm">Ofreciendo el mejor sabor tradicional camba con ingredientes de primera calidad. ¡Disfruta de nuestros horneados!</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('landing') }}" class="text-gray-400 hover:text-amber-500 transition-colors">Inicio</a></li>
                        <li><a href="{{ route('catalogo') }}" class="text-gray-400 hover:text-amber-500 transition-colors">Nuestro Catálogo</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-amber-500 transition-colors">Ingreso Empleados</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase mb-4">Contacto</h3>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li class="flex items-center"><i class="fas fa-map-marker-alt w-5 text-amber-500"></i> Av. Banzer, Santa Cruz</li>
                        <li class="flex items-center"><i class="fas fa-phone w-5 text-amber-500"></i> +591 70000000</li>
                        <li class="flex items-center"><i class="fab fa-whatsapp w-5 text-amber-500"></i> +591 70000000</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm xl:text-center">&copy; {{ date('Y') }} Bocaditos Yaquelin. Todos los derechos reservados.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-facebook text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-instagram text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="fab fa-tiktok text-xl"></i></a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
