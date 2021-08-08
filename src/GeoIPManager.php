<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\GeoIP;

use Illuminate\Support\Arr;
use Illuminate\Support\Manager;
use Larva\GeoIP\Providers\AbstractProvider;
use Larva\GeoIP\Contracts\Provider as ProviderContract;

/**
 * IP管理器
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class GeoIPManager extends Manager implements Contracts\Factory
{
    /**
     * Get a driver instance.
     *
     * @param string $driver
     * @return ProviderContract
     */
    public function with(string $driver): ProviderContract
    {
        return $this->driver($driver);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return ProviderContract
     */
    protected function createAmapDriver(): ProviderContract
    {
        $config = $this->config->get('geoip.drivers.amap', []);
        return $this->buildProvider(Providers\AMapProvider::class, $config);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return ProviderContract
     */
    protected function createBaiduDriver(): ProviderContract
    {
        $config = $this->config->get('geoip.drivers.baidu', []);
        return $this->buildProvider(Providers\BaiduProvider::class, $config);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return ProviderContract
     */
    protected function createIpapiDriver(): ProviderContract
    {
        $config = $this->config->get('geoip.drivers.ip-api', []);
        return $this->buildProvider(Providers\IPApiProvider::class, $config);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return ProviderContract
     */
    protected function createIpfinderDriver(): ProviderContract
    {
        $config = $this->config->get('geoip.drivers.ip-finder', []);
        return $this->buildProvider(Providers\IPFinderProvider::class, $config);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return ProviderContract
     */
    protected function createIpgeolocationDriver(): ProviderContract
    {
        $config = $this->config->get('geoip.drivers.ip-geolocation', []);
        return $this->buildProvider(Providers\IPGeoLocationProvider::class, $config);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return ProviderContract
     */
    protected function createIpinfoDriver(): ProviderContract
    {
        $config = $this->config->get('geoip.drivers.ip-info', []);
        return $this->buildProvider(Providers\IPInfoProvider::class, $config);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return ProviderContract
     */
    protected function createIpipDriver(): ProviderContract
    {
        $config = $this->config->get('geoip.drivers.ipip', []);
        return $this->buildProvider(Providers\IPIPProvider::class, $config);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return ProviderContract
     */
    protected function createLibrespeedDriver(): ProviderContract
    {
        $config = $this->config->get('geoip.drivers.librespeed', []);
        return $this->buildProvider(Providers\LibreSpeedProvider::class, $config);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return ProviderContract
     */
    protected function createQqDriver(): ProviderContract
    {
        $config = $this->config->get('geoip.drivers.qq', []);
        return $this->buildProvider(Providers\QQProvider::class, $config);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return ProviderContract
     */
    protected function createTaobaoDriver(): ProviderContract
    {
        $config = $this->config->get('geoip.drivers.taobao', []);
        return $this->buildProvider(Providers\TaobaoProvider::class, $config);
    }

    /**
     * Build an GeoIP provider instance.
     *
     * @param string $provider
     * @param array $config
     * @return ProviderContract
     */
    public function buildProvider(string $provider, array $config): ProviderContract
    {
        return new $provider($this->container['request'], Arr::get($config, 'key', ''), Arr::get($config, 'guzzle', []));
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('geoip.default');
    }
}
