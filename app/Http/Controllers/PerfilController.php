<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PerfilController extends Controller
{
    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $usuario = auth()->user();
        return view('perfil.edit', compact('usuario'));
    }

    /**
     * Update the user's profile in storage.
     */
    public function update(Request $request)
    {
        $usuario = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($usuario->id)],
            'telefono' => 'nullable|string|max:20',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return back()->with('success', 'Tu perfil ha sido actualizado correctamente.');
    }
}
