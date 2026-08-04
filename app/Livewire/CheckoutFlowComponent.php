<?php

namespace App\Livewire;

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
        return view('livewire.checkout-flow-component');
    }
}
