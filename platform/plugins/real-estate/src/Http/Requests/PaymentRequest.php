<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\Support\Http\Requests\Request;

class PaymentRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'contract_id' => 'required',
            'date'        => 'required',
            'amount'      => 'required',
        ];
    }
}
