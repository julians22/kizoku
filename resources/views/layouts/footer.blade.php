<footer class="bg-white pt-16 2xl:pt-36 pb-12 2xl:pb-20">

    <div class="mx-auto px-4 2xl:px-0 container">
        <div class="flex flex-col items-start 2xl:items-end mb-8 2xl:mb-0">
            <div class="">
                <p class="font-saa-series-e-dot">@lang('CUSTOMER SERVICE')</p>
                <ul class="mt-4">
                    <li><a href="#" class="text-gray-200 hover:text-gray-600 text-sm">@lang("FAQ")</a></li>
                    <li><a href="#" class="text-gray-200 hover:text-gray-600 text-sm">@lang("CONTACT")</a></li>
                    <li><a href="#" class="text-gray-200 hover:text-gray-600 text-sm">@lang("SHIPPING")</a></li>
                    <li><a href="#" class="text-gray-200 hover:text-gray-600 text-sm">@lang("TRACK MY ORDER")</a></li>
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
