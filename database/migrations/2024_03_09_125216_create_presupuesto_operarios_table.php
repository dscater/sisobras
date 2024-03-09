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
        Schema::create('presupuesto_operarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("presupuesto_id");
            $table->unsignedBigInteger("operario_id");
            $table->decimal("precio", 24, 2);
            $table->double("cantidad", 8, 2);
            $table->decimal("subtotal", 24, 2);
            $table->timestamps();

            $table->foreign("presupuesto_id")->on("presupuestos")->references("id")->onDelete("cascade");;
            $table->foreign("operario_id")->on("operarios")->references("id")->onDelete("cascade");;
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuesto_operarios');
    }
};
