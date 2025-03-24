<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToResearchTable extends Migration
{
    public function up()
    {
        Schema::table('research', function (Blueprint $table) {
            $table->softDeletes(); // Adds the 'deleted_at' column
        });
    }

    public function down()
    {
        Schema::table('research', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Removes the 'deleted_at' column if rolled back
        });
    }
}
