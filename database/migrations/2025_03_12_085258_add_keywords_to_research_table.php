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
        if (!Schema::hasColumn('research', 'keywords')) {
            $table->text('keywords')->nullable(); // Adding a nullable keywords column
        }
    });
}

public function down()
{
    Schema::table('research', function (Blueprint $table) {
        if (Schema::hasColumn('research', 'keywords')) {
            $table->dropColumn('keywords'); // For rolling back the migration
        }
    });
}

};
