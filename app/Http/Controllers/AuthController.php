<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // funLogin
    public function funLogin(Request $request){
        $credenciales = $request->validate([
            "email" => "required|email|min:5|max:200",
            "password" => "required"
        ]);

        // verificar y autenticar si el correo y contraseña son correctos
        if(!Auth::attempt($credenciales)){
            return response()->json(["mensaje" => "Credenciales Incorrectas"]);
        }
        // generar TOKEN
        $token = $request->user()->createToken("Token Auth")->plainTextToken;

        // retornamos el token
        return response()->json(["access_token" => $token, "usuario" => $request->user()]);
    }

    // funRegister
    public function funRegister(Request $request){
        $request->validate([
            "name" => "required|string",
            "email" => "required|email",
            "password" => "required|same:cpassword"
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        
        return response()->json(["mensaje" => "Usuario Registrado"]);
    }

    // funProfile
    public function funProfile(Request $request){
        return response()->json($request->user());
    }

    // funLogout
    public function funLogout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json(["mensaje" => "Salio"]);
    }
}
