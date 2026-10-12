<?php

namespace App\Services\Backend;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OrderService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.backend.base_url', 'http://karuizawa.test'), '/');
    }

    /**
     * Create an unpaid online order in Karuizawa for the authenticated customer.
     *
     * @return array<string, mixed>
     */
    public function createOrder(array $payload, string $customerToken): array
    {
        $response = Http::acceptJson()
            ->withToken($customerToken)
            ->timeout(15)
            ->post("{$this->baseUrl}/api/v1/online-sales/checkout", $payload);

        return $this->handleResponse($response);
    }

    /**
     * Relay a Midtrans payment notification so Karuizawa can record the payment
     * and, once settled, queue the Biteship shipment.
     *
     * @return array<string, mixed>
     */
    public function confirmPayment(array $payload): array
    {
        $token = (string) config('services.backend.webhook_token');

        $response = Http::acceptJson()
            ->withHeaders(['Authorization' => $token])
            ->timeout(15)
            ->post("{$this->baseUrl}/api/v1/online-sales/payments/confirm", $payload);

        return $this->handleResponse($response);
    }

    /**
     * Fetch the authenticated customer's order history from Karuizawa.
     *
     * @return array<string, mixed>
     */
    public function listOrders(string $customerToken, int $page = 1): array
    {
        $response = Http::acceptJson()
            ->withToken($customerToken)
            ->timeout(10)
            ->get("{$this->baseUrl}/api/customer/auth/orders", ['page' => $page]);

        return $this->handleResponse($response);
    }

    /**
     * @return array<string, mixed>
     */
    private function handleResponse(Response $response): array
    {
        $payload = $response->json() ?? [];

        if ($response->successful()) {
            return is_array($payload) ? $payload : [];
        }

        $message = $payload['message'] ?? 'Order service request failed.';

        if (isset($payload['errors']) && is_array($payload['errors'])) {
            $first = collect($payload['errors'])->flatten()->first();
            if (is_string($first) && $first !== '') {
                $message = $first;
            }
        }

        throw new RuntimeException((string) $message, $response->status());
    }
}
