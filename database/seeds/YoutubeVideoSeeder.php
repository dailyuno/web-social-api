<?php

use Illuminate\Database\Seeder;
use App\YoutubeVideo;
use Illuminate\Support\Facades\Storage;

class YoutubeVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(storage::get('public/videos.json'));
        
        foreach ($data->items as $item) {
            YoutubeVideo::create(collect($item)->all());
        }
    }
}
