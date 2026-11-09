<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AuthComponent extends Component
{
    // Mode: 'login', 'register', 'forgot_password', 'profile'
    public string $mode = 'login';

    public string $accountTab = 'orders';

    public bool $redirectAfterAuth = false;

    public bool $accountPage = false;

    public ?string $prefilledLoginEmail = null;

    public ?string $prefilledForgotEmail = null;

    public ?string $forgotInfoMessage = null;

    protected $listeners = [
        'customer-authenticated' => 'handleCustomerAuthenticated',
        'open-auth-mode' => 'setAuthMode',
        'set-account-tab' => 'setAccountTab',
    ];

    public function mount(?string $initialMode = null, bool $redirectAfterAuth = false): void
    {
        $this->redirectAfterAuth = $redirectAfterAuth;
        $this->accountPage = request()->routeIs('account');

        if (Auth::check()) {
            $this->mode = 'profile';
        } elseif (in_array($initialMode, ['login', 'register'], true)) {
            $this->mode = $initialMode;
        }
    }

    public function setAuthMode(string $mode, ?string $email = null, ?string $message = null): void
    {
        $this->mode = $mode;
        $this->prefilledLoginEmail = $mode === 'login' ? $email : null;
        $this->prefilledForgotEmail = $mode === 'forgot_password' ? $email : null;
        $this->forgotInfoMessage = $mode === 'forgot_password' ? $message : null;
    }

    public function setAccountTab(string $tab): void
    {
        if (! in_array($tab, ['orders', 'details', 'preferences', 'addresses'], true)) {
            throw new \InvalidArgumentException('Invalid account tab.');
        }

        $this->accountTab = $tab;
    }

    public function handleCustomerAuthenticated(): void
    {
        $this->mode = Auth::check() ? 'profile' : 'login';
        $this->accountTab = 'orders';
    }

    public function render()
    {
        return view('livewire.auth-component');
    }
}
