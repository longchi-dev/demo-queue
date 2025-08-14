<?php

return [
    'path' => env('EXPORT_PATH', 'export'),
    'batch_size' => env('APP_BATCH_SIZE', 200),
    'cache_key' => 'exports:%s',
    'ttl' => 60,
];
