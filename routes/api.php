<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// CRUD Usuarios
Route::get("/usuario", [UsuarioController::class, "funListar"]);
Route::post("/usuario", [UsuarioController::class, "funGuardar"]);
Route::get("/usuario/{id}", [UsuarioController::class, "funMostrar"]);
Route::put("/usuario/{id}", [UsuarioController::class, "funModificar"]);
Route::delete("/usuario/{id}", [UsuarioController::class, "funEliminar"]);
