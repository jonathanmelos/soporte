<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_session_photos', function (Blueprint $table) {
            $table->id();

            // Relación con la jornada
            $table->foreignId('work_session_id')
                ->constrained('work_sessions')
                ->onDelete('cascade');

            // Tipo de foto (selfie_inicio, contexto_inicio, selfie_fin, contexto_fin)
            $table->string('type', 30);

            // Ruta del archivo: storage/app/public/jornadas/xxxx.jpg
            $table->string('photo_path');

            $table->timestamps();

            // índice recomendado
            $table->index(['work_session_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_session_photos');
    }
};
