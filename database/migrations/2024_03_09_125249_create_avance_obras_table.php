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
        Schema::create('avance_obras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("obra_id");
            $table->integer("nro_progreso");
            $table->string("marcados");
            $table->text("descripcion");
            $table->text("observacion");
            $table->date("fecha_registro");
            $table->timestamps();
            $table->foreign("obra_id")->on("obras")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avance_obras');
    }
};
