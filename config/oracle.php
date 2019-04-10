<?php

return [
    'oracle' => [
        'driver'        => 'oracle',
        'tns'           => env('DB_ORACLE_TNS', ''),
        'host'          => env('DB_ORACLE_HOST', ''),
        'port'          => env('DB_ORACLE_PORT', '1521'),
        'database'      => env('DB_ORACLE_DB', ''),
        'username'      => env('DB_ORACLE_USERNAME', ''),
        'password'      => env('DB_ORACLE_PASSWORD', ''),
        'charset'       => env('DB_ORACLE_CHARSET', 'AL32UTF8'),
        'prefix'        => env('DB_ORACLE_PREFIX', ''),
        'prefix_schema' => env('DB_ORACLE_SCHEMA_PREFIX', ''),
    ],
];
