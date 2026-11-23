<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

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
        $stateful = config('sanctum.stateful', []);
        $additionalHosts = [];

        if (isset(Sanctum::$currentRequestHostPlaceholder)) {
            $additionalHosts[] = Sanctum::$currentRequestHostPlaceholder;
        }

        if (! $this->app->runningInConsole() && $this->app->bound('request')) {
            $request = $this->app->make('request');
            $additionalHosts[] = $request->getHttpHost();
            $additionalHosts[] = $request->getHost();
        }

        $additionalHosts = array_filter($additionalHosts);

        if (! empty($additionalHosts)) {
            config([
                'sanctum.stateful' => array_values(array_unique(array_merge($stateful, $additionalHosts))),
            ]);
        }
    }
}
