<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('association_id');
            $table->foreign('association_id')->references('id')->on('associations');
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->unsignedInteger('fpb_id')->unique();
            $table->string('name');
            $table->string('image')->nullable();
            $table->unsignedInteger('agegroup_id');
            $table->foreign('agegroup_id')->references('id')->on('agegroups');
            $table->unsignedInteger('competitionlevel_id');
            $table->foreign('competitionlevel_id')->references('id')->on('competitionlevels');
            $table->unsignedInteger('season_id');
            $table->foreign('season_id')->references('id')->on('seasons');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('competitions');
    }
}
