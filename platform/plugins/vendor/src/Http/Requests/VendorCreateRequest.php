<?php

namespace Botble\Vendor\Http\Requests;

use Botble\Support\Http\Requests\Request;

class VendorCreateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:120|min:2',
            'last_name'  => 'required|max:120|min:2',
            'email'      => 'required|max:60|min:6|email|unique:vendors',
            'password'   => 'required|min:6|confirmed',
        ];
    }
}
