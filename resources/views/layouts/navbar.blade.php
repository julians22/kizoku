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
                <a href="#" class="text-lg" @click="toggleSidebar('menu')">MENU</a>
            </li>
            <li>
                <a href="#" class="text-lg">ACCOUNT</a>
            </li>
            <li>
                <a href="#" class="flex items-center text-lg" @click="toggleSidebar('cart')">
                    CART <livewire:utils.badge-count count-for="cart" />
                </a>
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
                <a href="{{ route('about') }}" class="text-xl">ABOUT</a>
            </div>

            <!-- Shop lists -->
            <div class="flex flex-col justify-center">
                <span class="text-base">SHOP BY CATEGORY</span>
                <a href="{{ route('collections.category', ['slug'=>'parfume']) }}" class="text-xl">PARFUME</a>
                <a href="{{ route('collections.category', ['slug'=>'home-fragrance']) }}" class="text-xl">HOME FRAGRANCE</a>
            </div>

            <!-- Service lists -->
            <div class="flex flex-col justify-center">
                <span class="text-base">DISCOVER</span>
                <a href="#" class="text-xl">DELIVERY & SERVICE</a>
                <a href="#" class="text-xl">CONTACT</a>
                <a href="#" class="text-xl">STORE LOCATION</a>
            </div>
        </div>

        <div
            class="h-full"
            x-show="section === 'cart'">

            <h4 class="text-xl">YOUR CART</h4>

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
