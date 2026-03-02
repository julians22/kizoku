<footer class="bg-white pt-36 pb-20">

    <div class="mx-auto container">
        <div class="flex flex-col items-end">
            <div class="">
                <p class="font-saa-series-e-dot">CUSTOMER SERVICE</p>
                <ul class="mt-4">
                    <li><a href="#" class="text-gray-200 hover:text-gray-600 text-sm">FAQ</a></li>
                    <li><a href="#" class="text-gray-200 hover:text-gray-600 text-sm">CONTACT</a></li>
                    <li><a href="#" class="text-gray-200 hover:text-gray-600 text-sm">SHIPPING</a></li>
                    <li><a href="#" class="text-gray-200 hover:text-gray-600 text-sm">TRACK MY ORDER</a></li>
                </ul>
            </div>
        </div>

        <div class="flex justify-center items-center">
            <button

            @click="scrollToTop()"
            class="flex items-center gap-x-2 px-3 py-2 cursor-pointer">
                <span>
                    <x-carbon-arrow-up class="fill-gray-800 size-6"/>
                </span>
                <span>
                    TOP
                </span>
            </button>
        </div>
    </div>

</footer>
