<html lang="{{ str_replace('_', '-', app()->currentLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @yield('title', 'Kizoku - A Fragrance Brand Inspired by the Japanese Aesthetic')
    </title>

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
<body
     x-data="{
        openSidebar: {{ session('open_account_sidebar') || request()->has('auth') ? 'true' : 'false' }},
        section: '{{ (session('open_account_sidebar') || request()->has('auth')) ? 'account' : 'cart' }}',
        toggleSidebar(section) {
            if (section) {
                this.section = section;
            }
            this.openSidebar = !this.openSidebar;
        },
        scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }"

    x-bind:class="{
        'overflow-hidden': openSidebar
    }"
    >
    <div id="app">
        @include('layouts.navbar')

        <main
            class="min-h-screen"
        >
            @yield('content')
        </main>

        @include('layouts.footer')
    </div>

    @if (session('error'))
        <span data-name="error" data-text="{{ session('error') }}" class="hidden"></span>
    @endif

    @livewireScriptConfig

    <script>

        document.addEventListener('livewire:init', () => {
            Livewire.on('trigger-toast', (data) => {
                const type = data.type || (Array.isArray(data) && data[0]?.type) || 'info';
                const message = data.message || (Array.isArray(data) && data[0]?.message) || '';
                if (message) {
                    toaster(message, type);
                }
            });

            Livewire.on('customer-authenticated', () => {
                // Optionally close sidebar after short delay on login/register
                setTimeout(() => {
                    const body = document.querySelector('body');
                    if (body && body.__x) {
                        body.__x.$data.openSidebar = false;
                    }
                }, 1000);
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const toastElements = document.querySelectorAll('[data-name="error"]');

            toastElements.forEach(el => {
                const message = el.getAttribute('data-text');
                if (message) {
                    toaster(message, 'error');
                }
            });

        });

    </script>

    @stack('scripts')

</body>
</html>
