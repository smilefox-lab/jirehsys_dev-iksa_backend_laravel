<?php

namespace Botble\RealEstate\Http\Controllers\Api;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Http\Resources\TypeResource;
use Botble\RealEstate\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class TypeController extends BaseController
{
    /**
     * @return JsonResponse
     * @throws Throwable
     */
    public function list(Request $request, BaseHttpResponse $response)
    {
        return $response->setData(TypeResource::collection(Type::all()));
    }
}
