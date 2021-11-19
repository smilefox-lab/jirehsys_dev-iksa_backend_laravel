<?php

namespace Botble\RealEstate\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaseByCompanyResource extends JsonResource
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
            'property_id'   => $this->property_id,
            'property_name' => $this->property_name,
            'appraisal'     => intval($this->appraisal),
            'paid'          => intval($this->paid),
            'expected'      => intval($this->expected),
            'status'        => $this->status,
            'months'        => $this->months,
            'lessee_name'   => $this->lessee_name,
            'lessee_contact_name'   => $this->lessee_contact_name,
            'enero'         => !isset($this->{'0'})? 0 : intval($this->{'0'}),
            'febrero'       => !isset($this->{'1'})? 0 : intval($this->{'1'}),
            'marzo'         => !isset($this->{'2'})? 0 : intval($this->{'2'}),
            'abril'         => !isset($this->{'3'})? 0 : intval($this->{'3'}),
            'mayo'          => !isset($this->{'4'})? 0 : intval($this->{'4'}),
            'junio'         => !isset($this->{'5'})? 0 : intval($this->{'5'}),
            'julio'         => !isset($this->{'6'})? 0 : intval($this->{'6'}),
            'agosto'        => !isset($this->{'7'})? 0 : intval($this->{'7'}),
            'septiembre'    => !isset($this->{'8'})? 0 : intval($this->{'8'}),
            'octubre'       => !isset($this->{'9'})? 0 : intval($this->{'9'}),
            'noviembre'     => !isset($this->{'10'})? 0 : intval($this->{'10'}),
            'diciembre'     => !isset($this->{'11'})? 0 : intval($this->{'11'})
        ];
    }
}
