<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YoutubeVideoThumbnail extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function type()
    {
        return $this->belongsTo('App\YoutubeVideoType', 'video_type_id');
    }

    public function video()
    {
        return $this->belongsTo('App\YoutubeVideo', 'video_id');
    }
}
