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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('usuario', 255)->unique();
            $table->string('password');
            $table->string('nombre');
            $table->string('paterno')->nullable();
            $table->string('materno')->nullable();
            $table->string('ci');
            $table->string('ci_exp');
            $table->string('dir', 255);
            $table->string('email')->nullable();
            $table->string('fono');
            $table->string('tipo', 255);
            $table->string('foto', 255)->nullable();
            $table->integer('acceso')->default(1);
            $table->date("fecha_registro");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
