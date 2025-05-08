<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

   public function up()
{
    Schema::table('search_histories', function (Blueprint $table) {
        $table->integer('count')->default(0)->after('department');
    });
}

public function down()
{
    Schema::table('search_histories', function (Blueprint $table) {
        $table->dropColumn('count');
    });
}
};
