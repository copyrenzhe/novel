<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserNovel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //用户，小说订阅中间表
        Schema::create('user_novel', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('novel_id');
            $table->timestamp('subscribe_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_novel');
    }
}
