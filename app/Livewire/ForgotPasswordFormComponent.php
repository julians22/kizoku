<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Services\Backend\CustomerAuthService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class ForgotPasswordFormComponent extends Component
{
    // Step indicator: 'email' | 'reset'
    public string $forgotStep = 'email';

    public string $forgotEmail = '';

    public string $forgotOtp = '';

    public string $forgotPassword = '';

    public string $forgotPasswordConfirmation = '';

    public ?string $errorMessage = null;

    public ?string $infoMessage = null;

    public function mount(?string $initialEmail = null, ?string $initialMessage = null): void
    {
        $this->forgotEmail = $initialEmail ?? '';
        $this->infoMessage = $initialMessage;
    }

    /**
     * Step 1 - Request Reset OTP
     */
    public function requestForgotOtp(CustomerAuthService $authService): void
    {
        $this->resetErrorBag();
        $this->errorMessage = null;
        $this->infoMessage = null;

        $this->validate([
            'forgotEmail' => ['required', 'email'],
        ]);

        $email = Str::lower(trim($this->forgotEmail));

        try {
            $response = $authService->requestResetPasswordOtp($email);
            $this->forgotStep = 'reset';
            $this->infoMessage = $response['message'] ?? 'Kode OTP reset password telah dikirim ke email Anda.';
            $this->dispatch('trigger-toast', [
                'type' => 'success',
                'message' => 'Kode OTP reset password telah dikirim ke email Anda.',
            ]);
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
            $this->dispatch('trigger-toast', [
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Step 2 - Submit new password with OTP
     */
    public function submitResetPassword(CustomerAuthService $authService): void
    {
        $this->resetErrorBag();
        $this->errorMessage = null;

        $this->validate([
            'forgotEmail' => ['required', 'email'],
            'forgotOtp' => ['required', 'digits:6'],
            'forgotPassword' => ['required', 'string', 'min:8'],
            'forgotPasswordConfirmation' => ['required', 'same:forgotPassword'],
        ]);

        $email = Str::lower(trim($this->forgotEmail));

        try {
            $authService->resetPassword(
                $email,
                $this->forgotOtp,
                $this->forgotPassword,
                $this->forgotPasswordConfirmation
            );

            // Update local customer password
            Customer::where('email', $email)->update([
                'password' => Hash::make($this->forgotPassword),
            ]);

            $this->dispatch('open-auth-mode', mode: 'login', email: $email);
            $this->dispatch('trigger-toast', [
                'type' => 'success',
                'message' => 'Kata sandi berhasil direset. Silakan masuk menggunakan kata sandi baru.',
            ]);
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
            $this->dispatch('trigger-toast', [
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.forgot-password-form-component');
    }
}
