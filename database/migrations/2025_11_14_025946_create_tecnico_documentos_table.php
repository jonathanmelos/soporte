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
        Schema::create('tecnico_documentos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tecnico_id')->constrained('tecnicos')->onDelete('cascade');

    $table->string('tipo', 100);          // "certificado", "curso", "seguridad", etc.
    $table->string('archivo_url', 255)->nullable();
    $table->string('descripcion', 255)->nullable();
    $table->date('fecha_emision')->nullable();
    $table->date('fecha_expiracion')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tecnico_documentos');
    }
};
