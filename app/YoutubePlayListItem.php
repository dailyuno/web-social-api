<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YoutubePlayListItem extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false; 

    public $keyType = 'string';

    public function playlist()
    {
        return $this->belongsTo('App\YoutubePlayList');
    }

    public function video()
    {
        return $this->belongsTo('App\YoutubeVideo');
    }

    public function thumbnails()
    {
        return $this->hasMany('App\YoutubeVideoThumbnail', 'video_id');
    }
}
