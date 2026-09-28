<?php

namespace App\Livewire;

use App\Models\CustomerAddress;
use App\Services\Backend\BiteshipService;
use Throwable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Livewire\Component;

class CheckoutFlowComponent extends Component
{
    public array $selectedParfumes = [];

    public float|int $subtotal = 0;

    public float|int $taxes = 0;

    public string $email = '';

    public string $firstName = '';

    public string $lastName = '';

    public string $shippingAddress = '';

    public ?int $selectedAddressId = null;

    public bool $showNewAddressForm = false;

    public string $newAddressLabel = 'Rumah';

    public string $newRecipientName = '';

    public string $newRecipientPhone = '';

    public string $newRecipientEmail = '';

    public string $newAddressFull = '';

    public string $newAddressNote = '';

    public string $newAddressAreaId = '';

    public string $newAddressAreaName = '';

    public string $newAddressPostalCode = '';

    public string $newAddressAreaSearch = '';

    public array $newAddressAreaResults = [];

    public bool $newAddressIsPrimary = false;

    public bool $giftWrapping = false;

    public bool $completed = false;

    public ?string $transactionReference = null;

    public ?string $currentOrderId = null;

    public ?string $snapToken = null;

    public string $paymentStatus = 'idle';

    public function mount(array $selectedParfumes, float|int $subtotal, float|int $taxes): void
    {
        $this->selectedParfumes = $selectedParfumes;
        $this->subtotal = $subtotal;
        $this->taxes = $taxes;

        if (Auth::check()) {
            $user = Auth::user();
            $this->email = (string) ($user->email ?? '');

            $nameParts = explode(' ', trim((string) ($user->full_name ?? '')), 2);
            $this->firstName = $nameParts[0] ?? '';
            $this->lastName = $nameParts[1] ?? ($nameParts[0] ?? '');

            $address = CustomerAddress::where('customer_id', $user->getAuthIdentifier())
                ->orderByDesc('is_primary')
                ->oldest('id')
                ->first();

            if ($address) {
                $this->selectedAddressId = $address->id;
                $this->shippingAddress = $address->full_address;
            } else {
                $this->showNewAddressForm = true;
            }
        }
    }

    public function updatedSelectedAddressId($addressId): void
    {
        if (! $addressId || ! Auth::check()) {
            return;
        }

        $address = CustomerAddress::where('customer_id', Auth::id())->find($addressId);
        if ($address) {
            $this->shippingAddress = $address->full_address;
        }
    }

    public function searchNewAddressAreas(BiteshipService $biteshipService): void
    {
        try {
            $this->newAddressAreaResults = $biteshipService->searchAreas($this->newAddressAreaSearch);
        } catch (Throwable $exception) {
            $this->newAddressAreaResults = [];
            $this->dispatch('trigger-toast', [
                'type' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function selectNewAddressArea(array $area): void
    {
        $this->newAddressAreaId = (string) ($area['id'] ?? '');
        $this->newAddressAreaName = (string) ($area['name'] ?? '');
        $this->newAddressPostalCode = (string) ($area['postal_code'] ?? '');
        $this->newAddressAreaSearch = $this->newAddressAreaName;
        $this->newAddressAreaResults = [];
    }

    public function saveNewAddress(): void
    {
        $this->validate([
            'newAddressLabel' => ['nullable', 'string', 'max:100'],
            'newRecipientName' => ['nullable', 'string', 'max:255'],
            'newRecipientPhone' => ['nullable', 'string', 'min:10', 'max:20'],
            'newRecipientEmail' => ['nullable', 'email', 'max:255'],
            'newAddressFull' => ['required', 'string', 'max:2000'],
            'newAddressNote' => ['nullable', 'string', 'max:500'],
            'newAddressAreaId' => ['nullable', 'string', 'max:255'],
            'newAddressAreaName' => ['nullable', 'string', 'max:255'],
            'newAddressPostalCode' => ['nullable', 'string', 'max:10'],
        ]);

        $customerId = Auth::id();
        $hasAddresses = CustomerAddress::where('customer_id', $customerId)->exists();
        $isPrimary = $this->newAddressIsPrimary || ! $hasAddresses;

        if ($isPrimary) {
            CustomerAddress::where('customer_id', $customerId)->update(['is_primary' => false]);
        }

        $address = CustomerAddress::create([
            'customer_id' => $customerId,
            'label' => $this->newAddressLabel ?: null,
            'recipient_name' => $this->newRecipientName ?: null,
            'recipient_phone' => $this->newRecipientPhone ?: null,
            'recipient_email' => $this->newRecipientEmail ?: null,
            'full_address' => $this->newAddressFull,
            'note' => $this->newAddressNote ?: null,
            'area_id' => $this->newAddressAreaId ?: null,
            'area_name' => $this->newAddressAreaName ?: null,
            'postal_code' => $this->newAddressPostalCode ?: null,
            'is_primary' => $isPrimary,
        ]);

        $this->selectedAddressId = $address->id;
        $this->shippingAddress = $address->full_address;
        $this->showNewAddressForm = false;
        $this->newAddressLabel = 'Rumah';
        $this->newRecipientName = '';
        $this->newRecipientPhone = '';
        $this->newRecipientEmail = '';
        $this->newAddressFull = '';
        $this->newAddressNote = '';
        $this->newAddressAreaId = '';
        $this->newAddressAreaName = '';
        $this->newAddressPostalCode = '';
        $this->newAddressAreaSearch = '';
        $this->newAddressAreaResults = [];
        $this->newAddressIsPrimary = false;
        $this->resetValidation([
            'newAddressLabel',
            'newRecipientName',
            'newRecipientPhone',
            'newRecipientEmail',
            'newAddressFull',
            'newAddressNote',
            'newAddressAreaId',
            'newAddressAreaName',
            'newAddressPostalCode',
        ]);

        $this->dispatch('trigger-toast', [
            'type' => 'success',
            'message' => 'Alamat berhasil ditambahkan.',
        ]);
    }

    public function completePurchase(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
            'firstName' => ['required', 'string', 'max:100'],
            'lastName' => ['required', 'string', 'max:100'],
            'shippingAddress' => ['required', 'string', 'max:255'],
        ]);

        if (! config('services.midtrans.server_key') || ! config('services.midtrans.client_key')) {
            $this->addError('payment', 'Midtrans credentials are not configured.');
            return;
        }

        try {
            $this->configureMidtrans();

            $orderId = 'KZK-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));
            $this->currentOrderId = $orderId;

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) round($this->subtotal),
                ],
                'item_details' => collect($this->selectedParfumes)->map(function (array $item) {
                    return [
                        'id' => $item['slug'],
                        'price' => (int) $item['price'],
                        'quantity' => (int) $item['quantity'],
                        'name' => Str::limit((string) $item['name'], 50, ''),
                    ];
                })->values()->all(),
                'customer_details' => [
                    'first_name' => $this->firstName,
                    'last_name' => $this->lastName,
                    'email' => $this->email,
                    'shipping_address' => [
                        'first_name' => $this->firstName,
                        'last_name' => $this->lastName,
                        'address' => $this->shippingAddress,
                    ],
                ],
                'expiry' => [
                    'start_time' => now()->format('Y-m-d H:i:s O'),
                    'unit' => 'hour',
                    'duration' => 1,
                ],
            ];

            $callbacks = array_filter([
                'finish' => config('services.midtrans.finish_url'),
                'unfinish' => config('services.midtrans.unfinish_url'),
                'error' => config('services.midtrans.error_url'),
            ]);

            if (! empty($callbacks)) {
                $params['callbacks'] = $callbacks;
            }

            $this->snapToken = Snap::getSnapToken($params);
            $this->paymentStatus = 'pending';
            $this->dispatch('snap-token-created', token: $this->snapToken, orderId: $orderId, componentId: $this->getId());
        } catch (\Throwable $exception) {
            Log::error('Failed to create Midtrans Snap token', [
                'message' => $exception->getMessage(),
            ]);

            $this->addError('payment', 'Unable to initialize payment. Please try again.');
        }
    }

    public function markPaymentSuccess(array $result = []): void
    {
        $this->paymentStatus = 'success';
        $this->transactionReference = $result['transaction_id'] ?? $this->currentOrderId;
        $this->completed = true;
        session()->forget('cart');
    }

    public function markPaymentPending(array $result = []): void
    {
        $this->paymentStatus = 'pending';
        $this->transactionReference = $result['transaction_id'] ?? $this->currentOrderId;
    }

    public function markPaymentError(array $result = []): void
    {
        $this->paymentStatus = 'failed';
        $this->transactionReference = $result['transaction_id'] ?? $this->currentOrderId;
        $this->addError('payment', 'Payment was not completed. You can try again.');
    }

    private function configureMidtrans(): void
    {
        Config::$serverKey = (string) config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function render()
    {
        return view('livewire.checkout-flow-component', [
            'addresses' => Auth::check()
                ? CustomerAddress::where('customer_id', Auth::id())->orderByDesc('is_primary')->latest('id')->get()
                : collect(),
        ]);
    }
}
