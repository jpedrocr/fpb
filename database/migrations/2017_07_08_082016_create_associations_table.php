<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssociationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('associations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->unsignedInteger('fpb_id')->unique();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('president')->nullable();
            $table->string('technical_director')->nullable();
            $table->string('cad_president')->nullable();
            $table->string('address')->nullable();
            $table->string('telephone')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('email')->nullable();
            $table->string('url')->nullable();
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
        Schema::drop('associations');
    }
}
