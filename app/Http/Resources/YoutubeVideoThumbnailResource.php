<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class YoutubeVideoThumbnailResource extends JsonResource
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
            'type' => $this->type->id,
            'url' => "https://i.ytimg.com/vi/" . $this->video->id . "/" . $this->type->name,
            'width' => $this->type->width,
            'height' => $this->type->height,
        ];
    }
}
