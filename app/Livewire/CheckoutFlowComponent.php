<?php

namespace App\Livewire;

use App\Models\CustomerAddress;
use App\Services\Backend\BiteshipService;
use App\Services\Backend\OrderService;
use Throwable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Livewire\Component;

class CheckoutFlowComponent extends Component
{
    // Cart slug => Karuizawa product SKU.
    private const SKU_MAP = [
        'kahen' => 'KAHEN-EDP',
        'kakan' => 'KAKAN-EDP',
        'kohaku' => 'KOHAKU-EDP',
        'ki' => 'KI-EDP',
    ];

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

    public array $courierPricings = [];

    public string $selectedCourierService = '';

    public int $shippingCost = 0;

    public bool $isLoadingRates = false;

    public ?string $ratesError = null;

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
                $this->refreshCourierRates($address->postal_code);
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
            $this->refreshCourierRates($address->postal_code);
        }
    }

    public function fetchCourierRates(BiteshipService $biteshipService): void
    {
        $postalCode = null;

        if ($this->selectedAddressId && Auth::check()) {
            $postalCode = CustomerAddress::where('customer_id', Auth::id())
                ->whereKey($this->selectedAddressId)
                ->value('postal_code');
        }

        $this->loadCourierRates($biteshipService, $postalCode);
    }

    public function updatedSelectedCourierService(string $serviceKey): void
    {
        $pricing = collect($this->courierPricings)->first(function (array $rate) use ($serviceKey): bool {
            return $this->courierRateKey($rate) === $serviceKey;
        });

        $this->shippingCost = $pricing ? (int) ($pricing['price'] ?? $pricing['shipping_fee'] ?? 0) : 0;
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
        $this->refreshCourierRates($address->postal_code);
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

        if ($this->courierPricings && $this->selectedCourierService === '') {
            $this->addError('courier', 'Please select a shipping service.');
            return;
        }

        if (! config('services.midtrans.server_key') || ! config('services.midtrans.client_key')) {
            $this->addError('payment', 'Midtrans credentials are not configured.');
            return;
        }

        try {
            $this->configureMidtrans();

            $orderId = 'KZK-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));
            $this->currentOrderId = $orderId;

            // Persist the unpaid order in the backend first so a Snap token is never
            // created for an order that does not exist.
            try {
                $this->createBackendOrder($orderId);
            } catch (\Throwable $exception) {
                Log::error('Failed to create backend order', ['message' => $exception->getMessage()]);
                $this->addError('payment', 'Unable to place the order: ' . $exception->getMessage());
                $this->dispatch('trigger-toast', [
                    'type' => 'error',
                    'message' => 'Unable to place the order: ' . $exception->getMessage(),
                ]);
                return;
            }

            $itemDetails = collect($this->selectedParfumes)->map(function (array $item) {
                return [
                    'id' => $item['slug'],
                    'price' => (int) $item['price'],
                    'quantity' => (int) $item['quantity'],
                    'name' => Str::limit((string) $item['name'], 50, ''),
                ];
            });

            if ($this->shippingCost > 0) {
                $itemDetails->push([
                    'id' => 'shipping',
                    'price' => $this->shippingCost,
                    'quantity' => 1,
                    'name' => 'Shipping',
                ]);
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) round($this->subtotal + $this->shippingCost),
                ],
                'item_details' => $itemDetails->values()->all(),
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

    private function createBackendOrder(string $orderId): void
    {
        $customerToken = (string) Cookie::get('customer_token');

        if ($customerToken === '') {
            throw new \RuntimeException('Your session has expired. Please log out and log in again.');
        }

        $address = CustomerAddress::where('customer_id', Auth::id())->find($this->selectedAddressId);

        $rate = $this->selectedCourierRate();

        $payload = [
            'address' => array_filter([
                'customer_address_id' => $address?->id,
                'label' => $address?->label,
                'recipient_name' => $address?->recipient_name ?? trim($this->firstName . ' ' . $this->lastName),
                'recipient_phone' => $address?->recipient_phone,
                'recipient_email' => $address?->recipient_email ?? $this->email,
                'full_address' => $address?->full_address ?? $this->shippingAddress,
                'note' => $address?->note,
                'area_id' => $address?->area_id ?? '',
                'area_name' => $address?->area_name,
                'postal_code' => $address?->postal_code,
                'latitude' => $address?->latitude,
                'longitude' => $address?->longitude,
                'save_address' => false,
            ], fn ($value) => $value !== null),
            'items' => collect($this->selectedParfumes)->map(function (array $item): array {
                return [
                    'sku' => self::SKU_MAP[$item['slug']] ?? strtoupper((string) $item['slug']),
                    'qty' => (int) $item['quantity'],
                ];
            })->values()->all(),
            'courier' => array_filter([
                'company' => $rate['courier_code'] ?? $rate['company'] ?? null,
                'type' => $rate['courier_service_code'] ?? $rate['type'] ?? null,
                'name' => $rate['courier_name'] ?? null,
                'service_name' => $rate['courier_service_name'] ?? null,
                'shipping_cost' => $this->shippingCost,
                'insurance_cost' => 0,
            ], fn ($value) => $value !== null),
            'midtrans_order_id' => $orderId,
        ];

        app(OrderService::class)->createOrder($payload, $customerToken);
    }

    private function selectedCourierRate(): array
    {
        return collect($this->courierPricings)->first(function (array $rate): bool {
            return $this->courierRateKey($rate) === $this->selectedCourierService;
        }) ?? [];
    }

    private function refreshCourierRates(?string $postalCode): void
    {
        $this->loadCourierRates(app(BiteshipService::class), $postalCode);
    }

    private function loadCourierRates(BiteshipService $biteshipService, ?string $postalCode): void
    {
        $this->courierPricings = [];
        $this->selectedCourierService = '';
        $this->shippingCost = 0;
        $this->ratesError = null;

        if (! $postalCode || ! preg_match('/^\d{5}$/', (string) $postalCode)) {
            return;
        }

        $this->isLoadingRates = true;

        try {
            $this->courierPricings = $biteshipService->calculateRates(
                (int) $postalCode,
                $this->rateItems(),
            );
        } catch (Throwable $exception) {
            $this->ratesError = $exception->getMessage();
        } finally {
            $this->isLoadingRates = false;
        }
    }

    private function rateItems(): array
    {
        return collect($this->selectedParfumes)->map(function (array $item): array {
            return [
                'name' => (string) $item['name'],
                'description' => (string) $item['name'],
                'value' => (int) $item['price'],
                'length' => 10,
                'width' => 10,
                'height' => 10,
                'weight' => (int) config('biteship.default_item_weight_gram', 1000),
                'quantity' => (int) $item['quantity'],
            ];
        })->values()->all();
    }

    private function courierRateKey(array $rate): string
    {
        return (string) ($rate['courier_code'] ?? $rate['company'] ?? '') . ':' . (string) ($rate['courier_service_code'] ?? $rate['type'] ?? '');
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
