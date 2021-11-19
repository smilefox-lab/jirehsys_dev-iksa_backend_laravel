<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\RealEstate\Enums\DefaultStatusEnum;
use Botble\RealEstate\Enums\LesseeTypeEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class LesseeRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'   => 'required',
            'rut'    => 'required',
            'email'  => "required|max:60|min:6|email",
            'phone'  => 'required',
            'type'   => [
                'required',
                Rule::in(LesseeTypeEnum::values())
            ],
            'status' => Rule::in(DefaultStatusEnum::values()),
        ];
    }
}
