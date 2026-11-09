<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Services\Backend\CustomerAuthService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class AccountSidebarComponent extends Component
{
    #[Reactive]
    public string $accountTab = 'orders';

    public bool $accountPage = false;

    #[Computed]
    public function currentUser(): ?Customer
    {
        return Auth::user();
    }

    #[Computed]
    public function initials(): string
    {
        $initials = collect(explode(' ', $this->currentUser?->full_name ?? 'C'))
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->take(2)
            ->implode('');

        return $initials ?: 'KZ';
    }

    /**
     * Logout customer
     */
    public function logout(CustomerAuthService $authService): void
    {
        try {
            $token = Cookie::get('customer_token');
            if ($token) {
                $authService->logout($token);
            }
        } catch (Exception $e) {
            // Ignore token logout error if expired
        }

        Cookie::queue(Cookie::forget('customer_token'));
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->dispatch('customer-authenticated');
        $this->dispatch('trigger-toast', [
            'type' => 'success',
            'message' => 'Anda telah berhasil keluar.',
        ]);

        if ($this->accountPage) {
            $this->redirectRoute('login');
        }
    }

    public function render()
    {
        return view('livewire.account-sidebar-component');
    }
}
