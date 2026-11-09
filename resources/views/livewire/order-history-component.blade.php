<div class="space-y-6">
    <div class="pb-4 border-gray-200 border-b">
        <h2 class="font-light text-2xl">@lang('Order History')</h2>
        <p class="mt-1 text-gray-500 text-sm">@lang('Review your recent purchases.')</p>
    </div>
    @if ($ordersLoading)
        <div class="p-4 border border-gray-300 border-dashed text-gray-500 text-sm text-center">@lang('Memuat riwayat transaksi...')</div>
    @elseif ($ordersError)
        <div class="p-4 border border-red-200 text-red-500 text-sm">{{ $ordersError }}</div>
    @elseif (! $ordersLoaded)
        <div class="p-4 border border-gray-300 border-dashed text-gray-500 text-sm text-center">@lang('Memuat riwayat transaksi...')</div>
    @elseif (empty($orders))
        <div class="p-4 border border-gray-300 border-dashed text-gray-500 text-sm text-center">@lang('Belum ada transaksi.')</div>
    @else
        <div class="space-y-3">
            @foreach ($orders as $order)
                <div class="space-y-3 p-4 border border-gray-200" wire:key="order-{{ $order['order_number'] ?? $loop->index }}">
                    <div class="flex justify-between items-start gap-3">
                        <div>
                            <p class="font-semibold text-xs tracking-wider">{{ $order['order_number'] ?? '-' }}</p>
                            <p class="mt-1 text-gray-400 text-sm">
                                {{ $order['order_date_formatted'] ?? '' }}
                            </p>
                        </div>
                        <span class="px-2 py-0.5 border text-sm uppercase tracking-widest {{ $order['status_class'] ?? 'border-gray-300 bg-gray-50 text-gray-600' }}">
                            {{ $order['status_label'] }}
                        </span>
                    </div>
                    <ul class="space-y-1 text-gray-600 text-base">
                        @foreach ($order['items'] ?? [] as $item)
                            <li class="flex justify-between gap-3" wire:key="order-item-{{ $order['order_number'] ?? $loop->parent->index }}-{{ $loop->index }}">
                                <span class="truncate">{{ $item['name'] }} &times; {{ $item['qty'] }}</span>
                                <span class="whitespace-nowrap">IDR {{ $item['line_total_formatted'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="flex justify-between pt-2 border-gray-100 border-t text-xs">
                        <span class="text-gray-400">Total</span>
                        <span class="font-semibold">IDR {{ $order['total_formatted'] }}</span>
                    </div>
                    @if (! empty($order['shipment']))
                        <div class="pt-3 border-gray-100 border-t">
                            <h3 class="font-medium text-xs uppercase tracking-wider">@lang("Shipment timeline")</h3>
                            <div class="mt-4">
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <span class="bg-black rounded-full size-2.5"></span>
                                        <span class="bg-gray-200 w-px min-h-8 grow"></span>
                                    </div>
                                    <div class="pb-4">
                                        <p class="font-medium text-gray-800 text-xs">@lang("Order placed")</p>
                                        @if (! empty($order['order_date_formatted']))
                                            <p class="mt-1 text-gray-400 text-sm">
                                                {{ $order['order_date_formatted'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <span class="bg-black rounded-full size-2.5"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-800 text-xs">@lang('Latest shipment status')</p>
                                        <p class="mt-1 text-gray-600 text-xs">
                                            {{ $order['shipment']['status_label'] }}
                                        </p>
                                        <p class="mt-1 text-gray-500 text-sm leading-relaxed">
                                            {{ $order['shipment']['courier_name'] ?? '' }} {{ $order['shipment']['courier_service_name'] ?? '' }}
                                            @if (! empty($order['shipment']['waybill_id']))
                                                &middot; @lang('Waybill:') <span class="font-medium text-gray-700">{{ $order['shipment']['waybill_id'] }}</span>
                                            @endif
                                        </p>
                                        <p class="mt-2 text-gray-400 text-sm">
                                            @lang("Detailed carrier scan events are not available from the current order service.")
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        @if (($ordersMeta['last_page'] ?? 1) > 1)
            <div class="flex justify-between items-center pt-2 text-base">
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
</div>
