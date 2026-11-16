<?php

namespace App\Livewire;

use App\Livewire\Traits\ConfiguresMidtrans;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Midtrans\Transaction;
use Throwable;

class PaymentFinishComponent extends Component
{
    use ConfiguresMidtrans;

    public ?string $orderId = null;

    public ?string $transactionId = null;

    public ?string $paymentType = null;

    public ?string $transactionTime = null;

    public ?string $statusMessage = null;

    public string $transactionStatus = 'pending';

    public string $orderStatus = 'pending';

    public float $grossAmount = 0;

    public float $paymentFee = 0;

    public bool $statusLoaded = false;

    public bool $notFound = false;

    private ?string $fraudStatus = null;

    public function mount(): void
    {
        $orderId = request()->query('order_id');

        if (! is_string($orderId) || $orderId === '') {
            $this->notFound = true;

            return;
        }

        $this->orderId = $orderId;
        $this->transactionStatus = (string) request()->query('transaction_status', 'pending');

        $this->loadTransactionStatus();

        $this->orderStatus = $this->resolveOrderStatus();

        if ($this->orderStatus === 'processing') {
            session()->forget('cart');
        }
    }

    private function loadTransactionStatus(): void
    {
        if (! config('services.midtrans.server_key')) {
            return;
        }

        try {
            $this->configureMidtrans();

            $status = Transaction::status($this->orderId);

            $this->transactionId = $status->transaction_id ?? null;
            $this->paymentType = $status->payment_type ?? null;
            $this->transactionTime = $status->transaction_time ?? null;
            $this->statusMessage = $status->status_message ?? null;
            $this->transactionStatus = (string) ($status->transaction_status ?? $this->transactionStatus);
            $this->grossAmount = (float) ($status->gross_amount ?? 0);
            $this->paymentFee = (float) ($status->extra_info->gross_amount_info->customer_imposed_payment_fee ?? 0);
            $this->fraudStatus = isset($status->fraud_status) ? (string) $status->fraud_status : null;
            $this->statusLoaded = true;
        } catch (Throwable $exception) {
            // Get Status answers 404 until the customer picks a payment method on
            // the Snap page; fall back to the redirect query parameters then.
            Log::info('Unable to fetch Midtrans transaction status for the finish page', [
                'order_id' => $this->orderId,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function resolveOrderStatus(): string
    {
        $status = strtolower($this->transactionStatus);

        if ($status === 'settlement' || ($status === 'capture' && $this->fraudStatus === 'accept')) {
            return 'processing';
        }

        if (in_array($status, ['deny', 'cancel', 'expire', 'failure'], true)) {
            return 'cancelled';
        }

        return 'pending';
    }

    public function render()
    {
        return view('livewire.payment-finish-component');
    }
}
