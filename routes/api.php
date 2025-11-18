<?php

use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SucursalController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// Auth
Route::prefix('/v1/auth')->group(function(){

    Route::post("/login", [AuthController::class, "funLogin"]);
    Route::post("/register", [AuthController::class, "funRegister"]);
    
    Route::middleware('auth:sanctum')->group(function(){
        Route::get("/profile", [AuthController::class, "funProfile"]);
        Route::post("/logout", [AuthController::class, "funLogout"]);
    });
});

Route::middleware('auth:sanctum')->group(function(){
    
    // reporte excel
    Route::get('producto/export-excel', [ProductoController::class, 'exportarProductosPDF']);
    // reporte PDF
    Route::get("/nota/reportespdf", [NotaController::class, "funReportePDF"]);
    // subida de imagen de producto
    Route::post("/producto/{id}/subir-imagen", [ProductoController::class, "actualizarImagen"]);

    // CRUD Usuarios
    Route::get("/usuario", [UsuarioController::class, "funListar"]);
    Route::post("/usuario", [UsuarioController::class, "funGuardar"]);
    Route::get("/usuario/{id}", [UsuarioController::class, "funMostrar"]);
    Route::put("/usuario/{id}", [UsuarioController::class, "funModificar"]);
    Route::delete("/usuario/{id}", [UsuarioController::class, "funEliminar"]);

    // CRUDs
    Route::apiResource("categoria", CategoriaController::class); 
    Route::apiResource("role", RoleController::class);
    Route::apiResource("cliente", ClienteController::class); 
    
    Route::apiResource("sucursal", SucursalController::class);
    Route::apiResource("almacen", AlmacenController::class);
    Route::apiResource("producto", ProductoController::class);

    // Crud Notas
    Route::apiResource("nota", NotaController::class);
});

Route::get("/no-autorizado", function(){
    return response()->json(["mensaje" => "No estas autorizado para ver esta información"], 401);
})->name("login");
