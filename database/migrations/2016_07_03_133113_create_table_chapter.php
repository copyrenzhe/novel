<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableChapter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('chapters', function(Blueprint $table){
            $table->increments('id');
            $table->integer('n_id');
            $table->string('name');
            $table->longText('content');
            $table->bigInteger('views')->default(0);
            $table->timestamps();
        })
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('chapters');
    }
}
