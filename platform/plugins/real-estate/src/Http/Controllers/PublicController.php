<?php

namespace Botble\RealEstate\Http\Controllers;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Http\Requests\SendConsultRequest;
use Botble\RealEstate\Models\Consult;
use Botble\RealEstate\Repositories\Interfaces\ConsultInterface;
use Botble\RealEstate\Repositories\Interfaces\ProjectInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\Setting\Supports\SettingStore;
use Exception;
use Illuminate\Routing\Controller;
use EmailHandler;
use Throwable;

class PublicController extends Controller
{
    /**
     * @var ConsultInterface
     */
    protected $consultRepository;

    /**
     * @param ConsultInterface $consultRepository
     */
    public function __construct(ConsultInterface $consultRepository)
    {
        $this->consultRepository = $consultRepository;
    }

    /**
     * @param SendConsultRequest $request
     * @param BaseHttpResponse $response
     * @param SettingStore $setting
     * @param EmailHandler $emailHandler
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function postSendConsult(
        SendConsultRequest $request,
        BaseHttpResponse $response,
        SettingStore $setting,
        EmailHandler $emailHandler,
        PropertyInterface $propertyRepository,
        ProjectInterface $projectRepository
    ) {
        try {
            /**
             * @var Consult $consult
             */
            $consult = $this->consultRepository->getModel();

            $sendTo = null;
            $link = null;
            $subject = null;

            if ($request->input('type') == 'project') {
                $request->merge(['project_id' => $request->input('data_id')]);
                $project = $projectRepository->findById($request->input('data_id'));
                if ($project) {
                    $link = $project->url;
                    $subject = $project->name;
                }
            } else {
                $request->merge(['property_id' => $request->input('data_id')]);
                $property = $propertyRepository->findById($request->input('data_id'), ['author']);
                if ($property && $property->author->email) {
                    $sendTo = $property->author->email;
                    $link = $property->url;
                    $subject = $property->name;
                }

            }

            $consult->fill($request->input());
            $this->consultRepository->createOrUpdate($consult);

            EmailHandler::setModule(REAL_ESTATE_MODULE_SCREEN_NAME)
                ->setVariableValues([
                    'consult_name'    => $consult->name ?? 'N/A',
                    'consult_email'   => $consult->email ?? 'N/A',
                    'consult_phone'   => $consult->phone ?? 'N/A',
                    'consult_content' => $consult->content ?? 'N/A',
                    'consult_link'    => $link ?? 'N/A',
                    'consult_subject' => $subject ?? 'N/A',
                ])
                ->sendUsingTemplate('notice', $sendTo);

            return $response->setMessage(trans('plugins/real-estate::consult.email.success'));
        } catch (Exception $ex) {
            info($ex->getMessage());
            return $response
                ->setError()
                ->setMessage(trans('plugins/real-estate::consult.email.failed'));
        }
    }
}
