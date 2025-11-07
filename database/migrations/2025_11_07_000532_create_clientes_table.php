<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();

            $table->string("tipo");
            $table->string("razon_social")->nullable();
            $table->string("nro_identificacion", 40)->nullable();
            $table->string("telefono", 20)->nullable();
            $table->string("direccion", 200)->nullable();
            $table->string("correo", 250)->nullable();
            $table->boolean("estado", 30)->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
