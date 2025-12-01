<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla pivote cliente_ubicacion
     * Relación muchos-a-muchos entre:
     *  - clientes.id_cliente
     *  - ubicaciones.id
     */
    public function up(): void
    {
        Schema::create('cliente_ubicacion', function (Blueprint $table) {
            $table->bigIncrements('id');

            // FK a clientes (tu PK es id_cliente)
            $table->unsignedBigInteger('cliente_id');

            // FK a ubicaciones
            $table->unsignedBigInteger('ubicacion_id');

            // Opcional: tipo de ubicación para ese cliente
            // Ej: "oficina", "planta", "proyecto", etc.
            $table->string('tipo', 50)->nullable();

            // Opcional: marcar si es la ubicación principal de ese cliente
            $table->boolean('es_principal')->default(false);

            $table->timestamps();

            // Clave única para no repetir el mismo par cliente-ubicacion
            $table->unique(['cliente_id', 'ubicacion_id'], 'cliente_ubicacion_unique');

            // Foreign keys
            $table->foreign('cliente_id')
                ->references('id_cliente')->on('clientes')
                ->onDelete('cascade');

            $table->foreign('ubicacion_id')
                ->references('id')->on('ubicaciones')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente_ubicacion');
    }
};
