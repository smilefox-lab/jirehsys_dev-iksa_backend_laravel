<?php

namespace Botble\RealEstate\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyTypeConcreteResource extends JsonResource
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
            'id'         => $this->id,
            'name'       => $this->name,
            'quantity'   => $this->quantity,
            'square'     => $this->square,
            'company'    => $this->company,
            'company_id' => $this->company_id,
            'square_build'  => $this->square_build
        ];
    }
}
