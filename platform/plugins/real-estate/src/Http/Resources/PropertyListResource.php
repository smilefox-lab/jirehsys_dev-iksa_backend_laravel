<?php

namespace Botble\RealEstate\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyListResource extends JsonResource
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
        $contract = $this->contracts->last();

        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'location'      => $this->location,
            'image'         => $this->imageUrl,
            'square'        => $this->square,
            'appraisal'     => intval($this->appraisal),
            'status'        => $this->status->label(),
            'type'          => new TypeResource($this->type),
            'role'          => $this->role,
            'contract'      => new ContractResource($contract),
            'profitability' => intval($this->profitability),
        ];
    }
}
