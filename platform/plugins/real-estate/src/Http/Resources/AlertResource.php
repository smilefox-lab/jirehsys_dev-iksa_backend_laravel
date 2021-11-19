<?php

namespace Botble\RealEstate\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlertResource extends JsonResource
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
            'property_id' => $this->property_id,
            'end_date'    => date_from_database($this->end_date, config('core.base.general.date_format.date')),
            'cutoff_day'  => Carbon::now()->diffInDays(Carbon::parse($this->end_date)),
        ];
    }
}
