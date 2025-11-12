<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = isset($request->limit)?$request->limit:10;
        $estado = isset($request->estado)?$request->estado:null;
        $almacenID = isset($request->almacen)?$request->almacen:null;
        
        $productos = Producto::query();

        if(isset($estado)){
            $productos = $productos->where("estado", "=", $request->activo);
        }
        if(isset($request->search)){
            $search = $request->search;

            $productos = $productos->where("nombre", "iLike", "%$search%")
                                    ->orWhere("marca", "iLike", "%$search%");
        }
        if(isset($almacenID)){
            $productos = $productos->whereHas("almacenes", function ($query) use ($almacenID){
                $query->where('almacenes.id', "=", $almacenID);
            });
        }

        $productos = $productos->with(['categoria', 'almacenes'])
                                ->orderBy('id', 'desc')
                                ->paginate($limit);
        return response()->json($productos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "nombre" => "required"
        ]);

        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->unidad_medida = $request->unidad_medida;
        $producto->marca = $request->marca;
        $producto->precio_venta = $request->precio_venta;
        $producto->imagen = $request->imagen;
        $producto->estado = $request->estado;
        $producto->categoria_id = $request->categoria_id;
        $producto->save();

        return response()->json(["mensaje" => "Producto Registrado" ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $producto = Producto::find($id);

        return response()->json($producto);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "nombre" => "required"
        ]);

        $producto = Producto::find($id);
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->unidad_medida = $request->unidad_medida;
        $producto->marca = $request->marca;
        $producto->precio_venta = $request->precio_venta;
        $producto->imagen = $request->imagen;
        $producto->estado = $request->estado;
        $producto->categoria_id = $request->categoria_id;
        $producto->update();

        return response()->json(["mensaje" => "Producto actualizado" ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function actualizarImagen(string $id, Request $request){
        if($file = $request->file("imagen")){
            $direccion_url = time() . "-" .$file->getClientOriginalName();
            $file->move("imagenes", $direccion_url);

            $producto = Producto::find($id);
            $producto->imagen = "imagenes/". $direccion_url;
            $producto->update();

            return response()->json();
        }
    }
}
