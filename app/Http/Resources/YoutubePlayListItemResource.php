<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class YoutubePlayListItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'playlistId' => $this->play_list_id,
            'videoId' => $this->video_id,
            'publishedAt' => $this->published_at,
            'video' => new YoutubeVideoResource($this->video)
        ];
    }
}
