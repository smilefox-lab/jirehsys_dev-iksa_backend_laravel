<?php

namespace Botble\Vendor\Http\Resources;

use Botble\Vendor\Models\VendorActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin VendorActivityLog
 */
class ActivityLogResource extends JsonResource
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
            'id'          => $this->id,
            'ip_address'  => $this->ip_address,
            'description' => $this->getDescription(),
        ];
    }
}
