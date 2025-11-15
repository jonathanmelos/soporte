<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('proyecto', function (Blueprint $table) {
            $table->string('empresa', 150)->nullable()->after('id_requerimiento');
        });
    }
    
    public function down()
    {
        Schema::table('proyecto', function (Blueprint $table) {
            $table->dropColumn('empresa');
        });
    }
    
};
