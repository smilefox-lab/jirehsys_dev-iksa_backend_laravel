<?php

namespace Botble\RealEstate\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\RealEstate\Http\Requests\DestinyRequest;
use Botble\RealEstate\Repositories\Interfaces\DestinyInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Exception;
use Botble\RealEstate\Tables\DestinyTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Forms\DestinyForm;
use Botble\Base\Forms\FormBuilder;
use Illuminate\View\View;
use Throwable;

class DestinyController extends BaseController
{
    /**
     * @var DestinyInterface
     */
    protected $destinyRepository;

    /**
     * DestinyController constructor.
     * @param DestinyInterface $destinyRepository
     */
    public function __construct(DestinyInterface $destinyRepository)
    {
        $this->destinyRepository = $destinyRepository;
    }

    /**
     * @param DestinyTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(DestinyTable $table)
    {

        page_title()->setTitle(trans('plugins/real-estate::destiny.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/real-estate::destiny.create'));

        return $formBuilder->create(DestinyForm::class)->renderForm();
    }

    /**
     * Insert new Destiny into database
     *
     * @param DestinyRequest $request
     * @return BaseHttpResponse
     */
    public function store(DestinyRequest $request, BaseHttpResponse $response)
    {
        $destiny = $this->destinyRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(DESTINY_MODULE_SCREEN_NAME, $request, $destiny));

        return $response
            ->setPreviousUrl(route('destiny.index'))
            ->setNextUrl(route('destiny.edit', $destiny->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * Show edit form
     *
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $destiny = $this->destinyRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $destiny));

        page_title()->setTitle(trans('plugins/real-estate::destiny.edit') . ' "' . $destiny->name . '"');

        return $formBuilder->create(DestinyForm::class, ['model' => $destiny])->renderForm();
    }

    /**
     * @param $id
     * @param DestinyRequest $request
     * @return BaseHttpResponse
     */
    public function update($id, DestinyRequest $request, BaseHttpResponse $response)
    {
        $destiny = $this->destinyRepository->findOrFail($id);

        $destiny->fill($request->input());

        $this->destinyRepository->createOrUpdate($destiny);

        event(new UpdatedContentEvent(DESTINY_MODULE_SCREEN_NAME, $request, $destiny));

        return $response
            ->setPreviousUrl(route('destiny.index'))
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
            $destiny = $this->destinyRepository->findOrFail($id);

            $this->destinyRepository->delete($destiny);

            event(new DeletedContentEvent(DESTINY_MODULE_SCREEN_NAME, $request, $destiny));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.cannot_delete'));
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
            $destiny = $this->destinyRepository->findOrFail($id);
            $this->destinyRepository->delete($destiny);
            event(new DeletedContentEvent(DESTINY_MODULE_SCREEN_NAME, $request, $destiny));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
