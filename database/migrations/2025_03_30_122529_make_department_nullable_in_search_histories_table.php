<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeDepartmentNullableInSearchHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('search_histories', function (Blueprint $table) {
            $table->string('department')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('search_histories', function (Blueprint $table) {
            $table->string('department')->nullable(false)->change();
        });
    }
}