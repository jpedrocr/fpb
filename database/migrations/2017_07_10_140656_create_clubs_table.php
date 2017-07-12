<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('association_id');
            $table->foreign('association_id')->references('id')->on('associations');
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->unsignedInteger('fpb_id')->unique();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('alternative_name')->nullable();
            $table->string('founding_date')->nullable();
            $table->string('president')->nullable();
            $table->string('address')->nullable();
            $table->string('telephone')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('email')->nullable();
            $table->string('url')->nullable();
            // $table->unsignedInteger('venue_id')->nullable();
            // $table->foreign('venue_id')->references('id')->on('venues');
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
        Schema::drop('clubs');
    }
}
