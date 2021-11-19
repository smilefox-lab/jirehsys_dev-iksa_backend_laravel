<?php

namespace Botble\Career\Providers;

use Botble\Career\Models\Career;
use Illuminate\Support\ServiceProvider;
use Botble\Career\Repositories\Caches\CareerCacheDecorator;
use Botble\Career\Repositories\Eloquent\CareerRepository;
use Botble\Career\Repositories\Interfaces\CareerInterface;
use Botble\Base\Supports\Helper;
use Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;
use Language;
use SeoHelper;
use SlugHelper;

class CareerServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    public function register()
    {
        $this->app->bind(CareerInterface::class, function () {
            return new CareerCacheDecorator(new CareerRepository(new Career));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/career')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web']);

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-career',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/career::career.name',
                'icon'        => 'far fa-newspaper',
                'url'         => route('career.index'),
                'permissions' => ['career.index'],
            ]);
        });

        $modules = [Career::class];
        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            Language::registerModule($modules);
        }

        $this->app->booted(function () use ($modules) {
            SlugHelper::registerModule($modules);
            SlugHelper::setPrefix(Career::class, 'careers');

            SeoHelper::registerModule($modules);
        });
    }
}
