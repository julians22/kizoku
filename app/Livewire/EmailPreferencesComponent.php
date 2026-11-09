<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class EmailPreferencesComponent extends Component
{
    public bool $newsletterEnabled = false;

    public bool $emailNotificationsEnabled = false;

    #[Computed]
    public function englishUrl(): string
    {
        return LaravelLocalization::getLocalizedURL('en');
    }

    #[Computed]
    public function indonesianUrl(): string
    {
        return LaravelLocalization::getLocalizedURL('id');
    }

    #[Computed]
    public function currentLocale(): string
    {
        return app()->getLocale();
    }

    public function render()
    {
        return view('livewire.email-preferences-component');
    }
}
