<aside class="flex flex-col lg:min-h-[32rem]">
    <div class="flex items-center gap-3 pb-6 border-gray-200 border-b">
        <div class="flex justify-center items-center bg-navy-500 rounded-full w-12 h-12 font-saa-series-e-dot text-white tracking-wider">
            {{ $this->initials }}
        </div>
        <div class="min-w-0">
            <h2 class="font-medium text-gray-900 text-sm truncate">{{ $this->currentUser?->full_name ?? 'Kizoku Customer' }}</h2>
            <p class="text-gray-500 text-xs truncate">{{ $this->currentUser?->email }}</p>
        </div>
    </div>

    <nav class="lg:flex lg:flex-col gap-2 grid grid-cols-2 py-6" role="tablist" aria-label="Account sections">
        @foreach ([
            'orders' => __('Order History'),
            'details' => __('Account Details'),
            'preferences' => __('Email Preferences'),
            'addresses' => __('Address'),
        ] as $tab => $label)
            <button
                id="account-tab-{{ $tab }}"
                type="button"
                role="tab"
                aria-selected="{{ $accountTab === $tab ? 'true' : 'false' }}"
                aria-controls="account-tab-content"
                wire:click="$dispatch('set-account-tab', { tab: '{{ $tab }}' })"
                class="px-3 py-3 border-l-2 text-sm font-saa-series-e-dot uppercase text-left transition-colors {{ $accountTab === $tab ? 'border-navy-500 bg-gray-50 font-medium text-navy-500' : 'border-transparent text-gray-500' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </nav>

    <button
        wire:click="logout"
        wire:loading.attr="disabled"
        type="button"
        class="mt-auto px-3 py-3 border-gray-200 border-t font-saa-series-e-dot text-gray-500 hover:text-navy-500 text-sm text-left uppercase transition-colors"
    >
        <span wire:loading.remove wire:target="logout">@lang("Log out")</span>
        <span wire:loading wire:target="logout">@lang("Logging out...")</span>
    </button>
</aside>
