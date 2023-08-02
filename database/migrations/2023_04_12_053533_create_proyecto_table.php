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
        if(!Schema::hasTable('proyectos')){
            Schema::create('proyectos', function (Blueprint $table) {
                $table->id();
                $table->string('nombre_proyecto');
                $table->string('descripcion');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('ruta_carpeta');
                $table->timestamps();


            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
