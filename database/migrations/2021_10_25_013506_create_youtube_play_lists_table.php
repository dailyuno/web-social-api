<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYoutubePlayListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('youtube_play_lists', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTimeTz('published_at')->nullable();
            $table->enum('lang', ['kr', 'en'])->default('kr');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('youtube_play_lists');
    }
}
