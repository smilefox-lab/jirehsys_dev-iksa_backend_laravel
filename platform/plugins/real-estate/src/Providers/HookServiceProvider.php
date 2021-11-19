<?php

namespace Botble\RealEstate\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Botble\RealEstate\Repositories\Interfaces\ConsultInterface;
use Throwable;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @throws Throwable
     */
    public function boot()
    {
        add_filter(BASE_FILTER_TOP_HEADER_LAYOUT, [$this, 'registerTopHeaderNotification'], 130);
        add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getUnReadCount'], 130, 2);
        add_filter(BASE_FILTER_AFTER_SETTING_EMAIL_CONTENT, [$this, 'addConsultSetting'], 990, 1);
    }

    /**
     * @param string $options
     * @return string
     *
     * @throws Throwable
     */
    public function registerTopHeaderNotification($options)
    {
        // if (Auth::user()->hasPermission('consults.edit')) {
        //     $consults = $this->app->make(ConsultInterface::class)
        //         ->getUnread(['id', 'name', 'email', 'phone', 'created_at']);

        //     if ($consults->count() == 0) {
        //         return null;
        //     }

        //     return $options . view('plugins/real-estate::notification', compact('consults'))->render();
        // }
        return null;
    }

    /**
     * @param $number
     * @param $menuId
     * @return string
     * @throws BindingResolutionException
     */
    public function getUnReadCount($number, $menuId)
    {
        if ($menuId == 'cms-plugins-consult') {
            $unread = $this->app->make(ConsultInterface::class)->countUnread();
            if ($unread > 0) {
                return '<span class="badge badge-success">' . $unread . '</span>';
            }
        }

        return $number;
    }

    /**
     * @param null $data
     * @return string
     * @throws Throwable
     */
    public function addConsultSetting($data = null)
    {
        return $data . view('plugins/real-estate::setting')->render();
    }
}
