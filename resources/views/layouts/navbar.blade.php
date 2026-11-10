<!-- Fixed top navigaation bar -->
<header
    class="top-0 right-0 left-0 z-50 fixed text-[#11182b]"
    id="navigation"
    >
    <nav class="flex justify-between items-center mx-auto px-4 xl:px-0 py-3 xl:py-4 container">

        <a href="{{ route('home') }}">
            <img src="{{ asset('primary-logo.png') }}" alt="Kizoku Logo" class="h-6 xl:h-12 logo">
        </a>

        <ul class="flex items-center gap-3 sm:gap-4 xl:gap-8">
            <li class="order-3 xl:order-1">
                <a href="#" class="flex items-center text-base xl:text-lg" @click.prevent="toggleSidebar('menu')" aria-label="@lang('MENU')">
                    <span class="hidden xl:inline">@lang("MENU")</span>
                    <x-ionicon-menu-outline class="xl:hidden w-6 h-6" />
                </a>
            </li>
            <li class="hidden xl:block xl:order-2">
                <a href="{{ auth()->check() ? route('account') : route('login') }}" class="flex items-center space-x-1 text-lg">
                    @auth
                        <span class="bg-black px-4 py-2 rounded-full text-white text-sm capitalize" >{{ Str::limit(auth()->user()->full_name ?? 'ACCOUNT', 12) }}</span>
                    @else
                        <span>@lang("ACCOUNT")</span>
                    @endauth
                </a>
            </li>
            <li class="hidden xl:block xl:order-3">
                <a href="{{ route('collections.category', ['slug'=> 'parfume']) }}" class="text-lg">@lang("PRODUCTS")</a>
            </li>
            <li class="order-2 xl:order-4">
                <a href="#" class="flex items-center text-base xl:text-lg" @click.prevent="toggleSidebar('cart')" aria-label="@lang('CART')">
                    <span class="hidden xl:inline">@lang("CART")</span>
                    <x-ionicon-cart-outline class="xl:hidden w-6 h-6" />
                    <livewire:utils.badge-count count-for="cart" />
                </a>
            </li>
            <li class="order-1 xl:order-5">
                <div
                    x-data="{ lang: '{{ $localeData['currentLocale'] }}' }"
                    x-init="$watch('lang', value => window.location.href = '{{ $localeData['otherLocaleUrl'] }}')"
                    class="relative flex items-center p-px xl:p-1 border-[1px] border-black xl:border-2 rounded-full w-20 xl:w-24 h-8 xl:h-10 cursor-pointer select-none"
                    @click="lang = (lang === 'id' ? 'en' : 'id')">
                    <div
                        x-cloak
                        class="lang-pill"
                        :class="lang === 'en' ? 'right' : 'left'">
                    </div>

                    <div class="z-10 w-1/2 font-bold text-xs xl:text-sm text-center transition-colors duration-300"
                        :class="lang === 'id' ? 'text-white' : 'text-black'"> {{ $localeData['supportedLocales'][array_key_first($localeData['supportedLocales'])]['name'] }}
                    </div>

                    <div class="z-10 w-1/2 font-bold text-xs xl:text-sm text-center transition-colors duration-300"
                        :class="lang === 'en' ? 'text-white' : 'text-black'"> {{ $localeData['supportedLocales'][array_key_last($localeData['supportedLocales'])]['name'] }}
                    </div>
                </div>
            </li>
        </ul>

    </nav>

</header>

<sidebar
    class="top-0 right-0 bottom-0 z-50 fixed bg-[rgba(255,255,255,0.75)] backdrop-blur-sm px-6 pt-6 xl:pt-12 w-full md:w-1/2 xl:w-1/3 h-full overflow-y-auto transition-transform duration-300"
    :class="openSidebar ? 'translate-x-0' : 'translate-x-full'"
    x-cloak
    >
    <div class="relative">
        <div
            x-show="section === 'menu'"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="flex flex-col gap-y-10 xl:gap-y-24 pt-20 xl:pt-40 pb-10">
            <!-- Primary links (hidden from the top bar below xl) -->
            <div class="xl:hidden flex flex-col gap-y-6">
                <a href="{{ auth()->check() ? route('account') : route('login') }}" class="text-xl tracking-widest">
                    @auth
                        {{ Str::limit(auth()->user()->full_name ?? __('ACCOUNT'), 20) }}
                    @else
                        @lang("ACCOUNT")
                    @endauth
                </a>
                <a href="{{ route('collections.category', ['slug'=>'parfume']) }}" class="text-xl tracking-widest">@lang("PRODUCTS")</a>
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
                    <li><a href="{{ route('collections.category', ['slug'=>'parfume']) }}" class="hover:pl-2 text-xl tracking-widest transition-all duration-300">@lang("Extrait de Parfum")</a></li>
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
