<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYoutubeThumbnailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('youtube_thumbnails', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_thumbnailable_id');
            $table->string('youtube_thumbnailable_type');
            $table->string('url');
            $table->enum('type', ['default', 'medium', 'high', 'standard', 'maxres']);
            $table->integer('width');
            $table->integer('height');
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
        Schema::dropIfExists('youtube_thumbnails');
    }
}
