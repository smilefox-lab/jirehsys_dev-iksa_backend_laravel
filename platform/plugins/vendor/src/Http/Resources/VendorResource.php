<?php

namespace Botble\Vendor\Http\Resources;

use Botble\Vendor\Models\Vendor;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Vendor
 */
class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                      => $this->id,
            'name'                    => $this->getFullName(),
            'first_name'              => $this->first_name,
            'last_name'               => $this->last_name,
            'email'                   => $this->email,
            'phone'                   => $this->phone,
            'avatar'                  => $this->avatar_url,
            'dob'                     => $this->dob,
            'gender'                  => $this->gender,
            'description'             => $this->description,
            'credits' => $this->credits,
        ];
    }
}
