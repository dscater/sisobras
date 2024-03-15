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
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("obra_id");
            $table->decimal("presupuesto", 24, 2);
            $table->decimal("total_precio", 24, 2);
            $table->decimal("total_cantidad", 24, 2);
            $table->decimal("total", 24, 2);
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
        Schema::dropIfExists('presupuestos');
    }
};
