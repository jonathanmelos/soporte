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
        Schema::create('proyecto', function (Blueprint $table) {
    $table->id('id_proyecto');
    $table->unsignedBigInteger('id_requerimiento');
    $table->string('empresa', 150)->nullable(); // 👈 Campo nuevo
    $table->string('autorizado', 100)->nullable();
    $table->string('responsable', 100)->nullable();
    $table->timestamp('fecha_creacion')->useCurrent();
    $table->date('fecha_entrega')->nullable();
    $table->date('fecha_finalizacion')->nullable();
    $table->decimal('precio', 10, 2)->nullable();
    $table->string('documento')->nullable();
    $table->unsignedTinyInteger('avance_obra')->default(0);
    $table->integer('val_material')->nullable();
    $table->integer('val_equipo')->nullable();
    $table->integer('val_cliente')->nullable();
    $table->integer('val_planificacion')->nullable();
    $table->enum('estado', ['pendiente', 'finalizado'])->default('pendiente');
    $table->timestamps();

    // $table->foreign('id_requerimiento')->references('id')->on('requerimientos')->onDelete('cascade');
});

        
    }           

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
