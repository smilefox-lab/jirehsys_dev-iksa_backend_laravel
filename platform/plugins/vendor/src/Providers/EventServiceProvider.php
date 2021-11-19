<?php

namespace Botble\Vendor\Providers;

use Botble\Vendor\Listeners\UpdatedContentListener;
use Botble\Base\Events\UpdatedContentEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
    ];
}
