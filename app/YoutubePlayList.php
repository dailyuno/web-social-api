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
}
