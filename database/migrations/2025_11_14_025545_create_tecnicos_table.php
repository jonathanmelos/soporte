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

            // Relación con users (login / OTP / Sanctum)
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Datos personales básicos
            $table->string('cedula', 20)->unique();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 100)->nullable();      // opcional, puede duplicar el de user si quieres
            $table->string('direccion', 255)->nullable();
            $table->string('tipo_sangre', 10)->nullable();
            $table->string('contacto_emergencia', 100)->nullable();

            // Foto de perfil
            $table->string('foto_perfil')->nullable();

            // Estado dentro de la plataforma
            $table->boolean('perfil_completo')->default(false);
            $table->string('estado', 20)->default('activo'); // activo, suspendido, etc.

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tecnicos');
    }
};
