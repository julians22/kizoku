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

class AuthComponent extends Component
{
    // Mode: 'login', 'register', 'forgot_password', 'set_password', 'profile'
    public string $mode = 'login';

    // Step indicators
    public string $loginStep = 'credentials'; // 'credentials' | 'otp'
    public string $registerStep = 'email'; // 'email' | 'details'
    public string $forgotStep = 'email'; // 'email' | 'reset'
    public string $setStep = 'email'; // 'email' | 'set'

    // Form attributes
    public string $loginEmail = '';
    public string $loginPassword = '';
    public string $loginOtp = '';

    public string $registerEmail = '';
    public string $registerOtp = '';
    public string $registerFullName = '';
    public string $registerPassword = '';
    public string $registerPasswordConfirmation = '';
    public string $registerPhone = '';
    public string $registerAddress = '';
    public string $registerGender = 'male';

    public string $forgotEmail = '';
    public string $forgotOtp = '';
    public string $forgotPassword = '';
    public string $forgotPasswordConfirmation = '';

    public string $setEmail = '';
    public string $setOtp = '';
    public string $setPassword = '';
    public string $setPasswordConfirmation = '';
    public string $setFullName = '';

    public bool $showPassword = false;
    public ?string $errorMessage = null;
    public ?string $infoMessage = null;

    protected $listeners = [
        'customer-authenticated' => '$refresh',
        'open-auth-mode' => 'setAuthMode',
    ];

    public function mount(): void
    {
        if (Auth::check()) {
            $this->mode = 'profile';
        }
    }

    public function setAuthMode(string $mode): void
    {
        $this->resetState();
        $this->mode = $mode;
    }

    public function togglePasswordVisibility(): void
    {
        $this->showPassword = ! $this->showPassword;
    }

    public function resetState(): void
    {
        $this->resetErrorBag();
        $this->errorMessage = null;
        $this->infoMessage = null;
        $this->loginStep = 'credentials';
        $this->registerStep = 'email';
        $this->forgotStep = 'email';
        $this->setStep = 'email';
        $this->loginOtp = '';
        $this->registerOtp = '';
        $this->forgotOtp = '';
        $this->setOtp = '';
    }

    /**
     * LOGIN: Step 1 - Request Login OTP
     */
    public function requestLoginOtp(CustomerAuthService $authService): void
    {
        $this->resetErrorBag();
        $this->errorMessage = null;
        $this->infoMessage = null;

        $this->validate([
            'loginEmail' => ['required', 'email'],
            'loginPassword' => ['required', 'string', 'min:6'],
        ]);

        $email = Str::lower(trim($this->loginEmail));

        // Check if customer exists in database
        $customer = Customer::where('email', $email)->first();
        if ($customer && empty($customer->password)) {
            // Customer exists but has no password set
            $this->setEmail = $email;
            $this->setFullName = $customer->full_name ?? '';
            $this->mode = 'set_password';
            $this->setStep = 'email';
            $this->infoMessage = 'Akun Anda belum memiliki kata sandi. Silakan buat kata sandi terlebih dahulu.';
            $this->dispatch('trigger-toast', [
                'type' => 'warning',
                'message' => 'Akun belum memiliki kata sandi. Silakan atur kata sandi terlebih dahulu.',
            ]);
            return;
        }

        try {
            $response = $authService->requestLoginOtp($email);
            $this->loginStep = 'otp';
            $this->infoMessage = $response['message'] ?? 'Kode OTP login telah dikirim ke email Anda.';
            $this->dispatch('trigger-toast', [
                'type' => 'success',
                'message' => 'Kode OTP login telah dikirim ke email Anda.',
            ]);
        } catch (Exception $e) {
            $msg = $e->getMessage();
            if (str_contains(strtolower($msg), 'password belum dibuat')) {
                $this->setEmail = $email;
                $this->mode = 'set_password';
                $this->setStep = 'email';
                $this->infoMessage = 'Akun Anda belum memiliki kata sandi. Silakan buat kata sandi terlebih dahulu.';
                $this->dispatch('trigger-toast', [
                    'type' => 'warning',
                    'message' => 'Akun belum memiliki kata sandi. Silakan atur kata sandi terlebih dahulu.',
                ]);
                return;
            }

            $this->errorMessage = $msg;
            $this->dispatch('trigger-toast', [
                'type' => 'error',
                'message' => $msg,
            ]);
        }
    }

    /**
     * LOGIN: Step 2 - Verify OTP and complete login
     */
    public function submitLogin(CustomerAuthService $authService): void
    {
        $this->resetErrorBag();
        $this->errorMessage = null;

        $this->validate([
            'loginEmail' => ['required', 'email'],
            'loginPassword' => ['required', 'string'],
            'loginOtp' => ['required', 'digits:6'],
        ]);

        $email = Str::lower(trim($this->loginEmail));

        try {
            $result = $authService->login($email, $this->loginPassword, $this->loginOtp);

            // Fetch or synchronize local Customer model
            $customer = Customer::where('email', $email)->first();

            if (! $customer) {
                $apiCustomer = $result['data']['customer'] ?? [];
                $customer = Customer::create([
                    'full_name' => $apiCustomer['full_name'] ?? 'Customer',
                    'email' => $email,
                    'password' => Hash::make($this->loginPassword),
                    'phone' => $apiCustomer['phone'] ?? null,
                    'address' => $apiCustomer['address'] ?? null,
                    'is_male' => $apiCustomer['is_male'] ?? true,
                ]);
            } else {
                // Keep password up to date locally
                $customer->update([
                    'password' => Hash::make($this->loginPassword),
                ]);
            }

            // Authenticate web session
            Auth::login($customer, true);

            // Store API token in cookie for backend API consumption if needed
            if (!empty($result['data']['token'])) {
                Cookie::queue(Cookie::make('customer_token', $result['data']['token'], 60 * 24 * 30, '/', null, false, true));
            }

            $this->mode = 'profile';
            $this->resetState();

            $this->dispatch('customer-authenticated');
            $this->dispatch('trigger-toast', [
                'type' => 'success',
                'message' => 'Selamat datang kembali, ' . ($customer->full_name ?? 'Pelanggan') . '!',
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
     * REGISTER: Step 1 - Request OTP for email
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
            $this->infoMessage = $response['message'] ?? 'Kode OTP registrasi telah dikirim ke email Anda.';
            $this->dispatch('trigger-toast', [
                'type' => 'success',
                'message' => 'Kode OTP registrasi telah dikirim ke email Anda.',
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
     * REGISTER: Step 2 - Submit details & OTP to complete registration
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
            'registerAddress' => ['nullable', 'string', 'max:255'],
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
            'address' => $this->registerAddress ?: null,
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
                    'address' => $this->registerAddress ?: null,
                    'is_male' => $isMale,
                ]);
            }

            // Authenticate web session
            Auth::login($customer, true);

            if (!empty($result['data']['token'])) {
                Cookie::queue(Cookie::make('customer_token', $result['data']['token'], 60 * 24 * 30, '/', null, false, true));
            }

            $this->mode = 'profile';
            $this->resetState();

            $this->dispatch('customer-authenticated');
            $this->dispatch('trigger-toast', [
                'type' => 'success',
                'message' => 'Registrasi berhasil! Selamat datang di Kizoku.',
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
     * FORGOT PASSWORD: Step 1 - Request Reset OTP
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
     * FORGOT PASSWORD: Step 2 - Submit new password with OTP
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

            $this->loginEmail = $email;
            $this->mode = 'login';
            $this->loginStep = 'credentials';
            $this->resetState();

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

    /**
     * SET PASSWORD: Step 1 - Request OTP
     */
    public function requestSetOtp(CustomerAuthService $authService): void
    {
        $this->resetErrorBag();
        $this->errorMessage = null;
        $this->infoMessage = null;

        $this->validate([
            'setEmail' => ['required', 'email'],
        ]);

        $email = Str::lower(trim($this->setEmail));

        try {
            $response = $authService->requestSetPasswordOtp($email, $this->setFullName ?: null);
            $this->setStep = 'set';
            $this->infoMessage = $response['message'] ?? 'Kode OTP pembuatan kata sandi telah dikirim ke email Anda.';
            $this->dispatch('trigger-toast', [
                'type' => 'success',
                'message' => 'Kode OTP telah dikirim ke email Anda.',
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
     * SET PASSWORD: Step 2 - Submit new password with OTP
     */
    public function submitSetPassword(CustomerAuthService $authService): void
    {
        $this->resetErrorBag();
        $this->errorMessage = null;

        $this->validate([
            'setEmail' => ['required', 'email'],
            'setOtp' => ['required', 'digits:6'],
            'setPassword' => ['required', 'string', 'min:8'],
            'setPasswordConfirmation' => ['required', 'same:setPassword'],
        ]);

        $email = Str::lower(trim($this->setEmail));

        try {
            $authService->setPassword(
                $email,
                $this->setOtp,
                $this->setPassword,
                $this->setPasswordConfirmation,
                $this->setFullName ?: null
            );

            // Update or create local customer password
            Customer::where('email', $email)->update([
                'password' => Hash::make($this->setPassword),
            ]);

            $this->loginEmail = $email;
            $this->mode = 'login';
            $this->loginStep = 'credentials';
            $this->resetState();

            $this->dispatch('trigger-toast', [
                'type' => 'success',
                'message' => 'Kata sandi berhasil dibuat! Silakan masuk dengan akun Anda.',
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

        $this->mode = 'login';
        $this->resetState();

        $this->dispatch('customer-authenticated');
        $this->dispatch('trigger-toast', [
            'type' => 'success',
            'message' => 'Anda telah berhasil keluar.',
        ]);
    }

    public function render()
    {
        return view('livewire.auth-component', [
            'customer' => Auth::user(),
        ]);
    }
}
