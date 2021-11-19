<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PropertyRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'               => 'required',
            'role'               => 'required',
            'description'        => 'max:350',
            'commune_id'         => 'required',
            'square'             => 'required|numeric|min:0',
            'square_quota'       => 'numeric|min:0',
            'location'           => 'required',
            'coordinates'        => 'required',
            'status'             => Rule::in(PropertyStatusEnum::values()),
            'company_id'         => 'required',
            'type_id'            => 'required',
            'buy'                => 'numeric',
            'appraisal'          => 'numeric',
            'appraisal_coin'     => 'numeric',
            'uf'                 => 'numeric',
            'rent'               => 'numeric',
            'contribution'       => 'numeric',
            'contribution_quota' => 'numeric',
            'rent_cost'          => 'numeric',
            'income'             => 'numeric'
        ];
    }
}
