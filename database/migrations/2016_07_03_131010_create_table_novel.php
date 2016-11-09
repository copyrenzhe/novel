<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNovel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('novel', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('author_id');
            $table->enum('type', ['xuanhuan', 'xiuzhen', 'dushi', 'lishi', 'wangyou', 'kehuan', 'mingzhu', 'other'])->default('xuanhuan');
            $table->string('cover');
            $table->bigInteger('hot')->default(0);
            $table->integer('sort')->default(0);
            $table->boolean('is_over');
            $table->string('biquge_url')->nullable()->unique();
            $table->integer('chapter_num')->default(0);
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
        Schema::drop('novel');
    }
}
