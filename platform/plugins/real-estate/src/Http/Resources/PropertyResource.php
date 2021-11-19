<?php

namespace Botble\RealEstate\Http\Resources;

use Botble\ACL\Http\Resources\CompanyResource;
use Botble\Location\Http\Resources\CommuneResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
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
            'id'                 => $this->id,
            'name'               => $this->name,
            'description'        => $this->description,
            'location'           => $this->location,
            'images'             => $this->imagesUrl,
            'square'             => $this->square,
            'square_build'       => $this->square_build,
            'status'             => $this->status->label(),
            'commune'            => new CommuneResource($this->commune),
            'company'            => new CompanyResource($this->company),
            'type'               => new TypeResource($this->type),
            'role'               => $this->role,
            'leaves'             => $this->leaves,
            'number'             => $this->number,
            'year'               => $this->year,
            'buy'                => $this->buy,
            'date_deed'          => date_from_database($this->date_deed, config('core.base.general.date_format.date')),
            'appraisal'          => intval($this->appraisal),
            'pesos'              => intval($this->pesos),
            'uf'                 => intval($this->uf),
            'coordinates'        => json_decode($this->coordinates, true),
            'profitability'      => $this->profitability,
            'payments'           => PaymentResource::collection($this->payments()->orderBy('date', 'desc')->get()),
            'contract'           => new ContractResource($contract),
            'lessee'             => isset($contract->lessee) ? new LesseeResource($contract->lessee) : null,
            'top_payments'       => PaymentResource::collection($this->payments()->orderBy('date', 'desc')->limit(3)->get()),
            'files_technical'    => $this->files_technical,
            'files_legal'        => $this->files_legal,
            'files_plane'        => $this->files_plane,
        ];
    }
}
