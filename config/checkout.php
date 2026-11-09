<?php

return [
    // Gift wrapping option at checkout. Disabled for initial launch.
    'gift_wrapping_enabled' => (bool) env('CHECKOUT_GIFT_WRAPPING_ENABLED', false),
];
