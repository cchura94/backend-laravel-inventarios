<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsuarioController extends Controller
{
    public function funListar(){
        try {
            $usuarios = User::get();
            return response()->json($usuarios, 200);

        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }      
    }

    public function funGuardar(Request $request){

        $request->validate([
            "name" => "required",
            "email" => "email|required|unique:users",
            "password" => "required|min:6|max:30"
        ]);

         try {
            $usuario = new User();
            $usuario->name = $request->name;
            $usuario->email = $request->email;
            $usuario->password = $request->password;
            $usuario->save();

            return response()->json(["mensaje" => "Usuario Registrado"], 201);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function funMostrar($id){
         try {
            $usuario = User::find($id);

            return response()->json($usuario, 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function funModificar(Request $request, $id){
         try {
            $usuario = User::find($id);
            $usuario->name = $request->name;
            $usuario->email = $request->email;
            $usuario->password = $request->password;
            $usuario->update();
            
            return response()->json(["mensaje" => "Usuario Registrado"], 201);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }

    }

    public function funEliminar($id){
         try {
            $usuario = User::find($id);
            $usuario->delete();
            // softdelete

            return response()->json(["mensaje" => "Usuario eliminado"], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
