<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_sessions', function (Blueprint $table) {
            // UUID para sync sin duplicados
            if (!Schema::hasColumn('work_sessions', 'uuid')) {
                $table->string('uuid', 36)->nullable()->unique();
            }

            // Dispositivo desde el que se registró la sesión
            if (!Schema::hasColumn('work_sessions', 'device_uuid')) {
                $table->string('device_uuid', 100)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('work_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('work_sessions', 'device_uuid')) {
                $table->dropColumn('device_uuid');
            }

            if (Schema::hasColumn('work_sessions', 'uuid')) {
                // Nombre por defecto del índice unique: work_sessions_uuid_unique
                $table->dropUnique('work_sessions_uuid_unique');
                $table->dropColumn('uuid');
            }
        });
    }
};
