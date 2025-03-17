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
        Schema::table('research', function (Blueprint $table) {
            $table->string('reference_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('research', function (Blueprint $table) {
            $table->dropColumn('reference_id');
        });
    }
    
};
