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
        Schema::create('obras', function (Blueprint $table) {
            $table->id();
            $table->string("nombre", 255);
            $table->unsignedBigInteger("gerente_regional_id");
            $table->unsignedBigInteger("encargado_obra_id");
            $table->date("fecha_pent");
            $table->date("fecha_peje");
            $table->text("descripcion")->nullable();
            $table->string("lat", 255);
            $table->string("lon", 255);
            $table->unsignedBigInteger("categoria_id");
            $table->date("fecha_registor");
            $table->timestamps();

            $table->foreign("gerente_regional_id")->on("users")->references("id");
            $table->foreign("encargado_obra_id")->on("users")->references("id");
            $table->foreign("categoria_id")->on("categorias")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obras');
    }
};
