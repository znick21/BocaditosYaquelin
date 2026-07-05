@extends('layouts.app')

@section('title', $usuario->id ? 'Editar Usuario' : 'Nuevo Usuario')
@section('header', 'Gestión de Personal')
@section('subheader', $usuario->id ? 'Modifica los datos del usuario.' : 'Registra un nuevo cajero o administrador.')

@section('content')

<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden max-w-3xl">
    <form action="{{ $usuario->id ? route('usuarios.actualizar', $usuario->id) : route('usuarios.guardar') }}" method="POST" class="p-6">
        @csrf
        @if($usuario->id)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
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

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Rol del Usuario *</label>
                <select name="role" required class="mt-1 block w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                    <option value="cajero" {{ old('role', $usuario->role) == 'cajero' ? 'selected' : '' }}>Cajero (Solo ventas e inventario básico)</option>
                    <option value="admin" {{ old('role', $usuario->role) == 'admin' ? 'selected' : '' }}>Administrador (Acceso total)</option>
                </select>
                @error('role') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2 pt-4 border-t border-gray-100 mt-2">
                <h4 class="font-semibold text-gray-900 mb-4">{{ $usuario->id ? 'Cambiar Contraseña (Opcional)' : 'Contraseña de Acceso *' }}</h4>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Contraseña {{ $usuario->id ? '' : '*' }}</label>
                <input type="password" name="password" {{ $usuario->id ? '' : 'required' }} class="mt-1 block w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Confirmar Contraseña {{ $usuario->id ? '' : '*' }}</label>
                <input type="password" name="password_confirmation" {{ $usuario->id ? '' : 'required' }} class="mt-1 block w-full border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
            </div>
            
        </div>

        <div class="mt-8 flex justify-end">
            <a href="{{ route('usuarios.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3">
                Cancelar
            </a>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700">
                <i class="fas fa-save mr-2"></i> {{ $usuario->id ? 'Guardar Cambios' : 'Registrar Usuario' }}
            </button>
        </div>
    </form>
</div>

@endsection
