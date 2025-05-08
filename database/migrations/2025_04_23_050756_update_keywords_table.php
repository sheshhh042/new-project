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
    Schema::table('keywords', function (Blueprint $table) {
        // Example modification - adjust to your needs
        $table->integer('total')->default(0)->change();
    });
}

public function down()
{
    Schema::table('keywords', function (Blueprint $table) {
        $table->integer('total')->change(); // Revert if needed
    });
}
};
