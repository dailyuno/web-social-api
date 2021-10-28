<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YoutubeVideoType extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false; 
    
    public $keyType = 'string';
}
