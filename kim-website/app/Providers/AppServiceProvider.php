<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\PaymentPaid;
use App\Listeners\CreateRevenueShare;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register event listener for payment paid events
        Event::listen(PaymentPaid::class, CreateRevenueShare::class);
    }
}
