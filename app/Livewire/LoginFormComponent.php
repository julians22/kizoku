<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Services\Backend\CustomerAuthService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class LoginFormComponent extends Component
{
    public string $loginEmail = '';

    public string $loginPassword = '';

    public bool $showPassword = false;

    public bool $redirectAfterAuth = false;

    public ?string $errorMessage = null;

    public ?string $infoMessage = null;

    public function mount(?string $initialEmail = null, bool $redirectAfterAuth = false): void
    {
        $this->loginEmail = $initialEmail ?? '';
        $this->redirectAfterAuth = $redirectAfterAuth;
    }

    public function togglePasswordVisibility(): void
    {
        $this->showPassword = ! $this->showPassword;
    }

    /**
     * Login with the customer's email and password.
     */
    public function login(CustomerAuthService $authService): void
    {
        $this->resetErrorBag();
        $this->errorMessage = null;
        $this->infoMessage = null;

        $this->validate([
            'loginEmail' => ['required', 'email'],
            'loginPassword' => ['required', 'string', 'min:6'],
        ]);

        $email = Str::lower(trim($this->loginEmail));

        $customer = Customer::where('email', $email)->first();
        if ($customer && blank($customer->password)) {
            $this->dispatch(
                'open-auth-mode',
                mode: 'forgot_password',
                email: $email,
                message: 'Akun Anda belum memiliki kata sandi. Silakan reset kata sandi terlebih dahulu.',
            );
            $this->dispatch('trigger-toast', [
                'type' => 'warning',
                'message' => 'Akun belum memiliki kata sandi. Silakan reset kata sandi terlebih dahulu.',
            ]);

            return;
        }

        if (! $customer || ! Hash::check($this->loginPassword, $customer->password)) {
            $this->errorMessage = 'Email atau kata sandi tidak valid.';
            $this->dispatch('trigger-toast', [
                'type' => 'error',
                'message' => $this->errorMessage,
            ]);

            return;
        }

        Auth::login($customer, true);

        // Obtain a backend API token so authenticated calls (e.g. checkout) work.
        $tokenIssued = false;

        try {
            $result = $authService->issueToken($email, $this->loginPassword);

            if (! empty($result['data']['token'])) {
                Cookie::queue(Cookie::make('customer_token', $result['data']['token'], 60 * 24 * 30, '/', null, false, true));
                $tokenIssued = true;
            }
        } catch (Exception $e) {
            Log::warning('Failed to issue backend customer token: '.$e->getMessage());
        }

        if (! $tokenIssued) {
            $this->dispatch('trigger-toast', [
                'type' => 'warning',
                'message' => 'Layanan pesanan tidak dapat dihubungi. Riwayat pesanan dan checkout mungkin belum tersedia.',
            ]);
        }

        $this->dispatch('customer-authenticated');
        $this->dispatch('trigger-toast', [
            'type' => 'success',
            'message' => 'Selamat datang kembali, '.($customer->full_name ?? 'Pelanggan').'!',
        ]);

        if ($this->redirectAfterAuth) {
            $this->redirectRoute('account');
        }
    }

    public function render()
    {
        return view('livewire.login-form-component');
    }
}
