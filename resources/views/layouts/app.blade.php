<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @yield('title', 'Kizoku - A Fragrance Brand Inspired by the Japanese Aesthetic')
    </title>

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
            openSidebar: false,
            section: 'cart',
            toggleSidebar(section) {
                event.preventDefault();
                this.section = section;
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

    @livewireScripts

    <script>

        document.addEventListener('livewire:init', () => {
            Livewire.on('trigger-toast', ({type, message}) => {
                switch (type) {
                    case 'success':
                        toaster(message, type);
                        break;

                    default:
                        toaster('This is a default toast', 'info');
                        break;
                }
            });
        });

    </script>

    @stack('scripts')

</body>
</html>
