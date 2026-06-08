<html lang="{{ str_replace('_', '-', app()->currentLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Checkout Your Transaction')</title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}" />
    <meta name="apple-mobile-web-app-title" content="Kizoku" />

    <!-- Fonts -->
    <link href="{{ asset('fonts/stylesheet.css') }}" rel="stylesheet"/>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        [x-cloak] { display: none !important; }
    </style>

    @livewireStyles
</head>
<body>

    <header class="flex justify-between items-center px-6 md:px-12 py-6 border-gray-100 border-b">
        <div class="font-light text-2xl uppercase tracking-[0.2em]">
            <span>
                <img src="{{ asset('primary-logo.png') }}" alt="Kizoku Logo" class="h-12 logo">
            </span>
        </div>
        <div>
            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </div>
    </header>

  <main class="gap-16 grid grid-cols-1 lg:grid-cols-12 mx-auto px-6 md:px-12 py-12 max-w-6xl">

    <section class="space-y-12 lg:col-span-7">

      <div class="space-y-4">
        <h2 class="font-bold uppercase tracking-[0.15em]">1. Contact Information</h2>
        <div class="space-y-1">
          <label class="font-medium text-[10px] text-gray-400 uppercase tracking-wider">Email Address</label>
          <input type="email" placeholder="email@address.com" class="pt-1 pb-2 border-gray-400 focus:border-black border-b focus:outline-none w-full text-sm transition-colors placeholder-gray-300">
        </div>
      </div>

      <div class="space-y-4 pt-4">
        <h2 class="font-bold uppercase tracking-[0.15em]">2. Gift Option</h2>
        <label class="flex items-center space-x-3 cursor-pointer select-none">
          <input type="checkbox" class="checked:bg-black border border-gray-400 rounded-none focus:ring-0 w-4 h-4 accent-black">
          <span class="font-medium text-[11px] text-gray-600 uppercase tracking-wider">Add Complimentary Gift Wrapping</span>
        </label>
      </div>

      <div class="space-y-6 pt-4">
        <h2 class="font-bold uppercase tracking-[0.15em]">3. Shipping & Delivery</h2>

        <div class="gap-6 grid grid-cols-1 md:grid-cols-2">
          <div class="space-y-1">
            <label class="font-medium text-[10px] text-gray-400 uppercase tracking-wider">First Name</label>
            <input type="text" class="pt-1 pb-2 border-gray-300 focus:border-black border-b focus:outline-none w-full text-sm transition-colors">
          </div>
          <div class="space-y-1">
            <label class="font-medium text-[10px] text-gray-400 uppercase tracking-wider">Last Name</label>
            <input type="text" class="pt-1 pb-2 border-gray-300 focus:border-black border-b focus:outline-none w-full text-sm transition-colors">
          </div>
        </div>

        <div class="space-y-1">
          <label class="font-medium text-[10px] text-gray-400 uppercase tracking-wider">Shipping Address</label>
          <input type="text" class="pt-1 pb-2 border-gray-300 focus:border-black border-b focus:outline-none w-full text-sm transition-colors">
        </div>
      </div>

      <div class="space-y-4 pt-4">
        <h2 class="font-bold text-[#111625] text-sm uppercase tracking-[0.15em]">4. Payment</h2>
        <div class="bg-[#f8f9fa] p-4 border border-gray-200 w-full font-medium text-[11px] text-gray-400 uppercase tracking-widest">
          Secure Card Entry Placeholder
        </div>
      </div>

    </section>

    <section class="lg:col-span-5">
      <div class="top-6 sticky bg-stone-50 p-8 md:p-10">
        <h2 class="mb-8 font-bold text-[#111625] text-xs uppercase tracking-[0.15em]">Order Summary</h2>
        <div class="space-y-6 mb-8">
            @foreach ($selectedParfumes as $parfume)
            <div class="flex justify-between items-center space-x-4">
                <div class="flex items-center space-x-4">
                    <div class="flex justify-center items-center bg-[#2d3238] shadow-inner w-20 h-20 shrink-0">
                        <img src="{{ asset($parfume['image']) }}" alt="{{ $parfume['name'] }}" class="size-full object-cover">
                    </div>
                    <div>
                        <h3 class="font-bold text-[#111625] text-xs uppercase tracking-wider">{{ $parfume['name'] }}</h3>
                        <p class="mt-1 text-[10px] text-stone-400 uppercase tracking-wider">Qty: {{ $parfume['quantity'] }}</p>
                        <p class="mt-1 font-medium text-stone-500 text-xs tracking-wider">IDR {{ number_format($parfume['price'], 0) }}</p>
                    </div>
                </div>
                <span class="font-medium text-gray-700 text-sm tracking-wider">IDR {{ number_format($parfume['price'] * $parfume['quantity'], 0)}}</span>
            </div>
            @endforeach
        </div>

        <div class="space-y-3 pt-6 border-gray-300 border-t text-sm tracking-wider">
          <div class="flex justify-between text-gray-600">
            <span class="uppercase">Subtotal</span>
            <span>
                IDR {{ number_format($subtotal, 0) }}
            </span>
          </div>
          <div class="flex justify-between text-gray-600">
            <span class="uppercase">Shipping</span>
            <span class="font-medium text-[11px] uppercase">Free</span>
          </div>
          <div class="flex justify-between text-[11px] text-stone-500">
            <span class="uppercase">Taxes (Included)</span>
            <span>IDR {{ number_format($taxes, 0) }}</span>
          </div>
        </div>

        <div class="flex justify-between items-baseline mt-6 mb-8 pt-6 border-black border-t">
          <span class="font-bold text-sm uppercase tracking-[0.15em]">Total</span>
          <span class="font-bold text-base tracking-wider">IDR {{ number_format($subtotal, 0) }}</span>
        </div>

        <button class="bg-[#111625] hover:bg-black py-4 w-full font-bold text-white text-xs uppercase tracking-[0.2em] transition-colors cursor-pointer">
            Complete Purchase
        </button>
      </div>
    </section>

</body>
</html>
