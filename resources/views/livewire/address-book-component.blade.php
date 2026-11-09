<div class="space-y-6">
    @if ($view === 'list')
        <div class="pb-4 border-gray-200 border-b">
            <h2 class="font-light text-2xl">@lang('Address')</h2>
            <p class="mt-1 text-gray-500 text-sm">@lang('Manage your saved delivery addresses.')</p>
        </div>
        <div class="space-y-3">
            @forelse ($addresses as $address)
                <div class="p-4 border {{ $address->is_primary ? 'border-black' : 'border-gray-200' }}" wire:key="address-{{ $address->id }}">
                    <div class="flex justify-between items-start gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-semibold text-xs uppercase tracking-wider">{{ $address->label ?: __('Address') }}</h4>
                                @if ($address->is_primary)
                                    <span class="bg-black px-2 py-0.5 text-[9px] text-white uppercase tracking-widest">@lang('Primary')</span>
                                @endif
                            </div>
                            <p class="mt-2 text-gray-700 text-xs leading-relaxed">{{ $address->full_address }}</p>
                            @if ($address->note)
                                <p class="mt-1 text-gray-400 text-base">@lang('Note:') {{ $address->note }}</p>
                            @endif
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <button wire:click="editAddress({{ $address->id }})" type="button" class="text-gray-500 hover:text-black text-base underline">@lang('Edit')</button>
                            <button wire:click="deleteAddress({{ $address->id }})" wire:confirm="{{ __('Delete this address?') }}" type="button" class="text-gray-500 hover:text-red-600 text-base underline">@lang('Delete')</button>
                        </div>
                    </div>
                    @if (! $address->is_primary)
                        <button wire:click="setPrimaryAddress({{ $address->id }})" type="button" class="mt-3 text-gray-500 hover:text-black text-base underline">@lang('Set as Primary Address')</button>
                    @endif
                </div>
            @empty
                <div class="p-4 border border-gray-300 border-dashed text-gray-500 text-xs text-center">@lang('No saved addresses yet.')</div>
            @endforelse
        </div>

        <button wire:click="newAddress" type="button" class="bg-black hover:bg-neutral-800 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.18em] transition-colors">@lang('+ ADD ADDRESS')</button>
    @else
        <div class="flex justify-between items-center pb-4 border-gray-200 border-b">
            <button wire:click="backToList" type="button" class="text-gray-500 hover:text-black text-xs">&larr; @lang('Back')</button>
            <h3 class="font-semibold text-xs uppercase tracking-[0.18em]">{{ $editingAddressId ? __('EDIT ADDRESS') : __('NEW ADDRESS') }}</h3>
            <span class="w-12"></span>
        </div>

        <form wire:submit.prevent="saveAddress" class="space-y-4">
            <div class="gap-2 grid grid-cols-1 sm:grid-cols-2">
                <div>
                    <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang("Recipient's Name")</label>
                    <input type="text" wire:model="addressRecipientName" placeholder="{{ __('placeholder.address.recipient_name') }}" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs">
                    @error('addressRecipientName') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Contact Number')</label>
                    <input type="tel" wire:model="addressRecipientPhone" placeholder="{{ __('placeholder.address.phone') }}" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs">
                    @error('addressRecipientPhone') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang("Recipient's Email (Optional)")</label>
                <input type="email" wire:model="addressRecipientEmail" placeholder="{{ __('placeholder.address.recipient_email') }}" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs">
                @error('addressRecipientEmail') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Address Type')</label>
                <input type="text" wire:model="addressLabel" placeholder="{{ __('placeholder.address.label') }}" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs">
                @error('addressLabel') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>
            <div class="relative">
                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('City / Town')</label>
                <input
                    type="search"
                    wire:model.live.debounce.400ms="addressAreaSearch"
                    wire:input.debounce.400ms="searchAddressAreas"
                    placeholder="{{ __('placeholder.address.area_search') }}"
                    autocomplete="off"
                    class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs"
                >
                @if ($addressAreaResults)
                    <div class="top-full right-0 left-0 z-10 absolute bg-white shadow-lg mt-1 border border-gray-200 max-h-48 overflow-y-auto">
                        @foreach ($addressAreaResults as $area)
                            <button type="button" wire:click="selectAddressArea(@js($area))" class="block hover:bg-gray-50 px-3 py-2 w-full text-xs text-left" wire:key="area-{{ $area['id'] ?? $loop->index }}">
                                <span class="block font-medium text-gray-800">{{ $area['name'] }}</span>
                                @if ($area['postal_code']) <span class="text-gray-400">{{ $area['postal_code'] }}</span> @endif
                            </button>
                        @endforeach
                    </div>
                @endif
                <input type="hidden" wire:model="addressAreaId">
                <input type="hidden" wire:model="addressAreaName">
                <input type="hidden" wire:model="addressPostalCode">
                @if ($addressAreaName)
                    <p class="mt-1 text-gray-500 text-base">@lang('Selected:') {{ $addressAreaName }}{{ $addressPostalCode ? ' - ' . $addressPostalCode : '' }}</p>
                @endif
                @error('addressAreaId') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Street Address')</label>
                <textarea wire:model="addressFull" rows="4" required placeholder="{{ __('placeholder.address.full') }}" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs resize-none"></textarea>
                @error('addressFull') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block mb-1 text-gray-500 text-base uppercase tracking-wider">@lang('Notes for the Courier')</label>
                <textarea wire:model="addressNote" rows="2" placeholder="{{ __('placeholder.address.note') }}" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs resize-none"></textarea>
                @error('addressNote') <span class="block mt-1 text-red-500 text-base">{{ $message }}</span> @enderror
            </div>
            <label class="flex items-center gap-2 text-gray-600 text-xs">
                <input type="checkbox" wire:model="addressIsPrimary" class="border-gray-400 rounded-none focus:ring-0 accent-black">
                @lang('Set as Primary Address')
            </label>
            <button type="submit" wire:loading.attr="disabled" class="bg-black hover:bg-neutral-800 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.18em] transition-colors">
                <span wire:loading.remove wire:target="saveAddress">@lang('SAVE ADDRESS')</span>
                <span wire:loading wire:target="saveAddress">@lang('PROCESSING...')</span>
            </button>
        </form>
    @endif
</div>
