<?php

namespace Botble\Vendor\Providers;

use Botble\Vendor\Models\Transaction;
use Botble\Vendor\Repositories\Caches\TransactionCacheDecorator;
use Botble\Vendor\Repositories\Eloquent\TransactionRepository;
use Botble\Vendor\Repositories\Interfaces\TransactionInterface;
use Botble\Vendor\Models\Package;
use Botble\Vendor\Repositories\Caches\PackageCacheDecorator;
use Botble\Vendor\Repositories\Eloquent\PackageRepository;
use Botble\Vendor\Repositories\Interfaces\PackageInterface;
use Illuminate\Routing\Events\RouteMatched;
use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Vendor\Http\Middleware\RedirectIfVendor;
use Botble\Vendor\Http\Middleware\RedirectIfNotVendor;
use Botble\Vendor\Models\Vendor;
use Botble\Vendor\Models\VendorActivityLog;
use Botble\Vendor\Repositories\Caches\VendorActivityLogCacheDecorator;
use Botble\Vendor\Repositories\Caches\VendorCacheDecorator;
use Botble\Vendor\Repositories\Eloquent\VendorActivityLogRepository;
use Botble\Vendor\Repositories\Eloquent\VendorRepository;
use Botble\Vendor\Repositories\Interfaces\VendorActivityLogInterface;
use Botble\Vendor\Repositories\Interfaces\VendorInterface;
use Event;
use Illuminate\Support\ServiceProvider;
use Throwable;

class VendorServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        config([
            'auth.guards.vendor'     => [
                'driver'   => 'session',
                'provider' => 'vendors',
            ],
            'auth.providers.vendors' => [
                'driver' => 'eloquent',
                'model'  => Vendor::class,
            ],
            'auth.passwords.vendors' => [
                'provider' => 'vendors',
                'table'    => 'vendor_password_resets',
                'expire'   => 60,
            ],
            'auth.guards.vendor-api' => [
                'driver'   => 'passport',
                'provider' => 'vendors',
            ],
        ]);

        $router = $this->app->make('router');

        $router->aliasMiddleware('vendor', RedirectIfNotVendor::class);
        $router->aliasMiddleware('vendor.guest', RedirectIfVendor::class);
        $router->aliasMiddleware('vendor.media', VendorMediaMiddleware::class);

        $this->app->bind(VendorInterface::class, function () {
            return new VendorCacheDecorator(new VendorRepository(new Vendor));
        });

        $this->app->bind(VendorActivityLogInterface::class, function () {
            return new VendorActivityLogCacheDecorator(new VendorActivityLogRepository(new VendorActivityLog));
        });

        $this->app->bind(PackageInterface::class, function () {
            return new PackageCacheDecorator(
                new PackageRepository(new Package)
            );
        });

        $this->app->singleton(TransactionInterface::class, function () {
            return new TransactionCacheDecorator(new TransactionRepository(new Transaction));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/vendor')
            ->loadAndPublishConfigurations(['general', 'permissions', 'assets'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web', 'api'])
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-plugins-vendor',
                    'priority'    => 22,
                    'parent_id'   => null,
                    'name'        => 'plugins/vendor::vendor.name',
                    'icon'        => 'fa fa-users',
                    'url'         => route('vendor.index'),
                    'permissions' => ['vendor.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-package',
                    'priority'    => 23,
                    'parent_id'   => null,
                    'name'        => 'plugins/vendor::package.name',
                    'icon'        => 'fas fa-money-check-alt',
                    'url'         => route('package.index'),
                    'permissions' => ['package.index'],
                ]);

        });

        $this->app->register(EventServiceProvider::class);

        add_filter(IS_IN_ADMIN_FILTER, [$this, 'setInAdmin'], 20, 0);

        add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, [$this, 'addSettings'], 49, 1);
    }

    /**
     * @return bool
     */
    public function setInAdmin(): bool
    {
        return in_array(request()->segment(1), ['account', config('core.base.general.admin_dir')]);
    }

    /**
     * @param null $data
     * @return string
     * @throws Throwable
     */
    public function addSettings($data = null)
    {
        return $data . view('plugins/vendor::settings')->render();
    }
}
