<?php

namespace Botble\RealEstate\Http\Controllers;

use App\Imports\BulkImport;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Http\Requests\ImportRequest;
use Excel;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ImportController extends BaseController
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        page_title()->setTitle(trans('plugins/real-estate::real-estate.import'));

        return view('plugins/real-estate::import.index');
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function import(ImportRequest $request, BaseHttpResponse $response)
    {
        if ($request->hasFile('file')) {
            try {
                Excel::import(new BulkImport, $request->file('file'));
                return $response
                    ->setNextUrl(route('real-estate.import'))
                    ->setMessage(trans('core/base::notices.update_success_message'));
            } catch (Throwable $th) {
                return $response
                ->setNextUrl(route('real-estate.import'))
                ->setError()
                ->setMessage($th->getMessage());
            }

        }

        return $response
                ->setNextUrl(route('real-estate.import'))
                ->setError()
                ->setMessage(trans('core/base::notices.no_select_file'));

    }
}
