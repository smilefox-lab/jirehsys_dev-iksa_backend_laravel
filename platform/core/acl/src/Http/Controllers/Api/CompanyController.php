<?php

namespace Botble\ACL\Http\Controllers\Api;

use Botble\ACL\Http\Resources\CompanyResource;
use Botble\ACL\Models\Company;
use Botble\ACL\Repositories\Interfaces\CompanyInterface;
use Botble\ACL\Traits\PermissionTrait;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Http\Request;
use Botble\RealEstate\IksaMedia;
use Exception;

class CompanyController extends BaseController
{
    /**
     * Display all companies
     * @return CompanyResource
     */
    public function index(Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->hasPermission('api.company')) {
            if ($request->user()->hasAllAccess()) {
                return $response->setData(CompanyResource::collection(Company::all()));
            }

            return $response->setData(CompanyResource::collection(Company::where('id', $request->user()->company->id)->get()));
        }

        return $response
            ->setError()
            ->setCode(403)
            ->setMessage(__('No tiene permiso para listar los datos de la compañia.'));
    }

    public function downloadFile($id, $fileName, BaseHttpResponse $response)
    {
      try {
        $file =  IksaMedia::handleFileDownload($fileName, 'company', $id);
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
