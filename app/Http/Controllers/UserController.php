<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $usuarios = User::orderBy('name')->paginate(15);
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $usuario = new User();
        return view('usuarios.form', compact('usuario'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,cajero',
            'telefono' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'telefono' => $request->telefono,
            'is_active' => true,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario registrado correctamente.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $usuario)
    {
        // Don't let an admin edit the primary admin's role if they are the only admin.
        // For simplicity, we just pass the user to the view.
        return view('usuarios.form', compact('usuario'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($usuario->id)],
            'role' => 'required|in:admin,cajero',
            'telefono' => 'nullable|string|max:20',
        ];

        // Only validate password if it's filled
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->role = $request->role;
        $usuario->telefono = $request->telefono;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Datos de usuario actualizados correctamente.');
    }

    /**
     * Toggle the user's active status.
     */
    public function cambiarEstado(User $usuario)
    {
        // Protect the current user from deactivating themselves
        if (auth()->id() === $usuario->id) {
            return redirect()->route('usuarios.index')->with('error', 'No puedes suspender tu propia cuenta activa.');
        }

        $usuario->is_active = !$usuario->is_active;
        $usuario->save();

        $estado = $usuario->is_active ? 'activado' : 'suspendido';
        return redirect()->route('usuarios.index')->with('success', "El usuario ha sido {$estado} con éxito.");
    }
}
