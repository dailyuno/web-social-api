<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYoutubeVideoThumbnailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('youtube_video_thumbnails', function (Blueprint $table) {
            $table->id();
            $table->string('video_id');
            $table->string('video_type_id');

            $table->foreign('video_id')
                ->references('id')
                ->on('youtube_videos')
                ->onDelete('cascade');
            $table->foreign('video_type_id')
                ->references('id')
                ->on('youtube_video_types')
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
        Schema::dropIfExists('youtube_video_thumbnails');
    }
}
