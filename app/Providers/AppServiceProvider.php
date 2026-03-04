<?php

namespace App\Providers;

use Illuminate\Support\Facades\View as FacadesView;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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
        //Share language url to header
        FacadesView::composer('layouts.navbar', function(View $view){
            $localeData = [];

            $currentLocale = LaravelLocalization::getCurrentLocale();
            $supportLocales = LaravelLocalization::getSupportedLocales();

            $localeData['supportedLocales'] = $supportLocales;

            $otherLocale = collect($supportLocales)->filter(function ($value, $key) use ($currentLocale){
                return $key != $currentLocale;
            })->toArray();
            $localeData['otherLocale'] = $otherLocale;
            $localeData['currentLocale'] = $currentLocale;
            $localeData['otherLocaleUrl'] = LaravelLocalization::getLocalizedURL(array_key_first($otherLocale), null, [], true);

            $view->with('localeData', $localeData);
        });
    }
}
