<!-- Fixed top navigaation bar -->
<header
    class="top-0 right-0 left-0 z-50 fixed text-[#11182b]"
    id="navigation"
    >
    <nav class="flex justify-between items-center mx-auto py-4 container">

        <a href="{{ route('home') }}">
            <img src="{{ asset('primary-logo.png') }}" alt="Kizoku Logo" class="h-12 logo">
        </a>

        <ul class="flex items-center space-x-8">
            <li>
                <a href="#" class="text-lg" @click="toggleSidebar('menu')">@lang("MENU")</a>
            </li>
            <li>
                <a href="#" class="text-lg" @click="toggleSidebar('account')">@lang("ACCOUNT")</a>
            </li>
            <li>
                <a href="{{ route('collections.category', ['slug'=> 'parfume']) }}" class="text-lg">@lang("PRODUCTS")</a>
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
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="flex flex-col gap-y-24 pt-40">
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
                    <li><a href="{{ route('collections.category', ['slug'=>'parfume']) }}" class="hover:pl-2 text-xl tracking-widest transition-all duration-300">@lang("PERFUME")</a></li>
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
                    <li><a href="#" class="hover:pl-2 text-xl tracking-widest transition-all duration-300">@lang("TERMS & PRIVACY")</a></li>
                    <li><a href="#" class="hover:pl-2 text-xl tracking-widest transition-all duration-300">@lang("FAQ")</a></li>
                    <li><a href="#" class="hover:pl-2 text-xl tracking-widest transition-all duration-300">@lang("CONTACT")</a></li>
                </ul>
            </div>
        </div>

        <div
            class="h-full"
            x-show="section === 'cart'">

            <h4 class="text-xl">@lang("YOUR CART")</h4>

            <livewire:cart-component/>
        </div>

        <div
            class="h-full"
            x-show="section === 'account'">

            <h4 class="text-xl">@lang("ACCOUNT")</h4>

            <div class="flex justify-center items-center min-h-screen">
                <div x-data="{ tab: 'login', showPass: false }" class="w-full max-w-md">

                    <div class="flex mb-8 border-gray-200 border-b">
                        <button
                            @click="tab = 'login'"
                            :class="tab === 'login' ? 'border-black text-black' : 'border-transparent text-gray-400'"
                            class="flex-1 pb-2 border-b-2 font-semibold text-center tracking-widest transition-all duration-300 cursor-pointer">
                            @lang('LOGIN')
                        </button>
                        <button
                            @click="tab = 'daftar'"
                            :class="tab === 'daftar' ? 'border-black text-black' : 'border-transparent text-gray-400'"
                            class="flex-1 pb-2 border-b-2 font-semibold text-center tracking-widest transition-all duration-300 cursor-pointer">
                            @lang('REGISTER')
                        </button>
                    </div>

                    <form x-show="tab === 'login'"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-2">
                        <div class="space-y-4">
                            <div class="relative">
                                <span class="left-0 absolute inset-y-0 flex items-center pl-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <input type="text" placeholder="Email" class="py-3 pr-4 pl-10 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-400 w-full placeholder:text-gray-400 text-sm transition-all">
                            </div>

                            <div class="relative">
                                <span class="left-0 absolute inset-y-0 flex items-center pl-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <input :type="showPass ? 'text' : 'password'" placeholder="Kata Sandi" class="py-3 pr-10 pl-10 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-400 w-full placeholder:text-gray-400 text-sm transition-all">
                                <button type="button" @click="showPass = !showPass" class="right-0 absolute inset-y-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                    <svg x-show="!showPass" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="showPass" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex mt-4">
                            <a href="#" class="text-gray-500 text-xs hover:underline">@lang('FORGOT PASSWORD?')</a>
                        </div>

                        <button type="submit" class="bg-[#1a1a1a] hover:bg-black shadow-lg mt-8 py-3 rounded-md w-full font-medium text-white tracking-widest active:scale-[0.98] transition-colors">
                            MASUK
                        </button>
                    </form>

                    <form x-show="tab === 'daftar'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2">
                        <div class="space-y-4">
                            <input type="text" placeholder="Nama Lengkap" class="px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-400 w-full text-sm">
                            <input type="email" placeholder="Alamat Email" class="px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-400 w-full text-sm">
                            <input type="password" placeholder="Kata Sandi" class="px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-400 w-full text-sm">
                            <input type="password" placeholder="Konfirmasi Kata Sandi" class="px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-400 w-full text-sm">
                        </div>
                        <button type="submit" class="bg-[#1a1a1a] hover:bg-black shadow-lg mt-8 py-3 rounded-md w-full font-medium text-white tracking-widest transition-colors">
                            DAFTAR SEKARANG
                        </button>
                    </form>
                </div>
            </div>
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
