<?php

namespace Botble\RealEstate\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
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
            'id'                 => $this->id,
            'name'               => $this->name,
            'quota'              => intval($this->quota),
            'contribution'       => intval($this->contribution),
            'contribution_quota' => intval($this->contribution_quota),
            'income'             => intval($this->income),
            'start_date'         => date_from_database($this->start_date, config('core.base.general.date_format.date')),
            'end_date'           => date_from_database($this->end_date, config('core.base.general.date_format.date')),
            'cutoff_date'        => date_from_database($this->cutoff_date, config('core.base.general.date_format.date')),
        ];
    }
}
