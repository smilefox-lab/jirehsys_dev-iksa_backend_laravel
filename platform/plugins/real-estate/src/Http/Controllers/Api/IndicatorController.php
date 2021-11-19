<?php

namespace Botble\RealEstate\Http\Controllers\Api;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class IndicatorController extends BaseController
{
    /**
     * @return JsonResponse
     * @throws Throwable
     */
    public function profitability(Request $request, BaseHttpResponse $response)
    {
        return $response->setData(['profitability' => Property::query()->profitability()]);
    }
}
