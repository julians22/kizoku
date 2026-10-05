<?php

return [
    'base_url' => env('BITESHIP_BASE_URL', 'https://api.biteship.com'),
    'api_key' => env('BITESHIP_API_KEY'),

    // Token expected in the `Authorization` header of incoming Biteship webhooks.
    'webhook_token' => env('BITESHIP_WEBHOOK_TOKEN'),

    // Warehouse/store location used as the shipping origin for online orders.
    'origin' => [
        'area_id' => env('BITESHIP_ORIGIN_AREA_ID', 'IDNP6IDNC148IDND838IDZ12190'),
        'postal_code' => env('BITESHIP_ORIGIN_POSTAL_CODE', '12190'),
        'latitude' => env('BITESHIP_ORIGIN_LATITUDE', '-6.200000'),
        'longitude' => env('BITESHIP_ORIGIN_LONGITUDE', '106.816666'),
        'contact_name' => env('BITESHIP_ORIGIN_CONTACT_NAME', 'Karuizawa Warehouse'),
        'contact_phone' => env('BITESHIP_ORIGIN_CONTACT_PHONE', '081234567890'),
        'address' => env('BITESHIP_ORIGIN_ADDRESS', 'Jl. Contoh Alamat No.123'),
        'note' => env('BITESHIP_ORIGIN_NOTE', 'Warehouse note here'),
    ],

    'default_couriers' => explode(',', env('BITESHIP_DEFAULT_COURIERS', 'jne,jnt,sicepat,tiki')),

    // Fallback weight (grams) used per item when a product has no weight recorded.
    'default_item_weight_gram' => env('BITESHIP_DEFAULT_ITEM_WEIGHT_GRAM', 1000),
];
