<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();

            // Usuario dueño del dispositivo (tabla users existente)
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('device_uuid', 100);   // ID lógico generado por la app
            $table->string('platform', 50);       // android / ios / other
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('os_version', 50)->nullable();
            $table->string('app_version', 50)->nullable();

            $table->timestamp('last_seen_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'device_uuid'], 'uniq_user_device');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
