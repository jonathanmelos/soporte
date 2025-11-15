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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id('id_personal');                    // Clave primaria con auto incremento
            $table->string('cedula', 20)->unique();       // Número de cédula, único
            $table->string('nombre', 100);                 // Nombre
            $table->string('apellido', 100);               // Apellido
            $table->string('telefono', 20)->nullable();   // Teléfono, puede ser NULL
            $table->string('correo', 100)->nullable();    // Correo electrónico, puede ser NULL
            $table->string('direccion', 255)->nullable(); // Dirección, puede ser NULL
            $table->string('tipo_sangre', 10)->nullable(); // Tipo de sangre, puede ser NULL
            $table->string('contacto_emergencia', 100)->nullable(); // Contacto de emergencia, puede ser NULL
            $table->string('especialidad', 100)->nullable(); // Especialidad, puede ser NULL
            $table->string('departamento', 100)->nullable(); // Departamento, puede ser NULL
            $table->string('cargo', 100)->nullable();     // Cargo, puede ser NULL
            $table->string('foto')->nullable();     // Cargo, puede ser NULL
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
