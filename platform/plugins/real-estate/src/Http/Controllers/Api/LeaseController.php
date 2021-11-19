<?php

namespace Botble\RealEstate\Http\Controllers\Api;

use App\Models\DebtorView;
use App\Models\LeaseView;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Http\Resources\LeaseByCompanyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class LeaseController extends BaseController
{
    /**
     * @return JsonResponse
     * @throws Throwable
     */
    public function list(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.lease')) {
            if ($request->user()->hasAllAccess()) {
                $query = DebtorView::query();
            } else {
                $query = DebtorView::query()->filterByCompany($request->user()->company->id ?? null);
            }

            if (!is_null($request->find)) {
                $query = $query->filterToFindLeases($request->find);
            }
            if (!is_null($request->type)) {
                $query = $query->filterByType($request->type);
            }
            if (!is_null($request->company)) {
                $query = $query->filterByCompany($request->user()->company->id ?? $request->company);
            }

            $aux = $query->byProperty()->orderBy('property_id')->get();
            $ids = $aux->unique('property_id')->pluck('property_id');

            $results = [];
            foreach ($ids as &$id) {
                $obj = new  \stdClass();

                $currentValues = $aux->where('property_id', $id)->toArray();
                $i = 0;
                foreach ($currentValues as $row) {
                    $element = (object) $row;

                    if ($i === 0) {
                        $obj = $element;
                    }

                    $d = date_parse_from_format("Y-m-d", $element->payment_date);
                    $month = $d["month"];
                    $obj->{$month} = $element->paid;
                    $i++;
                }

                array_push($results, $obj);
            }

            return $response->setData(LeaseByCompanyResource::collection($results));
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
    public function byHolding(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.lease')) {
            if (!$request->user()->hasAllAccess()) {

                return $response->setData(LeaseView::all());
            }

            return $response->setData(LeaseView::query()->holding()->get());
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
    public function indicators(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.lease')) {
            if ($request->user()->hasAllAccess()) {
                $query = DebtorView::query()->leaseIndicators($request->company ?? null, $request->date ?? '');
            } else {
                $query = DebtorView::query()->leaseIndicators($request->user()->company->id ?? null, $request->date ?? '');
            }

            return $response->setData($query->get());
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
    public function companiesIndicators(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.lease')) {

            $query = DebtorView::query()->leaseCompaniesIndicators($request->date ?? '');

            return $response->setData($query->get());
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
    public function byHistory(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.lease')) {
            if (!$request->user()->hasAllAccess()) {

                return $response->setData(LeaseView::all());
            }

            return $response->setData([]);
        }

        return $response
            ->setError()
            ->setCode(403)
            ->setMessage(__('No tiene permiso para acceder a estos datos.'));
    }
}
