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
        Schema::create('detalle_facturas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('factura_id')->constrained('facturas')->onDelete('cascade');
    $table->string('codigo');
    $table->string('descripcion');
    $table->decimal('cantidad', 10, 2);
    $table->decimal('precio_unitario', 10, 4);
    $table->decimal('descuento', 10, 2);
    $table->decimal('precio_total_sin_impuesto', 10, 2);
    $table->decimal('impuesto_valor', 10, 2);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_facturas');
    }
};
