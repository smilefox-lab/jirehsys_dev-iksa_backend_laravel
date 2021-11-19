<?php

namespace Botble\RealEstate\Http\Requests;

use App\Rules\ExcelRule;
use Botble\Support\Http\Requests\Request;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class ImportRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     *
     * @throws FileNotFoundException
     */
    public function rules()
    {
        return [
            'file' => [new ExcelRule($this->file)]
        ];
    }
}
