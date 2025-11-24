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
    $table->string('uuid', 36)->unique();
    $table->string('session_uuid', 100); // ⬅️ 36 → 100
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
