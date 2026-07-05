@extends('layouts.app')

@section('title', 'Mi Perfil')
@section('header', 'Configuración de Cuenta')
@section('subheader', 'Actualiza tus datos personales y contraseña de acceso.')

@section('content')

<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden max-w-3xl">
    <form action="{{ route('perfil.update') }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="md:col-span-2 flex items-center mb-4">
                <div class="h-16 w-16 bg-amber-100 rounded-full flex items-center justify-center text-amber-700 font-bold text-2xl mr-4 shadow-sm border border-amber-200">
                    {{ substr($usuario->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $usuario->name }}</h3>
                    <p class="text-sm text-gray-500 capitalize"><i class="fas fa-id-badge mr-1"></i> Rol: {{ $usuario->role }}</p>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Nombre Completo *</label>
                <input type="text" name="name" value="{{ old('name', $usuario->name) }}" required class="mt-1 block w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
                <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required class="mt-1 block w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono) }}" class="mt-1 block w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                @error('telefono') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2 pt-4 border-t border-gray-100 mt-2">
                <h4 class="font-semibold text-gray-900 mb-2">Seguridad (Opcional)</h4>
                <p class="text-xs text-gray-500 mb-4">Solo llena estos campos si deseas cambiar tu contraseña actual. Si los dejas en blanco, tu contraseña seguirá siendo la misma.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                <input type="password" name="password" class="mt-1 block w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña</label>
                <input type="password" name="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
            </div>
            
        </div>

        <div class="mt-8 flex justify-end">
            <a href="{{ route('panel') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3">
                Cancelar
            </a>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700">
                <i class="fas fa-save mr-2"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>

@endsection
