<!-- Fixed top navigaation bar -->
<header
    class="top-0 right-0 left-0 z-50 fixed text-[#11182b]"
    id="navigation"
    >
    <nav class="flex justify-between items-center mx-auto px-4 2xl:px-0 py-3 2xl:py-4 container">

        <a href="{{ route('home') }}">
            <img src="{{ asset('primary-logo.png') }}" alt="Kizoku Logo" class="h-9 2xl:h-12 logo">
        </a>

        <ul class="flex items-center space-x-5 2xl:space-x-8">
            <li>
                <a href="#" class="text-base 2xl:text-lg" @click.prevent="toggleSidebar('menu')">@lang("MENU")</a>
            </li>
            <li class="hidden 2xl:block">
                <a href="{{ auth()->check() ? route('account') : route('login') }}" class="flex items-center space-x-1 text-lg">
                    @auth
                        <span class="bg-black px-4 py-2 rounded-full text-white text-sm capitalize" >{{ Str::limit(auth()->user()->full_name ?? 'ACCOUNT', 12) }}</span>
                    @else
                        <span>@lang("ACCOUNT")</span>
                    @endauth
                </a>
            </li>
            <li class="hidden 2xl:block">
                <a href="{{ route('collections.category', ['slug'=> 'parfume']) }}" class="text-lg">@lang("PRODUCTS")</a>
            </li>
            <li>
                <a href="#" class="flex items-center text-base 2xl:text-lg" @click.prevent="toggleSidebar('cart')">
                    @lang("CART") <livewire:utils.badge-count count-for="cart" />
                </a>
            </li>
            <li class="hidden 2xl:block">
                <div
                    x-data="{ lang: '{{ $localeData['currentLocale'] }}' }"
                    x-init="$watch('lang', value => window.location.href = '{{ $localeData['otherLocaleUrl'] }}')"
                    class="relative flex items-center p-1 border-2 border-black rounded-full w-24 h-10 cursor-pointer select-none"
                    @click="lang = (lang === 'id' ? 'en' : 'id')">
                    <div
                        x-cloak
                        class="top-1 bottom-1 left-1 absolute bg-black shadow-md rounded-full w-10 transition-all duration-300 ease-in-out"
                        :class="lang === 'en' ? 'translate-x-11' : 'translate-x-0'">
                    </div>

                    <div class="z-10 w-1/2 font-bold text-sm text-center transition-colors duration-300"
                        :class="lang === 'id' ? 'text-white' : 'text-black'"> {{ $localeData['supportedLocales'][array_key_first($localeData['supportedLocales'])]['name'] }}
                    </div>

                    <div class="z-10 w-1/2 font-bold text-sm text-center transition-colors duration-300"
                        :class="lang === 'en' ? 'text-white' : 'text-black'"> {{ $localeData['supportedLocales'][array_key_last($localeData['supportedLocales'])]['name'] }}
                    </div>
                </div>
            </li>
        </ul>

    </nav>

</header>

<sidebar
    class="top-0 right-0 bottom-0 z-50 fixed bg-[rgba(255,255,255,0.75)] backdrop-blur-sm px-6 pt-6 2xl:pt-12 w-full md:w-1/2 2xl:w-1/3 h-full overflow-y-auto transition-transform duration-300"
    :class="openSidebar ? 'translate-x-0' : 'translate-x-full'"
    x-cloak
    >
    <div class="relative">
        <div
            x-show="section === 'menu'"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="flex flex-col gap-y-10 2xl:gap-y-24 pt-20 2xl:pt-40 pb-10">
            <!-- Primary links (hidden from the top bar below 2xl) -->
            <div class="2xl:hidden flex flex-col gap-y-6">
                <a href="{{ auth()->check() ? route('account') : route('login') }}" class="text-xl tracking-widest">
                    @auth
                        {{ Str::limit(auth()->user()->full_name ?? __('ACCOUNT'), 20) }}
                    @else
                        @lang("ACCOUNT")
                    @endauth
                </a>
                <a href="{{ route('collections.category', ['slug'=>'parfume']) }}" class="text-xl tracking-widest">@lang("PRODUCTS")</a>
                <a href="{{ $localeData['otherLocaleUrl'] }}" class="text-stone-500 text-base uppercase tracking-widest">
                    {{ $localeData['currentLocale'] === 'id' ? 'English' : 'Bahasa Indonesia' }}
                </a>
            </div>

            <!-- Page Lists -->
            <div class="flex flex-col justify-center"
                x-show="section === 'menu'"
                x-transition:enter="transition ease-out duration-700 delay-[100ms]"
                x-transition:enter-start="opacity-0 translate-x-4"
                x-transition:enter-end="opacity-100"
            >
                <a href="{{ route('about') }}" class="hover:pl-2 text-xl tracking-widest transition-all duration-300">@lang("ABOUT")</a>
            </div>

            <!-- Shop lists -->
            <div class="flex flex-col justify-center"
                x-show="section === 'menu'"
                x-transition:enter="transition ease-out duration-700 delay-[200ms]"
                x-transition:enter-start="opacity-0 translate-x-4"
                x-transition:enter-end="opacity-100"
            >
                <span class="text-stone-500 text-base">@lang("SHOP BY CATEGORY")</span>
                <ul class="flex flex-col gap-y-8 mt-5">
                    <li><a href="{{ route('collections.category', ['slug'=>'parfume']) }}" class="hover:pl-2 text-xl tracking-widest transition-all duration-300">@lang("EAU DE PARFUME")</a></li>
                </ul>
            </div>

            <!-- Service lists -->
            <div class="flex flex-col justify-center"
                x-show="section === 'menu'"
                x-transition:enter="transition ease-out duration-700 delay-[300ms]"
                x-transition:enter-start="opacity-0 translate-x-4"
                x-transition:enter-end="opacity-100"
            >
                <span class="text-stone-500 text-base">@lang("DISCOVER")</span>
                <ul class="flex flex-col gap-y-8 mt-5">
                    <li><a href="{{ route('terms') }}" class="hover:pl-2 text-xl tracking-widest transition-all duration-300">@lang("TERMS & PRIVACY")</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:pl-2 text-xl tracking-widest transition-all duration-300">@lang("FAQ")</a></li>
                    {{-- <li><a href="#" class="hover:pl-2 text-xl tracking-widest transition-all duration-300">@lang("CONTACT")</a></li> --}}
                </ul>
            </div>
        </div>

        <div
            class="h-full"
            x-show="section === 'cart'">

            <h4 class="text-xl">@lang("YOUR CART")</h4>

            <livewire:cart-component/>
        </div>

        <!-- close button -->
        <button
            class="top-0 right-4 absolute px-4-2 hover:border border-black rounded-full size-12 font-bold text-2xl transition-all duration-150 ease-linear cursor-pointer transform"
            @click="toggleSidebar()"
            >
            &times;
        </button>
    </div>

</sidebar>
