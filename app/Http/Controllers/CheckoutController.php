<?php

namespace App\Http\Controllers;

use App\Livewire\Traits\ParfumeCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckoutController extends Controller
{
    use ParfumeCollection;

    public function index(Request $request)
    {
        $cartItems = session()->get('cart', []);

        $parfumes = $this->getParfumeCollections();


        $selectedParfumes = array_map(function ($item) use ($parfumes) {
            $product = collect($parfumes)->firstWhere('slug', $item['slug']);

            return [
                'name' => $item['name'],
                'slug' => $item['slug'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'image' => $product ? $product['image'] : null,
            ];
        }, $cartItems);

        $subtotal = array_reduce($selectedParfumes, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        $taxes = $subtotal * 0.1; // Assuming a tax rate of 10%

        return view('checkout', compact('selectedParfumes', 'subtotal', 'taxes'));
    }

    public function store(Request $request)
    {
        return $this->index($request);
    }

    public function midtransNotification(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');

        if (! $serverKey) {
            return response()->json(['message' => 'Midtrans server key missing.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $orderId = (string) $request->input('order_id');
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $signatureKey = (string) $request->input('signature_key');

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if (! hash_equals($expectedSignature, $signatureKey)) {
            return response()->json(['message' => 'Invalid signature'], Response::HTTP_FORBIDDEN);
        }

        Log::info('Midtrans notification received', [
            'order_id' => $orderId,
            'transaction_status' => $request->input('transaction_status'),
            'payment_type' => $request->input('payment_type'),
            'fraud_status' => $request->input('fraud_status'),
            'raw' => $request->all(),
        ]);

        return response()->json(['message' => 'Notification accepted']);
    }
}
