<?php

namespace Botble\RealEstate\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyTypeStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [

            'name'     => $this->name,
            'quantity' => $this->quantity,
            'square'   => $this->square,
            'status'   => $this->status->label(),
        ];
    }
}
