<!-- Fixed top navigaation bar -->
<header
    class="top-0 right-0 left-0 z-50 fixed text-[#11182b]"
    >
    <nav class="flex justify-between items-center mx-auto py-4 container">

        <a href="{{ route('home') }}">
            <img src="{{ asset('primary-logo.png') }}" alt="Kizoku Logo" class="h-12">
        </a>

        <ul class="flex items-center space-x-8">
            <li>
                <a href="#" class="text-lg" @click="toggleSidebar('menu')">@lang("MENU")</a>
            </li>
            <li>
                <a href="#" class="text-lg">@lang("ACCOUNT")</a>
            </li>
            <li>
                <a href="#" class="flex items-center text-lg" @click="toggleSidebar('cart')">
                    @lang("CART") <livewire:utils.badge-count count-for="cart" />
                </a>
            </li>
            <li>
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
    class="top-0 right-0 bottom-0 z-50 fixed bg-[rgba(255,255,255,0.75)] backdrop-blur-sm px-6 pt-12 w-1/3 h-full transition-transform duration-300"
    :class="openSidebar ? 'translate-x-0' : 'translate-x-full'"
    x-cloak
    >
    <div class="relative">
        <div
            x-show="section === 'menu'"
            class="flex flex-col gap-y-24 pt-40">
            <!-- Page Lists -->
            <div class="flex flex-col justify-center">
                <a href="{{ route('about') }}" class="text-xl">@lang("ABOUT")</a>
            </div>

            <!-- Shop lists -->
            <div class="flex flex-col justify-center">
                <span class="text-base">@lang("SHOP BY CATEGORY")</span>
                <a href="{{ route('collections.category', ['slug'=>'parfume']) }}" class="text-xl">@lang("PARFUME")</a>
                <a href="{{ route('collections.category', ['slug'=>'home-fragrance']) }}" class="text-xl">@lang("HOME FRAGRANCE")</a>
            </div>

            <!-- Service lists -->
            <div class="flex flex-col justify-center">
                <span class="text-base">@lang("DISCOVER")</span>
                <a href="#" class="text-xl">@lang("DELIVERY & SERVICE")</a>
                <a href="#" class="text-xl">@lang("CONTACT")</a>
                <a href="#" class="text-xl">@lang("STORE LOCATION")</a>
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
            class="top-0 right-4 absolute font-bold text-2xl cursor-pointer"
            @click="toggleSidebar()"
            >
            &times;
        </button>
    </div>

</sidebar>
