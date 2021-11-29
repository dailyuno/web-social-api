<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YoutubePlayList extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false; 
    
    public $keyType = 'string';

    public function play_list_items()
    {
        return $this->hasMany('App\YoutubePlayListItem', 'play_list_id');
    }

    public function getThumbnailAttribute()
    {
        if ($this->play_list_items->count() < 1) {
            return 'https://i.ytimg.com/img/no_thumbnail.jpg';
        }

        $playListItems = YoutubePlayListItem::where('play_list_id', $this->id);

        $item = $playListItems
            ->join('youtube_videos', 'youtube_videos.id', 'youtube_play_list_items.video_id')
            ->where('youtube_videos.title', '<>', 'Private video')
            ->select('youtube_play_list_items.*')
            ->first();
        
        return "https://i.ytimg.com/vi/" . $item->video->id . "/mqdefault.jpg";
    }
}
