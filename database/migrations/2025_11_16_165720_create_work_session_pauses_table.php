<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_session_pauses', function (Blueprint $table) {
            $table->id();

            $table->string('uuid', 36)->unique();      
            
            // ⬅️ Actualizado de 36 → 100
            $table->string('session_uuid', 100);       

            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();

            $table->timestamps();

            $table->index('session_uuid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_session_pauses');
    }
};
