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
        Schema::create('chapter', function(Blueprint $table){
            $table->increments('id');
            $table->integer('novel_id')->index();
            $table->string('name');
            $table->longText('content')->nullable();
            $table->bigInteger('views')->default(0);
            $table->string('biquge_url')->nullable()->unique();
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
        //
        Schema::drop('chapter');
    }
}
