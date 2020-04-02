<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Subscribers Queue
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of the subscribers queue, this values
    | can be override on the .env file or for testing adding an env variable to
    | the phpunit.xml
    |
    */

    'queue' => [
        'key' => env('SUBSCRIBERS_QUEUE_KEY', 'queue:subscribers'),
        'batch_max_items' => env('SUBSCRIBERS_BATCH_MAX_ITEMS', 1000),
        'batch_quantity' => env('SUBSCRIBERS_QUEUE_BATCH_QUANTITY', 50),
    ],
];
