<?php

use Illuminate\Database\Seeder;
use App\YoutubePlayListItem;
use Illuminate\Support\Facades\Storage;

class YoutubePlayListItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = json_decode(storage::get('public/play_list_items.json'));
        
        foreach ($data->items as $item) {
            YoutubePlayListItem::create(collect($item)->all());
        }
    }
}
