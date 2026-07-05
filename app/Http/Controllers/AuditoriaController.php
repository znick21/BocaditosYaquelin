<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditoriaProducto;

class AuditoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AuditoriaProducto::orderBy('created_at', 'desc');

        if ($request->has('accion') && $request->accion != '') {
            $query->where('accion', $request->accion);
        }

        if ($request->has('busqueda') && $request->busqueda != '') {
            $query->where('nombre_producto', 'like', '%' . $request->busqueda . '%');
        }

        $auditorias = $query->paginate(20);

        return view('auditoria.index', compact('auditorias'));
    }
}
