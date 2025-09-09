<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'build/assets/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // En production, spécifiez vos domaines
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
