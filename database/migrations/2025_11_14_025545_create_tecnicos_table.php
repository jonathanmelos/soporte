<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tecnicos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->string('cedula', 20)->unique();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 100)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('tipo_sangre', 10)->nullable();
            $table->string('contacto_emergencia', 100)->nullable();
            $table->string('foto_perfil', 255)->nullable();

            $table->boolean('perfil_completo')->default(0);
            $table->string('estado', 20)->default('activo');

            $table->decimal('tarifa_hora_1', 10, 2)->nullable();
            $table->decimal('tarifa_hora_2', 10, 2)->nullable();
            $table->decimal('tarifa_hora_3', 10, 2)->nullable();
            $table->decimal('tarifa_hora', 10, 2)->nullable();

            $table->string('modalidad_trabajo', 20)->default('por_horas');

            $table->timestamps();

            // Relación con usuarios
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tecnicos');
    }
};
