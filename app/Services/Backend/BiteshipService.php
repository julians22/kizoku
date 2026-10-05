<?php

namespace App\Services\Backend;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class BiteshipService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.backend.base_url', 'http://karuizawa.test'), '/');
    }

    /**
     * Search Indonesian delivery areas through Karuizawa's Biteship proxy.
     *
     * @return array<int, array{id: string, name: string, postal_code: string|null}>
     */
    public function searchAreas(string $searchQuery): array
    {
        $searchQuery = trim($searchQuery);

        if (mb_strlen($searchQuery) < 3) {
            return [];
        }

        $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])
            ->timeout(10)
            ->get("{$this->baseUrl}/api/v1/online-sales/shipping/areas", [
                'keyword' => $searchQuery,
            ]);

        $payload = $this->handleResponse($response);
        $areas = $payload['data'] ?? $payload['areas'] ?? [];

        if (! is_array($areas)) {
            return [];
        }

        return collect($areas)
            ->filter(fn ($area) => is_array($area) && isset($area['id'], $area['name']))
            ->map(fn (array $area) => [
                'id' => (string) $area['id'],
                'name' => (string) $area['name'],
                'postal_code' => isset($area['postal_code']) ? (string) $area['postal_code'] : null,
            ])
            ->values()
            ->all();
    }

    /**
     * Retrieve courier pricing from Biteship for a destination postal code.
     *
     * @param  array<int, array<string, int|string>>  $items
     * @param  array<int, string>|null  $couriers
     * @return array<int, array<string, mixed>>
     */
    public function calculateRates(int|string $destinationPostalCode, array $items, ?array $couriers = null): array
    {
        $apiKey = trim((string) config('biteship.api_key'));

        if ($apiKey === '') {
            throw new RuntimeException('Biteship API key is not configured.');
        }

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->timeout(15)
            ->post(rtrim((string) config('biteship.base_url'), '/') . '/v1/rates/couriers', [
                'origin_postal_code' => (int) config('biteship.origin.postal_code'),
                'destination_postal_code' => (int) $destinationPostalCode,
                'couriers' => implode(',', $couriers ?: config('biteship.default_couriers', [])),
                'items' => $items,
            ]);

        $payload = $this->handleResponse($response);
        $pricing = $payload['pricing'] ?? [];

        return is_array($pricing) ? array_values(array_filter($pricing, 'is_array')) : [];
    }

    private function handleResponse(Response $response): array
    {
        $payload = $response->json() ?? [];

        if ($response->successful()) {
            return is_array($payload) ? $payload : [];
        }

        $message = $payload['message'] ?? 'Area tidak dapat dimuat saat ini.';
        throw new RuntimeException((string) $message, $response->status());
    }
}
