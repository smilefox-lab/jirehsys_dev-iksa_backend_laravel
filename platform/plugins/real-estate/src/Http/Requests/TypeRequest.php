<?php

namespace Botble\RealEstate\Http\Requests;

use Botble\RealEstate\Enums\DefaultStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TypeRequest extends Request
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
            'status' => Rule::in(DefaultStatusEnum::values()),
        ];
    }
}
