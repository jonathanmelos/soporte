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
        Schema::create('chatreportes', function (Blueprint $table) {
            $table->id('id_chat'); // Clave primaria autoincremental
            $table->unsignedBigInteger('id_proyecto'); // Clave foránea, puede repetirse (Many-to-One)
            $table->timestamp('fecha')->useCurrent(); // Fecha y hora del registro automático
            $table->text('texto'); // Texto amplio
            $table->string('foto1')->nullable(); // URL opcional de la foto
            $table->string('usuario', 100); // Nombre del usuario (texto corto)
            $table->timestamps();

            // Definición de la clave foránea
            $table->foreign('id_proyecto')
                  ->references('id_proyecto')
                  ->on('proyecto')
                  ->onDelete('cascade'); // Elimina chatreportes asociados si se borra un proyecto
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatreportes');
    }
};
