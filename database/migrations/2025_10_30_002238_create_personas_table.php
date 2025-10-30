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
        Schema::create('personas', function (Blueprint $table) {
            $table->id(); // id (BI, US, AI, PK)

            $table->string("nombre_completo");
            $table->string("ci_nit")->nullable();
            $table->string("direccion")->nullable();
            $table->string("telefono", 20)->nullable();
            $table->boolean("estado")->default(true);

            $table->bigInteger("user_id")->unsigned();

            // 1:1 (belongsTo)
            $table->foreign("user_id")->references("id")->on("users");

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
