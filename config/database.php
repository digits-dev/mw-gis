<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3380'),
            'database' => env('DB_DATABASE', 'bea_pos_mw'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
            'modes' => [
                //'ONLY_FULL_GROUP_BY', // Disable this to allow grouping by one column
                'STRICT_TRANS_TABLES',
                'NO_ZERO_IN_DATE',
                'NO_ZERO_DATE',
                'ERROR_FOR_DIVISION_BY_ZERO',
                // 'NO_AUTO_CREATE_USER',
                'NO_ENGINE_SUBSTITUTION'
            ],
        ],
        
        'webpos' => [
            'driver' => 'mysql',
            'host' => env('DB_POS_HOST', '13.76.137.126'),
            'port' => env('DB_POS_PORT', '3306'),
            'database' => env('DB_POS_DATABASE', 'bc_webpos'),
            'username' => env('DB_POS_USERNAME', 'bc_webpos'),
            'password' => env('DB_POS_PASSWORD', 'bcpass82'),
            'unix_socket' => env('DB_POS_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
        ],

        'oracle' => [
            'driver'        => 'oracle',
            'tns'           => env('DB_ORACLE_TNS', ''),
            'host'          => env('DB_ORACLE_HOST', ''),
            'port'          => env('DB_ORACLE_PORT', '1521'),
            'database'      => env('DB_ORACLE_DATABASE', ''),
            'username'      => env('DB_ORACLE_USERNAME', ''),
            'password'      => env('DB_ORACLE_PASSWORD', ''),
            'charset'       => env('DB_ORACLE_CHARSET', 'AL32UTF8'),
            'prefix'        => env('DB_ORACLE_PREFIX', ''),
            'prefix_schema' => env('DB_ORACLE_SCHEMA_PREFIX', ''),
        ],
        
        'imfs' => [
            'driver' => 'mysql',
            'host' => env('DB_IMFS_HOST', '127.0.0.1'),
            'port' => env('DB_IMFS_PORT', '3306'),
            'database' => env('DB_IMFS_DATABASE', 'dtc_digits_imfs_v3'),
            'username' => env('DB_IMFS_USERNAME', 'root'),
            'password' => env('DB_IMFS_PASSWORD', ''),
            'unix_socket' => env('DB_IMFS_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        
        'gis' => [
            'driver' => 'mysql',
            'host' => env('DB_GIS_HOST', '127.0.0.1'),
            'port' => env('DB_GIS_PORT', '3306'),
            'database' => env('DB_GIS_DATABASE', 'dtc_digits_gis'),
            'username' => env('DB_GIS_USERNAME', 'root'),
            'password' => env('DB_GIS_PASSWORD', ''),
            'unix_socket' => env('DB_GIS_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
            'modes' => [
                //'ONLY_FULL_GROUP_BY', // Disable this to allow grouping by one column
                'STRICT_TRANS_TABLES',
                'NO_ZERO_IN_DATE',
                'NO_ZERO_DATE',
                'ERROR_FOR_DIVISION_BY_ZERO',
                // 'NO_AUTO_CREATE_USER',
                'NO_ENGINE_SUBSTITUTION'
            ],
        ],
        

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
