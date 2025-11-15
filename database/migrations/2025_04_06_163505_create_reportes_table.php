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
        Schema::create('reporte', function (Blueprint $table) {
            $table->id('id_reporte'); // Clave primaria autoincremental
            $table->unsignedBigInteger('id_proyecto'); // Llave foránea al proyecto

            $table->timestamp('fecha')->useCurrent(); // Fecha automática de creación
            $table->text('tareas')->nullable();       // Descripción de tareas realizadas
            $table->text('tecnicos')->nullable();     // Técnicos involucrados
            $table->text('material')->nullable();     // Material utilizado
            $table->text('herramienta')->nullable();  // Herramientas utilizadas
            $table->text('novedades')->nullable();    // Novedades o incidencias

            // Fotos (pueden ser rutas a los archivos en el almacenamiento)
            $table->string('foto1')->nullable();
            $table->string('foto2')->nullable();
            $table->string('foto3')->nullable();

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
