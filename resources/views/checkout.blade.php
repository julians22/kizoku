<html lang="{{ str_replace('_', '-', app()->currentLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Checkout Your Transaction')</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}" />
    <meta name="apple-mobile-web-app-title" content="Kizoku" />

    <link href="{{ asset('fonts/stylesheet.css') }}" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html {
            scroll-behavior: smooth;
        }

        [x-cloak] { display: none !important; }
    </style>

    @livewireStyles

    @if (config('services.midtrans.client_key'))
        <script
            src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('services.midtrans.client_key') }}"
            defer
        ></script>
    @endif
</head>
<body class="flex flex-col bg-white min-h-screen text-black">

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

    <main class="mx-auto px-6 md:px-12 py-12 max-w-6xl">
        <livewire:checkout-flow-component
            :selected-parfumes="$selectedParfumes"
            :subtotal="$subtotal"
            :taxes="$taxes"
        />
    </main>
    <!-- Footer -->
    <footer class="bg-slate-200 mt-auto border-gray-400 border-t w-full">
        <div class="flex md:flex-row flex-col justify-between items-center gap-4 mx-auto px-16 py-8 container">
            <div class="font-mono text-black text-sm uppercase tracking-tighter">
                © {{ date('Y') }} KIZOKU. ALL RIGHTS RESERVED.
            </div>
            <nav class="flex flex-wrap justify-center gap-6">
                <a class="hover:opacity-80 font-mono text-black text-sm underline transition-opacity" href="#">FAQ</a>
                <a class="hover:opacity-80 font-mono text-black text-sm underline transition-opacity" href="#">Shipping Info</a>
            </nav>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
