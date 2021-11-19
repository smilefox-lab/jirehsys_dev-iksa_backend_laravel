<?php

namespace Botble\RealEstate\Http\Controllers\Api;

use App\Models\DebtorView;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class DebtorController extends BaseController
{
    /**
     * @return JsonResponse
     * @throws Throwable
     */
    public function list(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.lease')) {
            if ($request->user()->hasAllAccess()) {
                $query = DebtorView::query()->status(['Retraso', 'Mora'], 'or');
            } else {
                $query = DebtorView::query()->status(['Retraso', 'Mora'], 'or')->filterByCompany($request->user()->company->id ?? null);
            }

            if (!is_null($request->find)) {
                $query = $query->filterToFindDebtors($request->find);
            }
            if (!is_null($request->type)) {
                $query = $query->filterByType($request->type);
            }
            if (!is_null($request->company)) {
                $query = $query->filterByCompany($request->user()->company->id ?? $request->company);
            }

            return $response->setData($query->orderBy('property_id')->selectByLessee()->get());
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
    public function getTop(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.lease')) {
            $query = DebtorView::query()->top();

            if (!is_null($request->company)) {
                $query = $query->filterByCompany($request->user()->company->id ?? $request->company);
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
    public function byOverview(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.lease')) {
            if ($request->user()->hasAllAccess()) {
                $query = DebtorView::overview($request->company ?? null, $request->date ?? '');
            } else {
                $query = DebtorView::overview($request->user()->company->id ?? null, $request->date ?? '');
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
    public function byHistoryGraphDefaultAndDelay(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.lease')) {
            if ($request->user()->hasAllAccess()) {
                $query = DebtorView::query()->HistoryGraphDefaultAndDelay();
            } else {
                $query = DebtorView::query()->HistoryGraphDefaultAndDelay()->filterByCompany($request->user()->company->id ?? null);
            }

            if (!is_null($request->company)) {
                $query = $query->filterByCompany($request->user()->company->id ?? $request->company);
            }

            return $response->setData($query->get());
        }

        return $response
            ->setError()
            ->setCode(403)
            ->setMessage(__('No tiene permiso para acceder a estos datos.'));
    }
}
