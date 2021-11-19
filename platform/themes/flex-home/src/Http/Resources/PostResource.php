<?php

namespace Theme\FlexHome\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'url'         => $this->url,
            'description' => Str::words($this->description, 35),
            'image'       => $this->image ? get_object_image($this->image, 'small') : null,
        ];
    }
}
