<?php

namespace Theme\FlexHome\Http\Resources;

use Botble\RealEstate\Enums\PropertyTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $price = format_price($this->price, $this->currency);

        if ($this->type == PropertyTypeEnum::RENT) {
            $price .= ' / ' . Str::lower($this->period->label());
        }

        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'url'             => $this->url,
            'description'     => $this->description,
            'image'           => $this->image ? get_object_image($this->image, 'small') : null,
            'price'           => $price,
            'location'        => $this->location,
            'number_bedroom'  => $this->number_bedroom,
            'number_bathroom' => $this->number_bathroom,
            'square'          => $this->square,
            'type'            => $this->type,
            'period'          => $this->period,
            'status_html'     => $this->status->toHtml(),
        ];
    }
}
