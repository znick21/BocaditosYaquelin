@extends('layouts.guest')

@section('title', 'Ingreso al Sistema')

@section('content')
<div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-amber-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl relative z-10 border border-gray-100">
        <div>
            <div class="mx-auto w-24 h-24 rounded-full flex items-center justify-center mb-6">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-24 h-24 rounded-full object-cover shadow-lg border-4 border-amber-500">
            </div>
            <h2 class="text-center text-3xl font-extrabold text-gray-900 tracking-tight">
                Ingreso al POS
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Acceso exclusivo para empleados
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="appearance-none rounded-lg relative block w-full pl-10 px-3 py-3 border @error('email') border-red-300 @else border-gray-300 @enderror placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm transition-all" 
                            placeholder="admin@yakelin.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="appearance-none rounded-lg relative block w-full pl-10 px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:z-10 sm:text-sm transition-all" 
                            placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="recordar" name="recordar" type="checkbox" class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded cursor-pointer">
                    <label for="recordar" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                        Recordar sesión
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-amber-600 hover:text-amber-500 transition-colors">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-md transition-all hover:shadow-lg overflow-hidden">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt text-amber-500 group-hover:text-amber-400 transition-colors"></i>
                    </span>
                    Ingresar al Sistema
                </button>
            </div>
            
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Credenciales de Demo</span>
                    </div>
                </div>
                <div class="mt-6 grid grid-cols-2 gap-3 text-xs text-center">
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <span class="block font-semibold text-gray-700 mb-1">Administrador</span>
                        <span class="block text-gray-500">admin@yakelin.com</span>
                        <span class="block text-gray-500">password</span>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <span class="block font-semibold text-gray-700 mb-1">Cajero</span>
                        <span class="block text-gray-500">cajero@yakelin.com</span>
                        <span class="block text-gray-500">password</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
</style>
@endsection
