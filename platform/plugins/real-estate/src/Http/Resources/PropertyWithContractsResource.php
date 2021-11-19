<?php

namespace Botble\RealEstate\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyWithContractsResource extends JsonResource
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
            'id'            => $this->id,
            'property_name' => $this->property_name,
            'status'        => $this->status->label(),
            'company_id'    => $this->company_id,
            'company_name'  => $this->company_name,
            'type'          => $this->type->name,
            'commune'       => $this->commune->name,
            'lessee_name'   => $this->lessee_name,
            'lessee_contact_name'   => $this->lessee_contact_name,
            'start_date'    => date_from_database($this->start_date, config('core.base.general.date_format.date')),
            'role'          => $this->role,
            'square'        => $this->square,
            'appraisal'     => intval($this->appraisal),
            'cutoff_date'   => date_from_database($this->cutoff_date, config('core.base.general.date_format.date')),
            'enero'         => intval($this->enero),
            'febrero'       => intval($this->febrero),
            'marzo'         => intval($this->marzo),
            'abril'         => intval($this->abril),
            'mayo'          => intval($this->mayo),
            'junio'         => intval($this->junio),
            'julio'         => intval($this->julio),
            'agosto'        => intval($this->agosto),
            'septiembre'    => intval($this->septiembre),
            'octubre'       => intval($this->octubre),
            'noviembre'     => intval($this->noviembre),
            'diciembre'     => intval($this->diciembre),
            'end_date'      => date_from_database($this->end_date, config('core.base.general.date_format.date')),
        ];
    }
}
