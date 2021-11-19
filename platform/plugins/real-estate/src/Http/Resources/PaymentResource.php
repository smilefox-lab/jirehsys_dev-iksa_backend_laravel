<?php

namespace Botble\RealEstate\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'contract_id' => $this->contract_id,
            'date'        => date_from_database($this->date, config('core.base.general.date_format.date')),
            'paid'        => intval($this->amount),
            'expected'    => intval($this->contract->quota),
        ];
    }
}
