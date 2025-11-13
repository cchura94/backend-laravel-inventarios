<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $clientes = Cliente::get();
        return response()->json($clientes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "tipo" => "required"
        ]);
        
        $cliente = new Cliente();
        $cliente->tipo = $request->tipo;
        $cliente->razon_social = $request->razon_social;
        $cliente->direccion = $request->direccion;
        $cliente->telefono = $request->telefono;
        $cliente->nro_identificacion = $request->nro_identificacion;
        $cliente->save();
        
        return response()->json($cliente);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cliente = Cliente::find($id);

        return  response()->json($cliente, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
