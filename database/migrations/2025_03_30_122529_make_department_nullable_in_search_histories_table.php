<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeDepartmentNullableInSearchHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('search_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('search_histories', 'department')) {
                $table->string('department')->nullable();
            }
        });
    }
    
    public function down()
    {
        Schema::table('search_histories', function (Blueprint $table) {
            $table->dropColumn('department');
        });
    }
}