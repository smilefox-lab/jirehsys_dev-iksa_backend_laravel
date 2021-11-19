<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ContractRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'property_id' => 'required',
            'lessee_id'   => 'required',
            'start_date'  => 'required',
            'end_date'    => 'required',
            'cutoff_date' => 'required',
            'name'        => 'required',
            'quota'       => 'required',
        ];
    }
}
