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
        Schema::create('work_sessions', function (Blueprint $table) {
    $table->id();

    $table->foreignId('tecnico_id')
        ->constrained('tecnicos')
        ->onDelete('cascade');

    $table->timestamp('started_at');
    $table->timestamp('ended_at')->nullable();
    $table->integer('duration_seconds')->nullable();

    $table->decimal('start_lat', 10, 7)->nullable();
    $table->decimal('start_lng', 10, 7)->nullable();
    $table->decimal('end_lat', 10, 7)->nullable();
    $table->decimal('end_lng', 10, 7)->nullable();

    // Para evitar duplicados desde el dispositivo
    $table->string('device_session_uuid')->nullable()->index();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_sessions');
    }
};
