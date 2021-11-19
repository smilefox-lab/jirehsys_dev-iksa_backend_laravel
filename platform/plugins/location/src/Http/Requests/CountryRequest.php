<?php

namespace Botble\Location\Http\Requests;

use Botble\Location\Enums\DefaultStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CountryRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required',
            'status'      => Rule::in(DefaultStatusEnum::values()),
        ];
    }
}
