<?php

namespace Botble\RealEstate\Providers;

use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\RealEstate\Commands\RenewPropertiesCommand;
use Botble\RealEstate\Models\Contract;
use Botble\RealEstate\Models\Feature;
use Botble\RealEstate\Models\Lessee;
use Botble\RealEstate\Models\Payment;
use Botble\RealEstate\Models\Property;
use Botble\RealEstate\Models\Type;
use Botble\RealEstate\Repositories\Caches\ContractCacheDecorator;
use Botble\RealEstate\Repositories\Caches\FeatureCacheDecorator;
use Botble\RealEstate\Repositories\Caches\LesseeCacheDecorator;
use Botble\RealEstate\Repositories\Caches\PaymentCacheDecorator;
use Botble\RealEstate\Repositories\Caches\PropertyCacheDecorator;
use Botble\RealEstate\Repositories\Caches\TypeCacheDecorator;
use Botble\RealEstate\Repositories\Eloquent\ContractRepository;
use Botble\RealEstate\Repositories\Eloquent\FeatureRepository;
use Botble\RealEstate\Repositories\Eloquent\LesseeRepository;
use Botble\RealEstate\Repositories\Eloquent\PaymentRepository;
use Botble\RealEstate\Repositories\Eloquent\PropertyRepository;
use Botble\RealEstate\Repositories\Eloquent\TypeRepository;
use Botble\RealEstate\Repositories\Interfaces\ContractInterface;
use Botble\RealEstate\Repositories\Interfaces\FeatureInterface;
use Botble\RealEstate\Repositories\Interfaces\LesseeInterface;
use Botble\RealEstate\Repositories\Interfaces\PaymentInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\RealEstate\Repositories\Interfaces\TypeInterface;
use EmailHandler;
use Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Language;

class RealEstateServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->singleton(PropertyInterface::class, function () {
            return new PropertyCacheDecorator(
                new PropertyRepository(new Property)
            );
        });

        $this->app->singleton(FeatureInterface::class, function () {
            return new FeatureCacheDecorator(
                new FeatureRepository(new Feature)
            );
        });

        $this->app->bind(TypeInterface::class, function () {
            return new TypeCacheDecorator(
                new TypeRepository(new Type)
            );
        });

        $this->app->bind(LesseeInterface::class, function () {
            return new LesseeCacheDecorator(
                new LesseeRepository(new Lessee)
            );
        });

        $this->app->bind(ContractInterface::class, function () {
            return new ContractCacheDecorator(
                new ContractRepository(new Contract)
            );
        });

        $this->app->bind(PaymentInterface::class, function () {
            return new PaymentCacheDecorator(
                new PaymentRepository(new Payment)
            );
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/real-estate')
            ->loadAndPublishConfigurations(['permissions', 'email', 'real-estate', 'media'])
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web', 'api'])
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-plugins-real-estate',
                    'priority'    => 2,
                    'parent_id'   => null,
                    'name'        => 'plugins/real-estate::real-estate.name',
                    'icon'        => 'fa fa-bed',
                    'permissions' => ['projects.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-property',
                    'priority'    => 0,
                    'parent_id'   => 'cms-plugins-real-estate',
                    'name'        => 'plugins/real-estate::property.name',
                    'icon'        => null,
                    'url'         => route('property.index'),
                    'permissions' => ['property.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-real-estate-type',
                    'priority'    => 1,
                    'parent_id'   => 'cms-plugins-real-estate',
                    'name'        => 'plugins/real-estate::type.name',
                    'icon'        => null,
                    'url'         => route('type.index'),
                    'permissions' => ['type.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-re-feature',
                    'priority'    => 2,
                    'parent_id'   => 'cms-plugins-real-estate',
                    'name'        => 'plugins/real-estate::feature.name',
                    'icon'        => null,
                    'url'         => route('property_feature.index'),
                    'permissions' => ['property_feature.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-real-estate-lessee',
                    'priority'    => 3,
                    'parent_id'   => 'cms-plugins-real-estate',
                    'name'        => 'plugins/real-estate::lessee.name',
                    'icon'        => null,
                    'url'         => route('lessee.index'),
                    'permissions' => ['lessee.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-real-estate-contract',
                    'priority'    => 4,
                    'parent_id'   => 'cms-plugins-real-estate',
                    'name'        => 'plugins/real-estate::contract.name',
                    'icon'        => null,
                    'url'         => route('contract.index'),
                    'permissions' => ['contract.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-real-estate-payment',
                    'priority'    => 5,
                    'parent_id'   => 'cms-plugins-real-estate',
                    'name'        => 'plugins/real-estate::payment.name',
                    'icon'        => null,
                    'url'         => route('payment.index'),
                    'permissions' => ['payment.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-real-estate-import',
                    'priority'    => 6,
                    'parent_id'   => 'cms-plugins-real-estate',
                    'name'        => 'plugins/real-estate::real-estate.import',
                    'icon'        => null,
                    'url'         => route('real-estate.import'),
                    'permissions' => ['import.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-real-estate-settings',
                    'priority'    => 999,
                    'parent_id'   => 'cms-plugins-real-estate',
                    'name'        => 'plugins/real-estate::real-estate.settings',
                    'icon'        => null,
                    'url'         => route('real-estate.settings'),
                    'permissions' => ['real-estate.settings'],
                ]);

        });

        $this->app->register(CommandServiceProvider::class);

        $this->app->booted(function () {
            $modules = [
                Property::class
            ];

            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                Language::registerModule($modules);
                Language::registerModule([
                    Feature::class,
                    Investor::class,
                    Destiny::class,
                ]);
            }

            $this->app->make(Schedule::class)->command(RenewPropertiesCommand::class)->dailyAt('01:00');
        });

        $this->app->register(HookServiceProvider::class);

        EmailHandler::addTemplateSettings(REAL_ESTATE_MODULE_SCREEN_NAME, config('plugins.real-estate.email'));
    }
}
