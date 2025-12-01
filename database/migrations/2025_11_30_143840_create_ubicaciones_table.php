<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla de ubicaciones físicas reutilizables.
     * Ej: "Edificio Matriz", "Bodega Norte", etc.
     */
    public function up(): void
    {
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Nombre amigable de la ubicación
            $table->string('nombre', 150);

            // Coordenadas (mismo formato que usas en work_session_locations)
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);

            // Radio de la zona en metros (para tolerancia según accuracy)
            $table->decimal('radio_m', 8, 2)->nullable();

            $table->timestamps();

            // Opcional: índice para búsquedas rápidas por lat/lng
            $table->index(['lat', 'lng'], 'ubicaciones_lat_lng_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubicaciones');
    }
};
