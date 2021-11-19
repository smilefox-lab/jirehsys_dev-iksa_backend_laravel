<?php

namespace Botble\RealEstate\Http\Resources;

use Botble\ACL\Http\Resources\CompanyResource;
use Botble\Location\Http\Resources\CommuneResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyStatusResource extends JsonResource
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
            'id'        => $this->id,
            'name'    => $this->name,
            'commune'   => $this->commune->name,
            'square'    => $this->square,
            'status'    => $this->status->label(),
            'type'      => new TypeResource($this->type),
            'date_deed' => date_from_database($this->date_deed, config('core.base.general.date_format.date')),
            'appraisal' => intval($this->appraisal),
        ];
    }
}
