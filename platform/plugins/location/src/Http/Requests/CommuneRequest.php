<?php

namespace Botble\Location\Http\Requests;

use Botble\Location\Enums\DefaultStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CommuneRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'       => 'required',
            'region_id'  => 'required',
            'status'     => Rule::in(DefaultStatusEnum::values()),
        ];
    }
}
