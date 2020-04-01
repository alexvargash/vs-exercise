<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Subscribers Queue
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of the subscribers queue.
    |
    */

    'queue' => [
        'key' => env('SUBSCRIBERS_QUEUE_KEY', 'queue:subscribers'),
        'batch_quantity' => env('SUBSCRIBERS_QUEUE_BATCH_QUANTITY', 1000),
    ],
];
