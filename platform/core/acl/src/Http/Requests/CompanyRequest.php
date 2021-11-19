<?php

namespace Botble\ACL\Http\Requests;

use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Botble\ACL\Enums\CompanyStatusEnum;

class CompanyRequest extends Request
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
            'status'      => Rule::in(CompanyStatusEnum::values()),
        ];
    }
}
