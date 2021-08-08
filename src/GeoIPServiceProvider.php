<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\GeoIP;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Larva\GeoIP\Contracts\Factory;

/**
 * GeoIP 服务提供者
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class GeoIPServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/geoip.php' => config_path('geoip.php'),
            ], 'geoip-config');
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if (!$this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/geoip.php', 'geoip');
        }

        $this->app->singleton(Factory::class, function () {
            return new GeoIPManager(Container::getInstance());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [Factory::class];
    }
}
