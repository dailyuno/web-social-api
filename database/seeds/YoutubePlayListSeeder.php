<?php

use Illuminate\Database\Seeder;
use App\YoutubePlayList;
use Illuminate\Support\Facades\Storage;

class YoutubePlayListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(storage::get('public/play_lists.json'));
        
        foreach ($data->items as $item) {
            YoutubePlayList::create(collect($item)->all());
        }
    }
}
