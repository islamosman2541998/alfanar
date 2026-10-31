<?php

namespace App\Providers;

use App\Http\Middleware\SetAdminLocale;
use App\Http\Middleware\SetLocale;
use App\Support\Seo;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(Seo::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Behind the hosting SSL proxy the request looks like http; keep every generated link on https
        // (images with srcset loaded over http are blocked by browsers on an https page).
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // Livewire requests keep the language of the page they came from.
        Livewire::addPersistentMiddleware([SetLocale::class, SetAdminLocale::class]);
    }
}
