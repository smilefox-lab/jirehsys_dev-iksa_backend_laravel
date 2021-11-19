<?php

namespace Botble\RealEstate\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LesseeResource extends JsonResource
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
            'id'     => $this->id,
            'name'   => $this->name,
            'rut'    => $this->rut,
            'email'  => $this->email,
            'phone'  => $this->phone,
            'type'   => $this->type,
            'contact_name' => $this->contact_name
        ];
    }
}
