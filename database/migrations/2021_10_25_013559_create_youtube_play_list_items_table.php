<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYoutubePlayListItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('youtube_play_list_items', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('youtube_play_lists_id');
            $table->string('title');
            $table->text('description')->nullable();
            // $table->timestamps();
            $table->dateTimeTz('publishedAt');

            $table->foreign('youtube_play_lists_id')->references('id')->on('youtube_play_lists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('youtube_play_list_items');
    }
}
