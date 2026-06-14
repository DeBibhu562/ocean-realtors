<?php

namespace App\Providers;

use App\Events\LeadCreated;
use App\Listeners\SendNewLeadNotification;
use App\Models\Agent;
use App\Models\Property;
use App\Observers\PropertyObserver;
use App\Services\Geocoding\GeocoderInterface;
use App\Services\Geocoding\GoogleGeocoder;
use App\Support\SeoLandingFourBhkPageFactory;
use App\Support\SeoLandingPlotPageFactory;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GeocoderInterface::class, GoogleGeocoder::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        config([
            'seo_landing_pages.pages' => array_merge(
                config('seo_landing_pages.pages', []),
                SeoLandingPlotPageFactory::all(),
                SeoLandingFourBhkPageFactory::all(),
            ),
        ]);

        Vite::prefetch(concurrency: 3);

        Event::listen(LeadCreated::class, SendNewLeadNotification::class);

        Property::observe(PropertyObserver::class);

        // Agent URLs use slug by default (getRouteKeyName). Admin UI historically passed numeric id,
        // which Laravel interpreted as a slug and returned 404. Accept id or slug for {agent}.
        Route::bind('agent', function (string $value): Agent {
            if (ctype_digit($value)) {
                return Agent::query()->where('id', (int) $value)->firstOrFail();
            }

            return Agent::query()->where('slug', $value)->firstOrFail();
        });

        // Public cards and admin both may pass slug or numeric id in {property}.
        Route::bind('property', function (string $value): Property {
            if (ctype_digit($value)) {
                return Property::query()->where('id', (int) $value)->firstOrFail();
            }

            return Property::query()->where('slug', $value)->firstOrFail();
        });
    }
}
