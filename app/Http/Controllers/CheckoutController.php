<?php

namespace App\Http\Controllers;

use App\Livewire\Traits\ParfumeCollection;
use Illuminate\Http\Request;

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
        //
    }
}
