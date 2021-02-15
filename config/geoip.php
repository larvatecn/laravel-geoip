<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default GeoIP Driver
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default geoip driver that should be used
    | by the framework.
    |
    | Supported Drivers: "baidu", "amap", "qq", "taobao", "ip-api", "ip-geolocation", "ip-finder"
    */

    'default' => 'ip-api',

    /*
    |--------------------------------------------------------------------------
    | GeoIP Drivers
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many geoip "drivers" as you wish. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'drivers' => [
        'amap' => [
            'key' => '',
        ],
        'baidu' => [
            'key' => '',
        ],
        'ip-api' => [
        ],
        'ip-geolocation' => [
            'key' => '',
        ],
        'ip-info' => [
            'key' => '',
        ],
        'ip-finder' => [
            'key' => '',
        ],
        'qq' => [
            'key' => '',
        ],
        'taobao' => [
            'key' => '',
        ],
    ],
];
