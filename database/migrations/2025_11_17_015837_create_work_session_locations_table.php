<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the work_session_locations table.
     */
    public function up(): void
    {
        Schema::create('work_session_locations', function (Blueprint $table) {
            $table->id();

            // Relación con la jornada (work_sessions.id)
            $table->foreignId('work_session_id')
                ->constrained('work_sessions')
                ->cascadeOnDelete();

            // Técnico dueño del registro (lo llenaremos desde work_sessions.tecnico_id)
            // Dejo solo la columna para no romper si la tabla se llama diferente.
            $table->unsignedBigInteger('tecnico_id')->nullable();

            // Datos de ubicación
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->decimal('accuracy', 8, 2)->nullable();

            // Momento en que se tomó el punto (desde el móvil viene en milisegundos)
            $table->timestamp('recorded_at')->useCurrent();

            // start / pause / resume / stop / ping
            $table->string('event_type', 20)->default('ping');

            // fuente del dato: foreground / background, etc. (por si luego lo quieres usar)
            $table->string('source', 20)->default('mobile');

            $table->timestamps();

            $table->index(['work_session_id', 'recorded_at']);
            $table->index('tecnico_id');
        });
    }

    /**
     * Drop the work_session_locations table.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_session_locations');
    }
};
