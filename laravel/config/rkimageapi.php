<?php

return [
    'table_name' => 'property_photos',
    'middleware' => ['auth:api', 'check-user', 'throttle:image-api'],
    'api_url'    => 'api/image-api',
    'image_size' => 1500,
    'thumb_size' => 180,
    // Max accepted upload size in kilobytes (validated server-side).
    'max_size'   => 10240,
    // Max number of photos per listing (server-enforced).
    'max_items'  => 50,
];