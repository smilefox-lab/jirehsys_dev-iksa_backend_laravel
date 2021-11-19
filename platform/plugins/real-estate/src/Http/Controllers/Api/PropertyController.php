<?php

namespace Botble\RealEstate\Http\Controllers\Api;

use Botble\Base\Http\Controllers\BaseController;
use Botble\RealEstate\Models\Property;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Http\Resources\PropertyListResource;
use Botble\RealEstate\Http\Resources\PropertyResource;
use Botble\RealEstate\Http\Resources\PropertyStatusResource;
use Botble\RealEstate\Http\Resources\PropertyTypeGeneralResource;
use Botble\RealEstate\Http\Resources\PropertyTypeStatusResource;
use Botble\RealEstate\Http\Resources\PropertyWithContractsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;
use Botble\RealEstate\IksaMedia;
use Exception;

class PropertyController extends BaseController
{
    /**
     * @return JsonResponse
     * @throws Throwable
     */
    public function list(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.property')) {
            if ($request->user()->hasAllAccess()) {
                $query = Property::aliasTable()->with('commune');
            } else {
                $query = Property::aliasTable()->with('commune')->filterByCompany($request->user()->company->id ?? null);
            }

            if (!is_null($request->find)) {
                $query = $query->filterToFind($request->find);
            }
            if (!is_null($request->type)) {
                $query = $query->filterByType($request->type);
            }
            if (!is_null($request->company)) {
                $query = $query->filterByCompany($request->user()->company->id ?? $request->company);
            }

            return $response->setData(PropertyListResource::collection($query->orderBy('p.id')->get()));
        }

        return $response
            ->setError()
            ->setCode(403)
            ->setMessage(__('No tiene permiso para acceder a estos datos.'));
    }

    /**
     * @return JsonResponse
     * @throws Throwable
     */
    public function payment(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.property')) {
            if ($request->user()->hasAllAccess()) {
                $query = Property::query()->withContractsJoin();
            } else {
                $query = Property::query()->filterByCompany($request->user()->company->id ?? null);
            }

            if (!is_null($request->find))
            {
                $query = $query->filterToFind($request->find);
            }

            if (!is_null($request->type)) {
                $query = $query->filterByType($request->type);
            }

            if (!is_null($request->company)) {
                $query = $query->filterByCompany($request->user()->company->id ?? $request->company);
            }

            if (!is_null($request->date)) {
                $query = $query->filterByDate($request->date);
            }

            return $response->setData(PropertyWithContractsResource::collection($query->orderBy('id')->get()));
        }

        return $response
            ->setError()
            ->setCode(403)
            ->setMessage(__('No tiene permiso para acceder a estos datos.'));
    }


    /**
     * @return JsonResponse
     * @throws Throwable
     */
    public function show(Request $request, BaseHttpResponse $response, Property $property)
    {
        if ($request->user()->hasPermission('api.property')) {

            if (isset($request->user()->company->id) && !$request->user()->hasCompany() && $request->user()->company->id != $property->company->id) {
                return $response
                    ->setError()
                    ->setCode(404)
                    ->setMessage(__('Esta propiedad pertenece a otra empresa.'));
            }

            return $response->setData(new PropertyResource($property));
        }

        return $response
            ->setError()
            ->setCode(403)
            ->setMessage(__('No tiene permiso para acceder a estos datos.'));
    }

    /**
     * @return JsonResponse
     * @throws Throwable
     */
    public function typeGeneral(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.property')) {

            $properties = Property::leftJoin('re_types as ret', 'ret.id', '=', 're_properties.type_id')
            ->selectRaw('ret.id as id, ret.name as name, count(re_properties.id) as quantity, sum(re_properties.square) as square, sum(re_properties.square_build) as square_build')->groupBy('ret.id')->get();
            return $response->setData(PropertyTypeGeneralResource::collection($properties));
        }

        return $response
            ->setError()
            ->setCode(403)
            ->setMessage(__('No tiene permiso para acceder a estos datos.'));
    }

    /**
     * @return JsonResponse
     * @throws Throwable
     */
    public function typeByCompany(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.property')) {

            $properties = Property::leftJoin('re_types as ret', 'ret.id', '=', 're_properties.type_id')
                            ->leftJoin('companies as c', 'c.id', '=', 're_properties.company_id')
                            ->selectRaw('ret.id as id, ret.name as name, count(re_properties.id) as quantity, sum(re_properties.square) as square, sum(re_properties.square_build) as square_build, c.name as company, c.id as company_id')
                            ->groupBy('ret.id', 're_properties.company_id')
                            ->get();

            return $response->setData($properties);
        }

        return $response
            ->setError()
            ->setCode(403)
            ->setMessage(__('No tiene permiso para acceder a estos datos.'));
    }

    /**
     * @return JsonResponse
     * @throws Throwable
     */
    public function typeStatus(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.property')) {

            if ($request->user()->hasAllAccess()) {
                $query = Property::aliasTable()->typeStatus();
            } else {
                $query = Property::aliasTable()->typeStatus()->filterByCompany($request->user()->company->id ?? null);
            }

            if (!is_null($request->company)) {
                $query = $query->filterByCompany($request->user()->company->id ?? $request->company);
            }
            if (!is_null($request->date)) {
                $query = $query->filterByDate($request->date);
            }

            $query = $query->groupBy('p.status', 't.id')
                           ->orderBy('status')
                           ->get();

            return $response->setData(PropertyTypeStatusResource::collection($query));
        }

        return $response
            ->setError()
            ->setCode(403)
            ->setMessage(__('No tiene permiso para acceder a estos datos.'));
    }

    /**
     * @return JsonResponse
     * @throws Throwable
     */
    public function status(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.property')) {
            if ($request->user()->hasAllAccess()) {
                $query = Property::with('type');
            } else {
                $query = Property::with('type')->filterByCompany($request->user()->company->id ?? null);
            }

            if (!is_null($request->company)) {
                $query = $query->filterByCompany($request->user()->company->id ?? $request->company);
            }
            if (!is_null($request->date)) {
                $query = $query->filterByDate($request->date);
            }

            return $response->setData(PropertyStatusResource::collection($query->orderBy('status')->get()));
        }

        return $response
            ->setError()
            ->setCode(403)
            ->setMessage(__('No tiene permiso para acceder a estos datos.'));
    }


    public function downloadFile($id, $folderId, $fileName, BaseHttpResponse $response)
    {
      try {
        $file =  IksaMedia::handleFileDownload($fileName, 'properties/' . $id, $folderId);
      } catch (\Exception $e) {
        return [
            'error'   => true,
            'message' => $e->getMessage(),
        ];
      }
  
      if (gettype($file) === 'object' || !$file['error']) {
        return $file;
      } else {
        return $response
          ->setError()
          ->setCode(404)
          ->setMessage(__($file['message']));
      }
    }
}
