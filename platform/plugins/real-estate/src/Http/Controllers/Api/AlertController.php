<?php

namespace Botble\RealEstate\Http\Controllers\Api;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Http\Resources\AlertResource;
use Botble\RealEstate\Models\Contract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AlertController extends BaseController
{
    /**
     * @return JsonResponse
     * @throws Throwable
     */
    public function list(Request $request, BaseHttpResponse $response)
    {
        return $response->setData(AlertResource::collection(Contract::query()->alert()->orderBy('end_date')->get()));
    }
}
