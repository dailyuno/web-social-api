<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YoutubeVideo extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false; 
    
    public $keyType = 'string';

    public function thumbnails()
    {
        return $this->hasMany('App\YoutubeVideoThumbnail', 'video_id');
    }
}
