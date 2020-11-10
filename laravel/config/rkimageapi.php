<?php

return [
    'table_name' => 'property_photos',
    'middleware' => ['auth:api', 'check-user'],
    'api_url'    => 'api/image-api',
    'image_size' => 1500,
    'thumb_size' => 180
];
