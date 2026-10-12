<div class="w-full">
    {{-- PROFILE VIEW (LOGGED IN) --}}
    @if ($mode === 'profile' && Auth::check())
        @php
            $currentUser = Auth::user();
            $initials = collect(explode(' ', $currentUser->full_name ?? 'C'))
                ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                ->take(2)
                ->implode('');
        @endphp

        <div class="space-y-6 pt-6">
            @if ($profileView === 'main')
            <div class="flex items-center space-x-4 pb-6 border-gray-200 border-b">
                <div class="flex justify-center items-center bg-black rounded-full w-14 h-14 font-bold text-white text-lg tracking-wider">
                    {{ $initials ?: 'KZ' }}
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900 text-base truncate">
                        {{ $currentUser->full_name ?? 'Pelanggan Kizoku' }}
                    </h3>
                    <p class="text-gray-500 text-xs truncate">{{ $currentUser->email }}</p>
                    <span class="inline-block bg-gray-100 mt-1 px-2 py-0.5 rounded text-[10px] text-gray-700 uppercase tracking-widest">
                        Customer
                    </span>
                </div>
            </div>

            <div class="space-y-3 text-gray-600 text-sm">
                @if ($currentUser->phone)
                    <div class="flex justify-between py-1 border-gray-100 border-b">
                        <span class="text-gray-400">@lang('Telepon')</span>
                        <span class="font-medium text-gray-800">{{ $currentUser->phone }}</span>
                    </div>
                @endif
                @php($primaryAddress = $addresses->firstWhere('is_primary', true))
                <div class="flex flex-col py-1 border-gray-100 border-b">
                    <span class="mb-1 text-gray-400">@lang('Alamat Pengiriman')</span>
                    <span class="font-medium text-gray-800 leading-relaxed">
                        {{ $primaryAddress?->full_address ?? 'Belum ada alamat tersimpan.' }}
                    </span>
                </div>
                <div class="flex justify-between py-1 border-gray-100 border-b">
                    <span class="text-gray-400">@lang('Jenis Kelamin')</span>
                    <span class="font-medium text-gray-800">{{ $currentUser->gender }}</span>
                </div>
            </div>

            <div>
                <button
                    wire:click="showAddresses"
                    type="button"
                    class="flex justify-between items-center px-4 py-3 border border-gray-300 hover:border-black w-full text-xs transition-colors"
                >
                    <span class="font-semibold uppercase tracking-[0.15em]">@lang('KELOLA ALAMAT')</span>
                    <span class="text-gray-400">{{ $addresses->count() }} tersimpan &rarr;</span>
                </button>
            </div>

            <div>
                <button
                    wire:click="showOrders"
                    type="button"
                    class="flex justify-between items-center px-4 py-3 border border-gray-300 hover:border-black w-full text-xs transition-colors"
                >
                    <span class="font-semibold uppercase tracking-[0.15em]">@lang('RIWAYAT TRANSAKSI')</span>
                    <span class="text-gray-400">&rarr;</span>
                </button>
            </div>

            <div class="pt-2">
                <button
                    wire:click="logout"
                    wire:loading.attr="disabled"
                    type="button"
                    class="flex justify-center items-center space-x-2 hover:bg-black py-3 border border-black w-full font-semibold text-black hover:text-white text-xs uppercase tracking-[0.2em] transition-all duration-300"
                >
                    <span wire:loading.remove wire:target="logout">@lang('LOGOUT')</span>
                    <span wire:loading wire:target="logout">@lang('MEMPROSES...')</span>
                </button>
            </div>
            @elseif ($profileView === 'addresses')
                <div class="flex justify-between items-center pb-4 border-gray-200 border-b">
                    <button wire:click="backToProfile" type="button" class="text-gray-500 hover:text-black text-xs">&larr; Kembali</button>
                    <h3 class="font-semibold text-xs uppercase tracking-[0.18em]">@lang('ALAMAT SAYA')</h3>
                    <span class="w-12"></span>
                </div>

                <div class="space-y-3">
                    @forelse ($addresses as $address)
                        <div class="p-4 border {{ $address->is_primary ? 'border-black' : 'border-gray-200' }}">
                            <div class="flex justify-between items-start gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-semibold text-xs uppercase tracking-wider">{{ $address->label ?: 'Alamat' }}</h4>
                                        @if ($address->is_primary)
                                            <span class="bg-black px-2 py-0.5 text-[9px] text-white uppercase tracking-widest">Utama</span>
                                        @endif
                                    </div>
                                    <p class="mt-2 text-gray-700 text-xs leading-relaxed">{{ $address->full_address }}</p>
                                    @if ($address->note)
                                        <p class="mt-1 text-[11px] text-gray-400">Catatan: {{ $address->note }}</p>
                                    @endif
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <button wire:click="editAddress({{ $address->id }})" type="button" class="text-[11px] text-gray-500 hover:text-black underline">Edit</button>
                                    <button wire:click="deleteAddress({{ $address->id }})" wire:confirm="Hapus alamat ini?" type="button" class="text-[11px] text-gray-500 hover:text-red-600 underline">Hapus</button>
                                </div>
                            </div>
                            @if (! $address->is_primary)
                                <button wire:click="setPrimaryAddress({{ $address->id }})" type="button" class="mt-3 text-[11px] text-gray-500 hover:text-black underline">Jadikan alamat utama</button>
                            @endif
                        </div>
                    @empty
                        <div class="p-4 border border-gray-300 border-dashed text-gray-500 text-xs text-center">Belum ada alamat tersimpan.</div>
                    @endforelse
                </div>

                <button wire:click="newAddress" type="button" class="bg-black hover:bg-neutral-800 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.18em] transition-colors">+ TAMBAH ALAMAT</button>
            @elseif ($profileView === 'orders')
                <div class="flex justify-between items-center pb-4 border-gray-200 border-b">
                    <button wire:click="backToProfile" type="button" class="text-gray-500 hover:text-black text-xs">&larr; Kembali</button>
                    <h3 class="font-semibold text-xs uppercase tracking-[0.18em]">@lang('RIWAYAT TRANSAKSI')</h3>
                    <span class="w-12"></span>
                </div>

                @if ($ordersLoading)
                    <div class="p-4 border border-gray-300 border-dashed text-gray-500 text-xs text-center">@lang('Memuat riwayat transaksi...')</div>
                @elseif ($ordersError)
                    <div class="p-4 border border-red-200 text-red-500 text-xs">{{ $ordersError }}</div>
                @elseif (! $ordersLoaded)
                    <div class="p-4 border border-gray-300 border-dashed text-gray-500 text-xs text-center">@lang('Memuat riwayat transaksi...')</div>
                @elseif (empty($orders))
                    <div class="p-4 border border-gray-300 border-dashed text-gray-500 text-xs text-center">@lang('Belum ada transaksi.')</div>
                @else
                    <div class="space-y-3">
                        @foreach ($orders as $order)
                            <div class="space-y-3 p-4 border border-gray-200">
                                <div class="flex justify-between items-start gap-3">
                                    <div>
                                        <p class="font-semibold text-xs tracking-wider">{{ $order['order_number'] ?? '-' }}</p>
                                        <p class="mt-1 text-[10px] text-gray-400">
                                            {{ isset($order['order_date']) ? \Illuminate\Support\Carbon::parse($order['order_date'])->translatedFormat('d M Y, H:i') : '' }}
                                        </p>
                                    </div>
                                    <span class="px-2 py-0.5 border text-[10px] uppercase tracking-widest {{ $order['status_class'] ?? 'border-gray-300 bg-gray-50 text-gray-600' }}">
                                        {{ $order['status'] ?? 'pending' }}
                                    </span>
                                </div>

                                <ul class="space-y-1 text-[11px] text-gray-600">
                                    @foreach ($order['items'] ?? [] as $item)
                                        <li class="flex justify-between gap-3">
                                            <span class="truncate">{{ $item['name'] }} &times; {{ $item['qty'] }}</span>
                                            <span class="whitespace-nowrap">IDR {{ number_format((float) $item['price'] * (int) $item['qty'], 0) }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="flex justify-between pt-2 border-gray-100 border-t text-xs">
                                    <span class="text-gray-400">Total</span>
                                    <span class="font-semibold">IDR {{ number_format((float) ($order['total_price'] ?? 0), 0) }}</span>
                                </div>

                                @if (! empty($order['shipment']))
                                    <div class="pt-1 text-[10px] text-gray-500 leading-relaxed">
                                        {{ $order['shipment']['courier_name'] ?? '' }} {{ $order['shipment']['courier_service_name'] ?? '' }}
                                        &middot; {{ $order['shipment']['status'] ?? 'pending' }}
                                        @if (! empty($order['shipment']['waybill_id']))
                                            &middot; Resi: <span class="font-medium text-gray-700">{{ $order['shipment']['waybill_id'] }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if (($ordersMeta['last_page'] ?? 1) > 1)
                        <div class="flex justify-between items-center pt-2 text-[11px]">
                            <button wire:click="previousOrdersPage" type="button" @disabled(($ordersMeta['current_page'] ?? 1) <= 1) class="disabled:opacity-40 text-gray-600 hover:text-black underline">
                                &larr; @lang('Sebelumnya')
                            </button>
                            <span class="text-gray-400">{{ $ordersMeta['current_page'] ?? 1 }} / {{ $ordersMeta['last_page'] ?? 1 }}</span>
                            <button wire:click="nextOrdersPage" type="button" @disabled(($ordersMeta['current_page'] ?? 1) >= ($ordersMeta['last_page'] ?? 1)) class="disabled:opacity-40 text-gray-600 hover:text-black underline">
                                @lang('Berikutnya') &rarr;
                            </button>
                        </div>
                    @endif
                @endif
            @else
                <div class="flex justify-between items-center pb-4 border-gray-200 border-b">
                    <button wire:click="showAddresses" type="button" class="text-gray-500 hover:text-black text-xs">&larr; Kembali</button>
                    <h3 class="font-semibold text-xs uppercase tracking-[0.18em]">{{ $editingAddressId ? 'EDIT ALAMAT' : 'ALAMAT BARU' }}</h3>
                    <span class="w-12"></span>
                </div>

                <form wire:submit.prevent="saveAddress" class="space-y-4">
                    <div class="gap-2 grid grid-cols-1 sm:grid-cols-2">
                        <div>
                            <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Nama Penerima</label>
                            <input type="text" wire:model="addressRecipientName" placeholder="Nama penerima" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs">
                            @error('addressRecipientName') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Nomor Telepon</label>
                            <input type="tel" wire:model="addressRecipientPhone" placeholder="08xxxxxxxxxx" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs">
                            @error('addressRecipientPhone') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Email Penerima (Opsional)</label>
                        <input type="email" wire:model="addressRecipientEmail" placeholder="nama@email.com" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs">
                        @error('addressRecipientEmail') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Label</label>
                        <input type="text" wire:model="addressLabel" placeholder="Rumah atau Kantor" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs">
                        @error('addressLabel') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="relative">
                        <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Cari Kota / Area</label>
                        <input
                            type="search"
                            wire:model.live.debounce.400ms="addressAreaSearch"
                            wire:input.debounce.400ms="searchAddressAreas"
                            placeholder="Contoh: Jakarta Selatan"
                            autocomplete="off"
                            class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs"
                        >
                        @if ($addressAreaResults)
                            <div class="top-full right-0 left-0 z-10 absolute bg-white shadow-lg mt-1 border border-gray-200 max-h-48 overflow-y-auto">
                                @foreach ($addressAreaResults as $area)
                                    <button type="button" wire:click="selectAddressArea(@js($area))" class="block hover:bg-gray-50 px-3 py-2 w-full text-xs text-left">
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
                            <p class="mt-1 text-[11px] text-gray-500">Terpilih: {{ $addressAreaName }}{{ $addressPostalCode ? ' - ' . $addressPostalCode : '' }}</p>
                        @endif
                        @error('addressAreaId') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Alamat Lengkap</label>
                        <textarea wire:model="addressFull" rows="4" required placeholder="Jalan, nomor rumah, kecamatan, kota, provinsi" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs resize-none"></textarea>
                        @error('addressFull') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Catatan Kurir (Opsional)</label>
                        <textarea wire:model="addressNote" rows="2" placeholder="Patokan atau instruksi pengiriman" class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs resize-none"></textarea>
                        @error('addressNote') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <label class="flex items-center gap-2 text-gray-600 text-xs">
                        <input type="checkbox" wire:model="addressIsPrimary" class="border-gray-400 rounded-none focus:ring-0 accent-black">
                        Jadikan alamat utama
                    </label>
                    <button type="submit" wire:loading.attr="disabled" class="bg-black hover:bg-neutral-800 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.18em] transition-colors">
                        <span wire:loading.remove wire:target="saveAddress">SIMPAN ALAMAT</span>
                        <span wire:loading wire:target="saveAddress">MEMPROSES...</span>
                    </button>
                </form>
            @endif
        </div>

    {{-- GUEST MODES (LOGIN, REGISTER, FORGOT PASSWORD) --}}
    @else
        <div class="w-full">
            {{-- Tabs Header for Login / Register --}}
            @if (in_array($mode, ['login', 'register']))
                <div class="flex mb-6 border-gray-200 border-b">
                    <button
                        wire:click="setAuthMode('login')"
                        class="flex-1 pb-3 text-xs font-semibold text-center tracking-[0.2em] uppercase transition-all duration-300 {{ $mode === 'login' ? 'border-b-2 border-black text-black' : 'border-transparent text-gray-400 hover:text-gray-600' }}"
                    >
                        @lang('LOGIN')
                    </button>
                    <button
                        wire:click="setAuthMode('register')"
                        class="flex-1 pb-3 text-xs font-semibold text-center tracking-[0.2em] uppercase transition-all duration-300 {{ $mode === 'register' ? 'border-b-2 border-black text-black' : 'border-transparent text-gray-400 hover:text-gray-600' }}"
                    >
                        @lang('REGISTER')
                    </button>
                </div>
            @endif

            {{-- Informational & Error Banners --}}
            @if ($infoMessage)
                <div class="bg-gray-50 mb-4 p-3 border-black border-l-2 text-gray-700 text-xs leading-relaxed">
                    {{ $infoMessage }}
                </div>
            @endif

            @if ($errorMessage)
                <div class="bg-red-50 mb-4 p-3 border-red-600 border-l-2 text-red-700 text-xs leading-relaxed">
                    {{ $errorMessage }}
                </div>
            @endif

            {{-- 1. LOGIN MODE --}}
            @if ($mode === 'login')
                <div class="space-y-4">
                    <form wire:submit.prevent="login" class="space-y-4">
                            <div>
                                <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Email</label>
                                <div class="relative">
                                    <span class="left-0 absolute inset-y-0 flex items-center pl-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                    <input
                                        type="email"
                                        wire:model="loginEmail"
                                        placeholder="nama@email.com"
                                        required
                                        class="py-2.5 pr-3 pl-9 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                                    />
                                </div>
                                @error('loginEmail') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">@lang('Kata Sandi')</label>
                                <div class="relative">
                                    <span class="left-0 absolute inset-y-0 flex items-center pl-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </span>
                                    <input
                                        type="{{ $showPassword ? 'text' : 'password' }}"
                                        wire:model="loginPassword"
                                        placeholder="Minimal 6 karakter"
                                        required
                                        class="py-2.5 pr-9 pl-9 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                                    />
                                    <button
                                        type="button"
                                        wire:click="togglePasswordVisibility"
                                        class="right-0 absolute inset-y-0 flex items-center pr-3 text-gray-400 hover:text-gray-600"
                                    >
                                        @if ($showPassword)
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        @endif
                                    </button>
                                </div>
                                @error('loginPassword') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center pt-1 text-xs">
                                <button
                                    type="button"
                                    wire:click="setAuthMode('forgot_password')"
                                    class="text-gray-500 hover:text-black underline transition-colors"
                                >
                                    @lang('Lupa Kata Sandi?')
                                </button>
                            </div>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="flex justify-center items-center space-x-2 bg-black hover:bg-neutral-800 mt-4 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.2em] transition-all duration-300"
                            >
                                <span wire:loading.remove wire:target="login">@lang('MASUK')</span>
                                <span wire:loading wire:target="login">@lang('MEMPROSES...')</span>
                            </button>
                    </form>
                </div>
            @endif

            {{-- 2. REGISTER MODE --}}
            @if ($mode === 'register')
                <div class="space-y-4">
                    {{-- Step 1: Input Email --}}
                    @if ($registerStep === 'email')
                        <form wire:submit.prevent="requestRegisterOtp" class="space-y-4">
                            <div>
                                <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Email</label>
                                <div class="relative">
                                    <span class="left-0 absolute inset-y-0 flex items-center pl-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                    <input
                                        type="email"
                                        wire:model="registerEmail"
                                        placeholder="nama@email.com"
                                        required
                                        class="py-2.5 pr-3 pl-9 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                                    />
                                </div>
                                @error('registerEmail') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <p class="text-[11px] text-gray-500 leading-normal">
                                Kami akan mengirimkan 6 digit kode OTP verifikasi ke email Anda sebelum melengkapi profil.
                            </p>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="flex justify-center items-center space-x-2 bg-black hover:bg-neutral-800 mt-4 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.2em] transition-all duration-300"
                            >
                                <span wire:loading.remove wire:target="requestRegisterOtp">@lang('MINTA KODE OTP REGISTRASI')</span>
                                <span wire:loading wire:target="requestRegisterOtp">@lang('MENGIRIM OTP...')</span>
                            </button>
                        </form>

                    {{-- Step 2: Complete Profile & OTP --}}
                    @elseif ($registerStep === 'details')
                        <form wire:submit.prevent="submitRegister" class="space-y-3">
                            <div class="bg-gray-50 p-2.5 border border-gray-200 rounded text-xs">
                                <span class="text-gray-500">Email:</span>
                                <span class="ml-1 font-semibold text-gray-900">{{ $registerEmail }}</span>
                            </div>

                            <div>
                                <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Kode OTP (6 Digit)</label>
                                <input
                                    type="text"
                                    wire:model="registerOtp"
                                    maxlength="6"
                                    pattern="\d{6}"
                                    placeholder="••••••"
                                    required
                                    class="py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full font-bold text-lg text-center tracking-[0.4em] transition-colors"
                                />
                                @error('registerOtp') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Nama Lengkap</label>
                                <input
                                    type="text"
                                    wire:model="registerFullName"
                                    placeholder="Nama sesuai KTP/identitas"
                                    required
                                    class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                                />
                                @error('registerFullName') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="gap-2 grid grid-cols-2">
                                <div>
                                    <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Nomor Telepon</label>
                                    <input
                                        type="tel"
                                        wire:model="registerPhone"
                                        placeholder="08123456789"
                                        class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                                    />
                                    @error('registerPhone') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Jenis Kelamin</label>
                                    <select
                                        wire:model="registerGender"
                                        class="bg-white px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                                    >
                                        <option value="male">Laki-laki</option>
                                        <option value="female">Perempuan</option>
                                    </select>
                                    @error('registerGender') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Kata Sandi (Min. 8 Karakter)</label>
                                <input
                                    type="password"
                                    wire:model="registerPassword"
                                    placeholder="Minimal 8 karakter"
                                    required
                                    class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                                />
                                @error('registerPassword') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Konfirmasi Kata Sandi</label>
                                <input
                                    type="password"
                                    wire:model="registerPasswordConfirmation"
                                    placeholder="Ulangi kata sandi"
                                    required
                                    class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                                />
                                @error('registerPasswordConfirmation') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="flex justify-center items-center space-x-2 bg-black hover:bg-neutral-800 mt-3 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.2em] transition-all duration-300"
                            >
                                <span wire:loading.remove wire:target="submitRegister">@lang('SELESAIKAN PENDAFTARAN')</span>
                                <span wire:loading wire:target="submitRegister">@lang('MEMPROSES...')</span>
                            </button>

                            <div class="pt-2 text-center">
                                <button
                                    type="button"
                                    wire:click="$set('registerStep', 'email')"
                                    class="text-gray-500 hover:text-black text-xs underline"
                                >
                                    &larr; @lang('Ganti Email')
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            @endif

            {{-- 3. FORGOT PASSWORD MODE --}}
            @if ($mode === 'forgot_password')
                <div class="space-y-4">
                    <div class="flex justify-between items-center mb-4 pb-3 border-gray-200 border-b">
                        <h4 class="font-semibold text-gray-900 text-xs uppercase tracking-[0.2em]">@lang('RESET KATA SANDI')</h4>
                        <button
                            type="button"
                            wire:click="setAuthMode('login')"
                            class="text-gray-400 hover:text-black text-xs"
                        >
                            &times; @lang('Batal')
                        </button>
                    </div>

                    @if ($forgotStep === 'email')
                        <form wire:submit.prevent="requestForgotOtp" class="space-y-4">
                            <div>
                                <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Email Terdaftar</label>
                                <input
                                    type="email"
                                    wire:model="forgotEmail"
                                    placeholder="nama@email.com"
                                    required
                                    class="px-3 py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                                />
                                @error('forgotEmail') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <p class="text-[11px] text-gray-500 leading-normal">
                                Kami akan mengirimkan 6 digit kode OTP ke email Anda untuk membuat kata sandi baru.
                            </p>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="flex justify-center items-center space-x-2 bg-black hover:bg-neutral-800 mt-4 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.2em] transition-all duration-300"
                            >
                                <span wire:loading.remove wire:target="requestForgotOtp">@lang('KIRIM KODE OTP RESET')</span>
                                <span wire:loading wire:target="requestForgotOtp">@lang('MENGIRIM...')</span>
                            </button>
                        </form>

                    @elseif ($forgotStep === 'reset')
                        <form wire:submit.prevent="submitResetPassword" class="space-y-3">
                            <div class="bg-gray-50 p-2.5 border border-gray-200 rounded text-xs">
                                <span class="text-gray-500">Email:</span>
                                <span class="ml-1 font-semibold text-gray-900">{{ $forgotEmail }}</span>
                            </div>

                            <div>
                                <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Kode OTP (6 Digit)</label>
                                <input
                                    type="text"
                                    wire:model="forgotOtp"
                                    maxlength="6"
                                    pattern="\d{6}"
                                    placeholder="••••••"
                                    required
                                    class="py-2.5 border border-gray-300 focus:border-black rounded focus:outline-none w-full font-bold text-lg text-center tracking-[0.4em] transition-colors"
                                />
                                @error('forgotOtp') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Kata Sandi Baru (Min. 8 Karakter)</label>
                                <input
                                    type="password"
                                    wire:model="forgotPassword"
                                    placeholder="Minimal 8 karakter"
                                    required
                                    class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                                />
                                @error('forgotPassword') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block mb-1 text-[11px] text-gray-500 uppercase tracking-wider">Ulangi Kata Sandi Baru</label>
                                <input
                                    type="password"
                                    wire:model="forgotPasswordConfirmation"
                                    placeholder="Konfirmasi kata sandi baru"
                                    required
                                    class="px-3 py-2 border border-gray-300 focus:border-black rounded focus:outline-none w-full text-xs transition-colors"
                                />
                                @error('forgotPasswordConfirmation') <span class="block mt-1 text-[11px] text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="flex justify-center items-center space-x-2 bg-black hover:bg-neutral-800 mt-3 py-3 w-full font-semibold text-white text-xs uppercase tracking-[0.2em] transition-all duration-300"
                            >
                                <span wire:loading.remove wire:target="submitResetPassword">@lang('SIMPAN KATA SANDI BARU')</span>
                                <span wire:loading wire:target="submitResetPassword">@lang('MEMPROSES...')</span>
                            </button>
                        </form>
                    @endif
                </div>
            @endif

        </div>
    @endif
</div>
