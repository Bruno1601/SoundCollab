<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLockingFieldsToArchivosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('archivos', function (Blueprint $table) {
            $table->boolean('bloqueado')->default(false);  // columna 'bloqueado' que por defecto será false
            $table->unsignedBigInteger('bloqueado_por')->nullable();  // columna 'bloqueado_por' que puede ser null

            // Agrega una restricción de clave externa para la columna 'bloqueado_por'.
            // Esto vinculará la columna 'bloqueado_por' con la columna 'id' en la tabla 'users'.
            // Nota: este paso es opcional. Si no tienes una tabla de usuarios o si no quieres vincular estas columnas, puedes omitir esta línea.
            $table->foreign('bloqueado_por')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archivos', function (Blueprint $table) {
            // Elimina aquí las nuevas columnas
        });
    }
}







