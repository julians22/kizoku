<div class="space-y-6">
    <div class="pb-4 border-gray-200 border-b">
        <h2 class="font-light text-2xl">@lang('Email Preferences')</h2>
        <p class="mt-1 text-gray-500 text-sm">@lang('Choose the updates you would like to see.')</p>
    </div>
    <div class="border-gray-200 border-y divide-y divide-gray-200">
        <label class="flex justify-between items-center gap-6 py-5 cursor-pointer">
            <span>
                <span class="block font-medium text-sm">@lang('Newsletter')</span>
                <span class="block mt-1 text-gray-500 text-xs">@lang('Receive news, launches, and special offers.')</span>
            </span>
            <input type="checkbox" wire:model="newsletterEnabled" class="size-5 accent-black">
        </label>
        <label class="flex justify-between items-center gap-6 py-5 cursor-pointer">
            <span>
                <span class="block font-medium text-sm">@lang('Email notifications')</span>
                <span class="block mt-1 text-gray-500 text-xs">@lang('Receive updates about your orders and account.')</span>
            </span>
            <input type="checkbox" wire:model="emailNotificationsEnabled" class="size-5 accent-black">
        </label>
    </div>
    <div class="py-4">
        <label for="account-language" class="block mb-2 font-medium text-sm">@lang('Language')</label>
        <select
            id="account-language"
            onchange="window.location.href = this.value"
            class="px-3 py-3 border border-gray-300 focus:border-black focus:outline-none w-full sm:max-w-sm text-sm"
        >
            <option value="{{ $this->englishUrl }}" @selected($this->currentLocale === 'en')>English</option>
            <option value="{{ $this->indonesianUrl }}" @selected($this->currentLocale === 'id')>Bahasa Indonesia</option>
        </select>
    </div>
    <p class="text-gray-500 text-xs">@lang('Newsletter and email notification selections are temporary and are not saved to your account yet.')</p>
</div>
