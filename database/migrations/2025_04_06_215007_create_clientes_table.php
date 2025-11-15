<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('id_cliente'); // Clave primaria autoincremental
            $table->string('empresa');       // Nombre de contacto
            $table->string('ruc')->nullable();            // Número de RUC
            $table->string('contacto')->nullable();       // Nombre de contacto
            $table->string('telefono')->nullable();    // Teléfono de contacto
            $table->string('correo')->nullable();      // Correo electrónico
            $table->string('web')->nullable(); // Sitio web, puede ser null

            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}

