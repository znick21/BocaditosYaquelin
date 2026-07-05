@extends('layouts.app')

@section('title', 'Gestión de Usuarios')
@section('header', 'Usuarios y Personal')
@section('subheader', 'Administra a los cajeros y administradores del sistema.')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div></div>
    <a href="{{ route('usuarios.crear') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700">
        <i class="fas fa-plus mr-2"></i> Nuevo Usuario
    </a>
</div>

<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($usuarios as $user)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-700 font-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($user->isAdmin())
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            Administrador
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Cajero
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $user->telefono ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($user->is_active)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Suspendido</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('usuarios.editar', $user->id) }}" class="text-amber-600 hover:text-amber-900 mr-3">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    
                    @if(auth()->id() !== $user->id)
                        <form id="form-estado-{{ $user->id }}" action="{{ route('usuarios.estado', $user->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="button" onclick="confirmarEliminacion('form-estado-{{ $user->id }}', 'cambiar el estado de este usuario')" class="{{ $user->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}">
                                <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check-circle' }}"></i> 
                                {{ $user->is_active ? 'Suspender' : 'Activar' }}
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay usuarios registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($usuarios->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $usuarios->links() }}
        </div>
    @endif
</div>
@endsection
