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
        Schema::create('requerimientos', function (Blueprint $table) {
            $table->id('id_requerimientos');
            $table->string('id_cliente', 150);
            $table->timestamp('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('contacto', 150)->nullable(); // ← Este campo ahora es opcional
            $table->string('creado', 150);
            $table->text('trabajo');
            $table->enum('proridad', ['alta', 'media', 'baja']);
            $table->enum('ejecucion', ['corto', 'mediano', 'largo']);
            $table->enum('pago', ['cotizacion', 'lista']);
            $table->enum('estado', ['pendiente', 'aprobado', 'no aprobado'])->default('pendiente');
            $table->text('comentarios')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requerimientos');
    }
};
