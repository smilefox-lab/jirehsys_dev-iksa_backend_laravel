<?php

namespace Botble\RealEstate\Http\Controllers;

use Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\RealEstate\Http\Requests\UpdateSettingsRequest;
use Botble\Setting\Supports\SettingStore;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class RealEstateController extends BaseController
{
    /**
     * @return Factory|View
     */
    public function getSettings()
    {
        page_title()->setTitle(trans('plugins/real-estate::real-estate.settings'));

        Assets::addScripts(['jquery-ui'])
            ->addScriptsDirectly([
                'vendor/core/plugins/real-estate/js/currencies.js',
            ])
            ->addStylesDirectly([
                'vendor/core/plugins/real-estate/css/currencies.css',
            ]);

        return view('plugins/real-estate::settings.index');
    }

    /**
     * @param UpdateSettingsRequest $request
     * @param BaseHttpResponse $response
     * @param StoreCurrenciesService $service
     * @param SettingStore $settingStore
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function postSettings(
        UpdateSettingsRequest $request,
        BaseHttpResponse $response,
        SettingStore $settingStore
    ) {
        foreach ($request->except(['_token', 'currencies', 'deleted_currencies']) as $settingKey => $settingValue) {
            $settingStore->set($settingKey, $settingValue);
        }

        $settingStore->save();

        return $response
            ->setNextUrl(route('real-estate.settings'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}
