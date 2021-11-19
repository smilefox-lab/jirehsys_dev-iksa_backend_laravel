<?php

namespace Botble\Location\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Location\Http\Requests\CommuneRequest;
use Botble\Location\Http\Resources\CommuneResource;
use Botble\Location\Repositories\Interfaces\CommuneInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Exception;
use Botble\Location\Tables\CommuneTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Location\Forms\CommuneForm;
use Botble\Base\Forms\FormBuilder;
use Illuminate\View\View;
use Location;
use Throwable;

class CommuneController extends BaseController
{
    /**
     * @var CommuneInterface
     */
    protected $communeRepository;

    /**
     * CommuneController constructor.
     * @param CommuneInterface $communeRepository
     */
    public function __construct(CommuneInterface $communeRepository)
    {
        $this->communeRepository = $communeRepository;
    }

    /**
     * @param CommuneTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(CommuneTable $table)
    {

        page_title()->setTitle(trans('plugins/location::commune.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/location::commune.create'));

        return $formBuilder->create(CommuneForm::class)->renderForm();
    }

    /**
     * @param CommuneRequest $request
     * @return BaseHttpResponse
     */
    public function store(CommuneRequest $request, BaseHttpResponse $response)
    {
        $commune = $this->communeRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(COMMUNE_MODULE_SCREEN_NAME, $request, $commune));

        return $response
            ->setPreviousUrl(route('commune.index'))
            ->setNextUrl(route('commune.edit', $commune->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $commune = $this->communeRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $commune));

        page_title()->setTitle(trans('plugins/location::commune.edit') . ' "' . $commune->name . '"');

        return $formBuilder->create(CommuneForm::class, ['model' => $commune])->renderForm();
    }

    /**
     * @param $id
     * @param CommuneRequest $request
     * @return BaseHttpResponse
     */
    public function update($id, CommuneRequest $request, BaseHttpResponse $response)
    {
        $commune = $this->communeRepository->findOrFail($id);

        $commune->fill($request->input());

        $this->communeRepository->createOrUpdate($commune);

        event(new UpdatedContentEvent(COMMUNE_MODULE_SCREEN_NAME, $request, $commune));

        return $response
            ->setPreviousUrl(route('commune.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $commune = $this->communeRepository->findOrFail($id);

            $this->communeRepository->delete($commune);

            event(new DeletedContentEvent(COMMUNE_MODULE_SCREEN_NAME, $request, $commune));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $commune = $this->communeRepository->findOrFail($id);
            $this->communeRepository->delete($commune);
            event(new DeletedContentEvent(COMMUNE_MODULE_SCREEN_NAME, $request, $commune));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     */
    public function getList(Request $request, BaseHttpResponse $response)
    {
        $keyword = $request->input('q');

        if (!$keyword) {
            return $response->setData([]);
        }

        $data = $this->communeRepository->advancedGet([
            'condition' => [
                ['communes.name', 'LIKE', '%' . $keyword . '%'],
            ],
            'select'    => ['communes.id', 'communes.name'],
            'take'      => 10,

        ]);

        return $response->setData(CommuneResource::collection($data));
    }

    /**
     * @param BaseHttpResponse $response
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getArrayCommunes(BaseHttpResponse $response)
    {
        return $response->setData(Location::getCommunes());
    }
}
