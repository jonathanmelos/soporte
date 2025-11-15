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
        Schema::create('facturas', function (Blueprint $table) {
    $table->id();
    $table->string('numero_autorizacion');
    $table->dateTime('fecha_autorizacion');
    $table->string('ambiente');
    $table->string('clave_acceso')->unique();
    $table->string('ruc_emisor');
    $table->string('razon_social_emisor');
    $table->string('nombre_comercial_emisor')->nullable();
    $table->string('ruc_comprador');
    $table->string('razon_social_comprador');
    $table->date('fecha_emision');
    $table->decimal('total_sin_impuestos', 10, 2);
    $table->decimal('total_descuento', 10, 2);
    $table->decimal('importe_total', 10, 2);
    $table->string('moneda');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
