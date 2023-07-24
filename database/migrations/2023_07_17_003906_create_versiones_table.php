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
        Schema::create('versiones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('archivo_id');
            $table->string('ruta');
            $table->string('usuario');
            $table->timestamp('fecha_subida');
            $table->timestamps();

            // Relación con la tabla archivos
            $table->foreign('archivo_id')->references('id')->on('archivos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('versiones');
    }
};
