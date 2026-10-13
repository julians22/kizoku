@extends('layouts.app')

@section('title', 'Track My Order - Kizoku')

@section('content')
<section class="px-6 pt-32 pb-20">
    <div class="mx-auto max-w-4xl">
        <h1 class="mb-6 font-saa-series-e-dot text-3xl 2xl:text-4xl uppercase tracking-widest">Track My Order</h1>

        <p class="mb-10 text-gray-700 text-sm lg:text-base leading-relaxed">
            Our website may contain links to external sites. We are not responsible for the privacy practices of
            those websites, and we encourage you to review their policies separately.
        </p>

        <div class="space-y-8 text-gray-700 text-sm lg:text-base leading-relaxed">
            <div>
                <h2 class="mb-2 font-medium">What shipping carriers do you use to deliver the orders?</h2>
                <p>JNE</p>
            </div>

            <div>
                <h2 class="mb-2 font-medium">Do you offer international shipping?</h2>
                <p>We are currently not offering international shipping.</p>
            </div>

            <div>
                <h2 class="mb-2 font-medium">How can I track my package once it is shipped?</h2>
                <p>You may track through our shipping carrier's website by inputting receipt number.</p>
            </div>

            <div>
                <h2 class="mb-2 font-medium">How long does it take to process and ship an order?</h2>
                <p>
                    We will process and ship orders as quickly as possible. Orders are usually processed within 1-2
                    business days and shipped within 3-5 business days. Delivery times may vary based on your
                    location during checkout.
                </p>
            </div>

            <div>
                <h2 class="mb-2 font-medium">Can I change the shipping address or method after placing the order?</h2>
                <p>
                    Please contact us through the following channels. WhatsApp: (no WA Brand). Please provide the
                    receipt or order number and the list of the products to be changed.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
