<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            YoutubeVideoTypeSeeder::class,
            YoutubeVideoSeeder::class,
            YoutubeVideoThumbnailSeeder::class,
            YoutubePlayListSeeder::class,
            YoutubePlayListItemSeeder::class,
        ]);
    }
}
