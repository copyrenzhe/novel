<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNovelAndChapter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('novel', function (Blueprint $table) {
            $table->string('source')->after('is_over')->default('');
            $table->renameColumn('biquge_url', 'source_link');
        });

        Schema::table('novel', function(Blueprint $table) {
            $table->renameColumn('biquge_url', 'source_link');
        });

        Schema::table('chapter', function(Blueprint $table) {
            $table->renameColumn('biquge_url', 'source_link');
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
    }
}
