<?php

use Illuminate\Database\Seeder;
use App\YoutubeVideoThumbnail;
use Illuminate\Support\Facades\Storage;

class YoutubeVideoThumbnailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(storage::get('public/video_thumbnails.json'));

        foreach ($data->items as $item) {
            YoutubeVideoThumbnail::create(collect($item)->all());
        }
    }
}
