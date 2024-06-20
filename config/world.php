<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Allowed countries to be loaded
    | Leave it empty to load all countries else include the country iso2
    | value in the allowed_countries array
    |--------------------------------------------------------------------------
    */
    'allowed_countries' => [
        'CO',
    ],

    /*
    |--------------------------------------------------------------------------
    | Disallowed countries to not be loaded
    | Leave it empty to allow all countries to be loaded else include the
    | country iso2 value in the disallowed_countries array
    |--------------------------------------------------------------------------
    */
    'disallowed_countries' => [],

    /*
    |--------------------------------------------------------------------------
    | Supported locales.
    |--------------------------------------------------------------------------
    */
    'accepted_locales' => [
        'ar',
        'bn',
        'br',
        'de',
        'en',
        'es',
        'fr',
        'hr',
        'it',
        'ja',
        'kr',
        'nl',
        'pl',
        'pt',
        'ro',
        'ru',
        'tr',
        'zh',
    ],
    /*
    |--------------------------------------------------------------------------
    | Enabled modules.
    | The cities module depends on the states module.
    |--------------------------------------------------------------------------
    */
    'modules' => [
        'states' => true,
        'cities' => true,
        'timezones' => false,
        'currencies' => false,
        'languages' => false,
    ],
    /*
    |--------------------------------------------------------------------------
    | Routes.
    |--------------------------------------------------------------------------
    */
    'routes' => true,
    /*
    |--------------------------------------------------------------------------
    | Connection.
    |--------------------------------------------------------------------------
    */
    'connection' => env('WORLD_DB_CONNECTION', env('DB_CONNECTION')),
    /*
    |--------------------------------------------------------------------------
    | Migrations.
    |--------------------------------------------------------------------------
    */
    'migrations' => [
        'countries' => [
            'table_name' => 'countries',
            'optional_fields' => [
                'phone_code' => [
                    'required' => true,
                    'type' => 'string',
                    'length' => 5,
                ],
                'iso3' => [
                    'required' => true,
                    'type' => 'string',
                    'length' => 3,
                ],
            ],
        ],
        'states' => [
            'table_name' => 'states',
            'optional_fields' => [
                'country_code' => [
                    'required' => true,
                    'type' => 'string',
                    'length' => 3,
                ],
                'state_code' => [
                    'required' => false,
                    'type' => 'string',
                    'length' => 5,
                ],
            ],
        ],
        'cities' => [
            'table_name' => 'cities',
            'optional_fields' => [
                'country_code' => [
                    'required' => true,
                    'type' => 'string',
                    'length' => 3,
                ],
                'state_code' => [
                    'required' => false,
                    'type' => 'string',
                    'length' => 5,
                ],
            ],
        ],
        'timezones' => [
            'table_name' => 'timezones',
        ],
        'currencies' => [
            'table_name' => 'currencies',
        ],
        'languages' => [
            'table_name' => 'languages',
        ],
    ],
];
