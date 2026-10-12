<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Services\Backend\BiteshipService;
use App\Services\Backend\CustomerAuthService;
use App\Services\Backend\OrderService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class AuthComponent extends Component
{
    // Mode: 'login', 'register', 'forgot_password', 'profile'
    public string $mode = 'login';
    public string $profileView = 'main';

    // Step indicators
    public string $registerStep = 'email'; // 'email' | 'details'
    public string $forgotStep = 'email'; // 'email' | 'reset'

    // Form attributes
    public string $loginEmail = '';
    public string $loginPassword = '';

    public string $registerEmail = '';
    public string $registerOtp = '';
    public string $registerFullName = '';
    public string $registerPassword = '';
    public string $registerPasswordConfirmation = '';
    public string $registerPhone = '';
    public string $registerGender = 'male';

    public string $forgotEmail = '';
    public string $forgotOtp = '';
    public string $forgotPassword = '';
    public string $forgotPasswordConfirmation = '';

    public string $addressLabel = '';
    public string $addressRecipientName = '';
    public string $addressRecipientPhone = '';
    public string $addressRecipientEmail = '';
    public string $addressFull = '';
    public string $addressNote = '';
    public string $addressAreaId = '';
    public string $addressAreaName = '';
    public string $addressPostalCode = '';
    public string $addressAreaSearch = '';
    public array $addressAreaResults = [];
    public bool $addressIsPrimary = false;
    public ?int $editingAddressId = null;

    public bool $showPassword = false;
    public ?string $errorMessage = null;
    public ?string $infoMessage = null;

    public array $orders = [];
    public array $ordersMeta = ['current_page' => 1, 'last_page' => 1, 'total' => 0];
    public bool $ordersLoaded = false;
    public bool $ordersLoading = false;
    public ?string $ordersError = null;

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

    public function showAddresses(): void
    {
        $this->profileView = 'addresses';
        $this->resetAddressForm();
    }

    public function showOrders(): void
    {
        $this->profileView = 'orders';
        $this->loadOrders(1);
    }

    public function loadOrders(int $page = 1): void
    {
        $token = (string) Cookie::get('customer_token');

        if ($token === '') {
            $this->ordersError = 'Sesi Anda telah berakhir. Silakan keluar dan masuk kembali.';
            $this->ordersLoaded = true;
            return;
        }

        $this->ordersLoading = true;
        $this->ordersError = null;

        try {
            $result = app(OrderService::class)->listOrders($token, $page);
            $this->orders = collect($result['data'] ?? [])->map(function (array $order): array {
                $order['status_class'] = match ($order['status'] ?? 'pending') {
                    'processing' => 'border-blue-300 bg-blue-50 text-blue-700',
                    'completed' => 'border-emerald-300 bg-emerald-50 text-emerald-700',
                    'cancelled' => 'border-gray-300 bg-gray-50 text-gray-500',
                    default => 'border-amber-300 bg-amber-50 text-amber-700',
                };
                return $order;
            })->all();
            $this->ordersMeta = $result['meta'] ?? ['current_page' => 1, 'last_page' => 1, 'total' => 0];
        } catch (Exception $e) {
            $this->ordersError = $e->getMessage();
        } finally {
            $this->ordersLoading = false;
            $this->ordersLoaded = true;
        }
    }

    public function nextOrdersPage(): void
    {
        if ($this->ordersMeta['current_page'] < $this->ordersMeta['last_page']) {
            $this->loadOrders($this->ordersMeta['current_page'] + 1);
        }
    }

    public function previousOrdersPage(): void
    {
        if ($this->ordersMeta['current_page'] > 1) {
            $this->loadOrders($this->ordersMeta['current_page'] - 1);
        }
    }

    public function backToProfile(): void
    {
        $this->profileView = 'main';
        $this->resetAddressForm();
    }

    public function newAddress(): void
    {
        $this->profileView = 'address_form';
        $this->resetAddressForm();
    }

    public function editAddress(int $addressId): void
    {
        $address = $this->customerAddressQuery()->findOrFail($addressId);

        $this->editingAddressId = $address->id;
        $this->addressLabel = (string) ($address->label ?? '');
        $this->addressRecipientName = (string) ($address->recipient_name ?? '');
        $this->addressRecipientPhone = (string) ($address->recipient_phone ?? '');
        $this->addressRecipientEmail = (string) ($address->recipient_email ?? '');
        $this->addressFull = $address->full_address;
        $this->addressNote = (string) ($address->note ?? '');
        $this->addressAreaId = (string) ($address->area_id ?? '');
        $this->addressAreaName = (string) ($address->area_name ?? '');
        $this->addressPostalCode = (string) ($address->postal_code ?? '');
        $this->addressAreaSearch = $this->addressAreaName;
        $this->addressIsPrimary = (bool) $address->is_primary;
        $this->addressAreaResults = [];
        $this->profileView = 'address_form';
    }

    public function searchAddressAreas(BiteshipService $biteshipService): void
    {
        try {
            $this->addressAreaResults = $biteshipService->searchAreas($this->addressAreaSearch);
        } catch (Exception $exception) {
            $this->addressAreaResults = [];
            $this->dispatch('trigger-toast', [
                'type' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function selectAddressArea(array $area): void
    {
        $this->addressAreaId = (string) ($area['id'] ?? '');
        $this->addressAreaName = (string) ($area['name'] ?? '');
        $this->addressPostalCode = (string) ($area['postal_code'] ?? '');
        $this->addressAreaSearch = $this->addressAreaName;
        $this->addressAreaResults = [];
    }

    public function saveAddress(): void
    {
        $this->validate([
            'addressLabel' => ['nullable', 'string', 'max:100'],
            'addressRecipientName' => ['nullable', 'string', 'max:255'],
            'addressRecipientPhone' => ['nullable', 'string', 'min:10', 'max:20'],
            'addressRecipientEmail' => ['nullable', 'email', 'max:255'],
            'addressFull' => ['required', 'string', 'max:2000'],
            'addressNote' => ['nullable', 'string', 'max:500'],
            'addressAreaId' => ['nullable', 'string', 'max:255'],
            'addressAreaName' => ['nullable', 'string', 'max:255'],
            'addressPostalCode' => ['nullable', 'string', 'max:10'],
        ]);

        $customerId = Auth::id();
        $addressCount = CustomerAddress::where('customer_id', $customerId)->count();
        $existingAddress = $this->editingAddressId
            ? $this->customerAddressQuery()->findOrFail($this->editingAddressId)
            : null;
        $shouldBePrimary = $this->addressIsPrimary || $addressCount === 0 || (bool) $existingAddress?->is_primary;

        if ($shouldBePrimary) {
            CustomerAddress::where('customer_id', $customerId)->update(['is_primary' => false]);
        }

        $address = $existingAddress ?: new CustomerAddress(['customer_id' => $customerId]);

        $address->fill([
            'label' => $this->addressLabel ?: null,
            'recipient_name' => $this->addressRecipientName ?: null,
            'recipient_phone' => $this->addressRecipientPhone ?: null,
            'recipient_email' => $this->addressRecipientEmail ?: null,
            'full_address' => $this->addressFull,
            'note' => $this->addressNote ?: null,
            'area_id' => $this->addressAreaId ?: null,
            'area_name' => $this->addressAreaName ?: null,
            'postal_code' => $this->addressPostalCode ?: null,
            'is_primary' => $shouldBePrimary,
        ]);
        $address->save();

        $this->profileView = 'addresses';
        $this->resetAddressForm();
        $this->dispatch('trigger-toast', [
            'type' => 'success',
            'message' => 'Alamat berhasil disimpan.',
        ]);
    }

    public function setPrimaryAddress(int $addressId): void
    {
        $address = $this->customerAddressQuery()->findOrFail($addressId);

        CustomerAddress::where('customer_id', Auth::id())->update(['is_primary' => false]);
        $address->update(['is_primary' => true]);

        $this->dispatch('trigger-toast', [
            'type' => 'success',
            'message' => 'Alamat utama berhasil diperbarui.',
        ]);
    }

    public function deleteAddress(int $addressId): void
    {
        $address = $this->customerAddressQuery()->findOrFail($addressId);
        $wasPrimary = $address->is_primary;
        $address->delete();

        if ($wasPrimary) {
            $replacement = $this->customerAddressQuery()->oldest('id')->first();
            $replacement?->update(['is_primary' => true]);
        }

        $this->dispatch('trigger-toast', [
            'type' => 'success',
            'message' => 'Alamat berhasil dihapus.',
        ]);
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
        $this->registerStep = 'email';
        $this->forgotStep = 'email';
        $this->registerOtp = '';
        $this->forgotOtp = '';
        $this->profileView = 'main';
        $this->resetAddressForm();
    }

    private function resetAddressForm(): void
    {
        $this->addressLabel = '';
        $this->addressRecipientName = '';
        $this->addressRecipientPhone = '';
        $this->addressRecipientEmail = '';
        $this->addressFull = '';
        $this->addressNote = '';
        $this->addressAreaId = '';
        $this->addressAreaName = '';
        $this->addressPostalCode = '';
        $this->addressAreaSearch = '';
        $this->addressAreaResults = [];
        $this->addressIsPrimary = false;
        $this->editingAddressId = null;
        $this->resetValidation([
            'addressLabel',
            'addressRecipientName',
            'addressRecipientPhone',
            'addressRecipientEmail',
            'addressFull',
            'addressNote',
            'addressAreaId',
            'addressAreaName',
            'addressPostalCode',
        ]);
    }

    private function customerAddressQuery()
    {
        return CustomerAddress::query()->where('customer_id', Auth::id());
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
            $this->forgotEmail = $email;
            $this->mode = 'forgot_password';
            $this->forgotStep = 'email';
            $this->infoMessage = 'Akun Anda belum memiliki kata sandi. Silakan reset kata sandi terlebih dahulu.';
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
        try {
            $result = $authService->issueToken($email, $this->loginPassword);

            if (! empty($result['data']['token'])) {
                Cookie::queue(Cookie::make('customer_token', $result['data']['token'], 60 * 24 * 30, '/', null, false, true));
            }
        } catch (Exception $e) {
            Log::warning('Failed to issue backend customer token: ' . $e->getMessage());
        }

        $this->mode = 'profile';
        $this->resetState();

        $this->dispatch('customer-authenticated');
        $this->dispatch('trigger-toast', [
            'type' => 'success',
            'message' => 'Selamat datang kembali, ' . ($customer->full_name ?? 'Pelanggan') . '!',
        ]);
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
            'addresses' => Auth::check()
                ? $this->customerAddressQuery()->orderByDesc('is_primary')->latest('id')->get()
                : collect(),
        ]);
    }
}
