<?php

namespace App\Livewire;

use App\Services\Backend\OrderService;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;
use Livewire\Component;

class OrderHistoryComponent extends Component
{
    public array $orders = [];

    public array $ordersMeta = ['current_page' => 1, 'last_page' => 1, 'total' => 0];

    public bool $ordersLoaded = false;

    public bool $ordersLoading = false;

    public ?string $ordersError = null;

    public function mount(): void
    {
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
                $status = $order['status'] ?? null;

                $order['status_class'] = match ($status ?? 'pending') {
                    'processing' => 'border-blue-300 bg-blue-50 text-blue-700',
                    'completed' => 'border-emerald-300 bg-emerald-50 text-emerald-700',
                    'cancelled' => 'border-gray-300 bg-gray-50 text-gray-500',
                    default => 'border-amber-300 bg-amber-50 text-amber-700',
                };
                $order['status_label'] = $status ? __($status) : __('pending');
                $order['order_date_formatted'] = isset($order['order_date'])
                    ? Carbon::parse($order['order_date'])->translatedFormat('d M Y, H:i')
                    : null;
                $order['items'] = collect($order['items'] ?? [])->map(function (array $item): array {
                    $item['line_total_formatted'] = number_format((float) ($item['price'] ?? 0) * (int) ($item['qty'] ?? 0), 0);

                    return $item;
                })->all();
                $order['total_formatted'] = number_format((float) ($order['total_price'] ?? 0), 0);

                if (! empty($order['shipment'])) {
                    $shipmentStatus = $order['shipment']['status'] ?? null;
                    $order['shipment']['status_label'] = ucfirst(str_replace('_', ' ', $shipmentStatus ? __($shipmentStatus) : __('pending')));
                }

                return $order;
            })->all();
            $this->ordersMeta = $result['meta'] ?? ['current_page' => 1, 'last_page' => 1, 'total' => 0];
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            report($e);
            $this->ordersError = 'Layanan pesanan sedang tidak dapat dihubungi. Silakan coba lagi nanti.';
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

    public function render()
    {
        return view('livewire.order-history-component');
    }
}
