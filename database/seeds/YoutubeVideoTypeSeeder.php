<?php

use Illuminate\Database\Seeder;
use App\YoutubeVideoType;

class YoutubeVideoTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        YoutubeVideoType::create([
            'id' => 'default',
            'name' => 'default.jpg',
            'width' => 120,
            'height' => 90
        ]);

        YoutubeVideoType::create([
            'id' => 'medium',
            'name' => 'mqdefault.jpg',
            'width' => 320,
            'height' => 180
        ]);

        YoutubeVideoType::create([
            'id' => 'high',
            'name' => 'hqdefault.jpg',
            'width' => 480,
            'height' => 360
        ]);

        YoutubeVideoType::create([
            'id' => 'standard',
            'name' => 'sddefault.jpg',
            'width' => 640,
            'height' => 480
        ]);

        YoutubeVideoType::create([
            'id' => 'maxres',
            'name' => 'maxresdefault.jpg',
            'width' => 1280,
            'height' => 720
        ]);
    }
}
