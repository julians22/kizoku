@extends('layouts.app')

@section('title', 'FAQ - Kizoku')

@section('content')
<section class="px-6 pt-32 pb-20">
    <div class="mx-auto max-w-4xl">
        <h1 class="mb-12 font-saa-series-e-dot text-3xl 2xl:text-4xl uppercase tracking-widest">FAQ</h1>

        {{-- My Account --}}
        <div class="mb-12">
            <h2 class="mb-4 text-xl uppercase tracking-widest">My Account</h2>
            <div class="border-gray-200 border-t border-b divide-y divide-gray-200" x-data="{ open: null }">
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 0 ? null : 0">
                        <span class="font-medium">Do I need to register to place an order?</span>
                        <span class="ml-4 text-xl" x-text="open === 0 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 0" x-cloak x-transition class="pt-3 text-gray-600 text-sm lg:text-base leading-relaxed">
                        Registration is not required to place an order. However, creating an account makes it easier to save your information for future purchases and receive promotional emails. You can manage these emails anytime through the newsletter settings.
                    </div>
                </div>
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 1 ? null : 1">
                        <span class="font-medium">Is there a benefit to creating an account?</span>
                        <span class="ml-4 text-xl" x-text="open === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 1" x-cloak x-transition class="pt-3 text-gray-600 text-sm lg:text-base leading-relaxed">
                        Yes. Customers who create an account receive <strong>10% off</strong> their <strong>first purchase</strong>.
                    </div>
                </div>
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 2 ? null : 2">
                        <span class="font-medium">How can I change my password or personal information?</span>
                        <span class="ml-4 text-xl" x-text="open === 2 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 2" x-cloak x-transition class="pt-3 text-gray-600 text-sm lg:text-base leading-relaxed">
                        You can update your password and personal details anytime through the <strong>My Account</strong> section after logging in.
                    </div>
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="mb-12">
            <h2 class="mb-4 text-xl uppercase tracking-widest">Items</h2>
            <div class="border-gray-200 border-t border-b divide-y divide-gray-200" x-data="{ open: null }">
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 0 ? null : 0">
                        <span class="font-medium">How can I check if an item is available online?</span>
                        <span class="ml-4 text-xl" x-text="open === 0 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 0" x-cloak x-transition class="pt-3 text-gray-600 text-sm lg:text-base leading-relaxed">
                        Product pages will display the availability status. If stock is low, it will be clearly indicated.
                    </div>
                </div>
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 1 ? null : 1">
                        <span class="font-medium">Can I order online and pick up at the store?</span>
                        <span class="ml-4 text-xl" x-text="open === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 1" x-cloak x-transition class="pt-3 text-gray-600 text-sm lg:text-base leading-relaxed">
                        Yes, you can place an order online and choose to pick it up at the store.
                    </div>
                </div>
            </div>
        </div>

        {{-- Payments --}}
        <div class="mb-12">
            <h2 class="mb-4 text-xl uppercase tracking-widest">Payments</h2>
            <div class="border-gray-200 border-t border-b divide-y divide-gray-200" x-data="{ open: null }">
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 0 ? null : 0">
                        <span class="font-medium">When will I be charged for my order?</span>
                        <span class="ml-4 text-xl" x-text="open === 0 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 0" x-cloak x-transition class="pt-3 text-gray-600 text-sm lg:text-base leading-relaxed">
                        You will be charged once your order is successfully processed at checkout.
                    </div>
                </div>
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 1 ? null : 1">
                        <span class="font-medium">The amount of my purchase was charged twice. What should I do?</span>
                        <span class="ml-4 text-xl" x-text="open === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 1" x-cloak x-transition class="pt-3 text-gray-600 text-sm lg:text-base leading-relaxed">
                        If you notice a duplicate charge, please contact our customer service team with your order details so we can investigate and assist you.
                    </div>
                </div>
            </div>
        </div>

        {{-- Delivery --}}
        <div class="mb-12">
            <h2 class="mb-4 text-xl uppercase tracking-widest">Delivery</h2>
            <div class="border-gray-200 border-t border-b divide-y divide-gray-200" x-data="{ open: null }">
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 0 ? null : 0">
                        <span class="font-medium">When will I receive my order?</span>
                        <span class="ml-4 text-xl" x-text="open === 0 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 0" x-cloak x-transition class="pt-3 text-gray-600 lg:text-base\ text-sm leading-relaxed">
                        Delivery time depends on your location. Estimated delivery times will be provided during checkout.
                    </div>
                </div>
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 1 ? null : 1">
                        <span class="font-medium">Can I change the shipping method after placing an order?</span>
                        <span class="ml-4 text-xl" x-text="open === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 1" x-cloak x-transition class="pt-3 text-gray-600 lg:text-base\ text-sm leading-relaxed">
                        Shipping methods generally cannot be changed once the order has been processed. Please contact customer service as soon as possible if you need assistance.
                    </div>
                </div>
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 2 ? null : 2">
                        <span class="font-medium">My order shows as delivered, but I haven't received it. What should I do?</span>
                        <span class="ml-4 text-xl" x-text="open === 2 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 2" x-cloak x-transition class="pt-3 text-gray-600 lg:text-base\ text-sm leading-relaxed">
                        Please check with people around your delivery address (family members, neighbors, or building reception). If the package is still missing, contact our customer service team for further assistance.
                    </div>
                </div>
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 3 ? null : 3">
                        <span class="font-medium">What if I need very fast delivery?</span>
                        <span class="ml-4 text-xl" x-text="open === 3 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 3" x-cloak x-transition class="pt-3 text-gray-600 lg:text-base\ text-sm leading-relaxed">
                        For urgent delivery, you may visit the store and complete the purchase directly. Alternatively, you can contact our store staff via WhatsApp to place an online order. The store team can then arrange a same-day delivery using delivery services.
                    </div>
                </div>
            </div>
        </div>

        {{-- Stores --}}
        <div class="mb-12">
            <h2 class="mb-4 text-xl uppercase tracking-widest">Stores</h2>
            <div class="border-gray-200 border-t border-b divide-y divide-gray-200" x-data="{ open: null }">
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 0 ? null : 0">
                        <span class="font-medium">Can I visit your physical store?</span>
                        <span class="ml-4 text-xl" x-text="open === 0 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 0" x-cloak x-transition class="pt-3 text-gray-600 lg:text-base\ text-sm leading-relaxed">
                        Yes. You are welcome to visit our store to see products in person and make purchases directly.
                    </div>
                </div>
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 1 ? null : 1">
                        <span class="font-medium">Where is your store located?</span>
                        <span class="ml-4 text-xl" x-text="open === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 1" x-cloak x-transition class="pt-3 text-gray-600 lg:text-base\ text-sm leading-relaxed">
                        Our stores are located in:
                        <ol class="space-y-1 mt-2 list-decimal list-inside">
                            <li>Karuizawa Shirt - ASHTA District 8</li>
                            <li>Karuizawa Shirt - PIK Avenue</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Return Policy --}}
        <div class="mb-12">
            <h2 class="mb-4 text-xl uppercase tracking-widest">Return Policy</h2>
            <div class="border-gray-200 border-t border-b divide-y divide-gray-200" x-data="{ open: null }">
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 0 ? null : 0">
                        <span class="font-medium">What is your return policy?</span>
                        <span class="ml-4 text-xl" x-text="open === 0 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 0" x-cloak x-transition class="pt-3 text-gray-600 lg:text-base\ text-sm leading-relaxed">
                        <ol class="space-y-2 list-decimal list-inside">
                            <li>Items must be in perfect condition, unopened, and in their original packaging with the seal fully intact.</li>
                            <li>Returns or exchanges are not permitted for incorrect purchases, including wrong items, models, or scents selected by the customer, as well as products purchased from other distributors.</li>
                            <li>Returns are not accepted based on personal preference, including dissatisfaction with the product or its scent, as fragrance and intensity may vary according to individual taste.</li>
                        </ol>
                    </div>
                </div>
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 1 ? null : 1">
                        <span class="font-medium">How do I submit a complaint about a product?</span>
                        <span class="ml-4 text-xl" x-text="open === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 1" x-cloak x-transition class="pt-3 text-gray-600 lg:text-base\ text-sm leading-relaxed">
                        If you experience an issue with your order, please ensure the <strong>entire unboxing process is recorded on video from the start</strong>. This video is required to process any complaint or warranty claim.
                    </div>
                </div>
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 2 ? null : 2">
                        <span class="font-medium">How do I submit a return?</span>
                        <span class="ml-4 text-xl" x-text="open === 2 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 2" x-cloak x-transition class="space-y-3 pt-3 text-gray-600 lg:text-base\ text-sm leading-relaxed">
                        <div>
                            <p class="font-medium text-gray-700">1. In-Store Purchases</p>
                            <p>Please bring the product along with the original receipt and contact a shop assistant at the store where the purchase was made within 7 days of the purchase date.</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">2. Website Orders</p>
                            <p>For online purchases, please contact us through the following channel: WhatsApp: (no wa). Kindly provide your receipt or order number for any return or exchange request.</p>
                        </div>
                        <ul class="list-disc list-inside">
                            <li>Return shipping costs are the customer's responsibility.</li>
                            <li>Returns must be initiated within 7 days after receiving the product.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Refund --}}
        <div class="mb-12">
            <h2 class="mb-4 text-xl uppercase tracking-widest">Refund</h2>
            <div class="border-gray-200 border-t border-b divide-y divide-gray-200" x-data="{ open: null }">
                <div class="py-4">
                    <button type="button" class="flex justify-between items-center w-full text-left" @click="open = open === 0 ? null : 0">
                        <span class="font-medium">Do you offer refunds?</span>
                        <span class="ml-4 text-xl" x-text="open === 0 ? '−' : '+'"></span>
                    </button>
                    <div x-show="open === 0" x-cloak x-transition class="pt-3 text-gray-600 lg:text-base\ text-sm leading-relaxed">
                        We currently <strong>do not provide refunds</strong>. Please ensure your order details are correct before completing your purchase.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
