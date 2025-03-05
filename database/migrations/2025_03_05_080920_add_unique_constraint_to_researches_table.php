<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToResearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('researches', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('research_title');
            $table->string('author');
            $table->string('location');
            $table->string('subject_area');
            $table->timestamps();
        });

        Schema::table('researches', function (Blueprint $table) {
            $table->unique(['research_title', 'author']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('researches');

        Schema::table('researches', function (Blueprint $table) {
            $table->dropUnique(['research_title', 'author']);
        });
    }
  
};