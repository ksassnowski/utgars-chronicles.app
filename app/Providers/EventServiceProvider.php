<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\EventDeleted;
use App\Events\PeriodDeleted;
use App\Listeners\ReorderEvents;
use App\Listeners\ReorderPeriods;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        PeriodDeleted::class => [
            ReorderPeriods::class,
        ],

        EventDeleted::class => [
            ReorderEvents::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
