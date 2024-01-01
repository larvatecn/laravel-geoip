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

    /**
     * 高精IP查询 (只有打开高精查询才会走下面 drivers 的查询通道，否则走本地缓存)
     */
    'precision' => false,

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
            'key' => 'alibaba-inc',
        ],
    ],
];
