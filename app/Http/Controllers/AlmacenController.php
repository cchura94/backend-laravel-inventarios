<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sucursalID = isset($request->sucursal)?$request->sucursal:null;
      
        $almacenes = Almacen::where("sucursal_id", $sucursalID)->with('sucursal')->get();
        
        return response()->json($almacenes, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "nombre" => "required",
            "sucursal_id" => "required",
        ]);

        $almacen = new Almacen();
        $almacen->nombre = $request->nombre;
        $almacen->codigo = $request->codigo;
        $almacen->descripcion = $request->descripcion;
        $almacen->sucursal_id = $request->sucursal_id;
        $almacen->save();

        return response()->json(["mensaje" => "Almacen Registrado correctamente"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $almacen = Almacen::with(["sucursal", "productos"])->find($id);

        return response()->json($almacen, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            "nombre" => "required",
            "sucursal_id" => "required",
        ]);

        $almacen = Almacen:: find($id);
        $almacen->nombre = $request->nombre;
        $almacen->codigo = $request->codigo;
        $almacen->descripcion = $request->descripcion;
        $almacen->sucursal_id = $request->sucursal_id;
        $almacen->update();

        return response()->json(["mensaje" => "Almacen actualizado correctamente"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
