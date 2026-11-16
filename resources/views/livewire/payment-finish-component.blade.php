<div class="mx-auto max-w-2xl">
    @if ($notFound)
        <div class="space-y-6 p-8 border border-gray-200 text-center">
            <h1 class="title__2xl">@lang('Transaksi tidak ditemukan')</h1>
            <p class="text-gray-500 text-sm">
                @lang('Kami tidak dapat menemukan informasi transaksi Anda. Silakan periksa riwayat pemesanan Anda.')
            </p>
            <a
                href="{{ route('account') }}"
                class="inline-block bg-[#111625] hover:bg-navy-500 px-8 py-4 font-bold text-white text-xs uppercase tracking-[0.2em] transition-colors"
            >
                @lang('Lihat Riwayat Pemesanan')
            </a>
        </div>
    @else
        @php
            $statusStyles = [
                'processing' => 'border-emerald-300 bg-emerald-50 text-emerald-700',
                'cancelled' => 'border-gray-300 bg-gray-50 text-gray-500',
                'pending' => 'border-amber-300 bg-amber-50 text-amber-700',
            ];
            $statusStyle = $statusStyles[$orderStatus] ?? $statusStyles['pending'];
        @endphp

        <div class="space-y-8">
            <div class="p-8 border {{ $statusStyle }}">
                <span class="block opacity-70 mb-2 font-label-caps text-label-caps uppercase tracking-widest">@lang('Status Pesanan')</span>
                <h1 class="font-saa-series-e-dot text-3xl">
                    @if ($orderStatus === 'processing')
                        @lang('Pembayaran berhasil, pesanan Anda sedang diproses.')
                    @elseif ($orderStatus === 'cancelled')
                        @lang('Pembayaran tidak berhasil atau dibatalkan.')
                    @else
                        @lang('Menunggu pembayaran Anda.')
                    @endif
                </h1>
                @if ($statusMessage)
                    <p class="opacity-80 mt-3 text-sm">{{ $statusMessage }}</p>
                @endif
            </div>

            <div class="border border-gray-200">
                <div class="px-6 py-4 border-gray-200 border-b">
                    <h2 class="font-bold text-xs uppercase tracking-[0.15em]">@lang('Detail Transaksi')</h2>
                </div>
                <dl class="space-y-3 px-6 py-4 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-400 uppercase">@lang('Nomor Pesanan')</dt>
                        <dd class="font-medium text-right">{{ $orderId }}</dd>
                    </div>
                    @if ($transactionId)
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-400 uppercase">@lang('ID Transaksi')</dt>
                            <dd class="font-medium text-right break-all">{{ $transactionId }}</dd>
                        </div>
                    @endif
                    @if ($paymentType)
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-400 uppercase">@lang('Metode Pembayaran')</dt>
                            <dd class="font-medium text-right">{{ ucwords(str_replace('_', ' ', $paymentType)) }}</dd>
                        </div>
                    @endif
                    @if ($transactionTime)
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-400 uppercase">@lang('Waktu Transaksi')</dt>
                            <dd class="font-medium text-right">{{ $transactionTime }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-400 uppercase">@lang('Status Pembayaran')</dt>
                        <dd class="font-medium text-right uppercase">{{ __($transactionStatus) }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 pt-3 border-gray-100 border-t">
                        <dt class="text-gray-400 uppercase">@lang('Biaya Layanan')</dt>
                        <dd class="font-medium text-right">Rp.{{ number_format($paymentFee, 0, ',', '.') }}</dd>
                    </div>
                    @if ($statusLoaded)
                        <div class="flex justify-between items-baseline gap-4 pt-3 border-black border-t">
                            <dt class="font-bold text-xs uppercase tracking-[0.15em]">@lang('Total Dibayar')</dt>
                            <dd class="font-bold">Rp.{{ number_format($grossAmount, 0, ',', '.') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="flex sm:flex-row flex-col justify-center gap-4">
                <a
                    href="{{ route('account') }}"
                    class="inline-block bg-[#111625] hover:bg-navy-500 px-8 py-4 font-bold text-white text-xs text-center uppercase tracking-[0.2em] transition-colors"
                >
                    @lang('Lihat Riwayat Pemesanan')
                </a>
                <a
                    href="{{ route('home') }}"
                    class="inline-block px-8 py-4 border border-gray-300 hover:border-black font-bold text-xs text-center uppercase tracking-[0.2em] transition-colors"
                >
                    @lang('Kembali ke Beranda')
                </a>
            </div>
        </div>
    @endif
</div>
