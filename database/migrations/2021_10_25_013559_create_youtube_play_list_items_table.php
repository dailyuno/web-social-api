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
            $table->id();
            $table->string('play_list_id');
            $table->string('video_id');
            $table->dateTimeTz('published_at')->nullable();
            
            $table->foreign('play_list_id')
                ->references('id')
                ->on('youtube_play_lists')
                ->onDelete('cascade');
            
            $table->foreign('video_id')
                ->references('id')
                ->on('youtube_videos')
                ->onDelete('cascade');
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
