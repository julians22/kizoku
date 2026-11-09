<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Services\Backend\CustomerAuthService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class RegisterFormComponent extends Component
{
    // Step indicator: 'email' | 'details'
    public string $registerStep = 'email';

    public string $registerEmail = '';

    public string $registerOtp = '';

    public string $registerFullName = '';

    public string $registerPassword = '';

    public string $registerPasswordConfirmation = '';

    public string $registerPhone = '';

    public string $registerGender = 'male';

    public bool $redirectAfterAuth = false;

    public ?string $errorMessage = null;

    public ?string $infoMessage = null;

    public function mount(bool $redirectAfterAuth = false): void
    {
        $this->redirectAfterAuth = $redirectAfterAuth;
    }

    /**
     * Step 1 - Request OTP for email
     */
    public function requestRegisterOtp(CustomerAuthService $authService): void
    {
        $this->resetErrorBag();
        $this->errorMessage = null;
        $this->infoMessage = null;

        $this->validate([
            'registerEmail' => ['required', 'email'],
        ]);

        $email = Str::lower(trim($this->registerEmail));

        try {
            $response = $authService->requestRegisterOtp($email);
            $this->registerStep = 'details';
            // $this->infoMessage = $response['message'] ?? __('OTP Code has been sent to your email');
            $this->infoMessage = $response['status'] == true ? __("api_response.otp_send_to_email") : $response['message'];
            $this->dispatch('trigger-toast', [
                'type' => 'success',
                'message' => $this->infoMessage
            ]);
        } catch (Exception $e) {
            $this->errorMessage = __($e->getMessage());
            $this->dispatch('trigger-toast', [
                'type' => 'error',
                'message' => __($e->getMessage()),
            ]);
        }
    }

    /**
     * Step 2 - Submit details & OTP to complete registration
     */
    public function submitRegister(CustomerAuthService $authService): void
    {
        $this->resetErrorBag();
        $this->errorMessage = null;

        $this->validate([
            'registerEmail' => ['required', 'email'],
            'registerOtp' => ['required', 'digits:6'],
            'registerFullName' => ['required', 'string', 'max:255'],
            'registerPassword' => ['required', 'string', 'min:8'],
            'registerPasswordConfirmation' => ['required', 'same:registerPassword'],
            'registerPhone' => ['nullable', 'string', 'max:50'],
            'registerGender' => ['required', 'in:male,female'],
        ]);

        $email = Str::lower(trim($this->registerEmail));
        $isMale = $this->registerGender === 'male';

        $payload = [
            'email' => $email,
            'otp' => $this->registerOtp,
            'full_name' => $this->registerFullName,
            'password' => $this->registerPassword,
            'password_confirmation' => $this->registerPasswordConfirmation,
            'phone' => $this->registerPhone ?: null,
            'is_male' => $isMale,
        ];

        try {
            $result = $authService->register($payload);

            // Fetch or create customer locally
            $customer = Customer::where('email', $email)->first();
            if (! $customer) {
                $customer = Customer::create([
                    'full_name' => $this->registerFullName,
                    'email' => $email,
                    'password' => Hash::make($this->registerPassword),
                    'phone' => $this->registerPhone ?: null,
                    'is_male' => $isMale,
                ]);
            }

            // Authenticate web session
            Auth::login($customer, true);

            if (! empty($result['data']['token'])) {
                Cookie::queue(Cookie::make('customer_token', $result['data']['token'], 60 * 24 * 30, '/', null, false, true));
            }

            $this->dispatch('customer-authenticated');
            $this->dispatch('trigger-toast', [
                'type' => 'success',
                'message' => __('Registration successful! Welcome to Kizoku.'),
            ]);

            if ($this->redirectAfterAuth) {
                $this->redirectRoute('account');
            }
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
        return view('livewire.register-form-component');
    }
}
