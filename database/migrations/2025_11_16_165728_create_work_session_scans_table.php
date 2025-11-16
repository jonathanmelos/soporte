<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_session_scans', function (Blueprint $table) {
            $table->id();

            $table->string('uuid', 36)->unique();      // identificador del escaneo
            $table->string('session_uuid', 36);        // referencia lógica a work_sessions.uuid

            $table->string('project_code', 100)->nullable();
            $table->string('area', 100)->nullable();
            $table->text('description')->nullable();

            $table->dateTime('scanned_at');

            $table->timestamps();

            $table->index('session_uuid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_session_scans');
    }
};
