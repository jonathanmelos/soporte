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
        Schema::create('rubros_requerimientos', function (Blueprint $table) {
            $table->id('id_rubros');
            $table->unsignedBigInteger('id_requerimientos'); // clave foránea
            $table->string('nombre_rubro');
            $table->string('unidad');
            $table->decimal('cantidad', 10, 2);
            $table->text('nota')->nullable();
            $table->string('archivo')->nullable();
            $table->timestamps();

            // Clave foránea corregida para coincidir con la tabla requerimientos
            $table->foreign('id_requerimientos')
                  ->references('id_requerimientos')->on('requerimientos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubros_requerimientos');
    }
};

