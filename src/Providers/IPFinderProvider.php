<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\GeoIP\Providers;

use Illuminate\Support\Arr;
use Larva\GeoIP\Contracts\IP;
use Larva\GeoIP\IPInfo;

/**
 * Class IPFinder
 * @author Tongle Xu <xutongle@gmail.com>
 */
class IPFinderProvider extends AbstractProvider
{
    /**
     * Get the name for the provider.
     *
     * @return string
     */
    protected function getName(): string
    {
        return 'IP Finder';
    }

    /**
     * Get the ip info response for the given ip.
     * @param string $ip
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getIPInfoResponse(string $ip): array
    {
        $response = $this->getHttpClient()->post('https://api.ipfinder.io/v1/' . $ip, [
            'json' => [
                'token' => $this->apiKey,
                'format' => 'JSON'
            ],
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json',]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * Map the raw ipinfo array to a IPInfo instance.
     *
     * @param array $ipinfo
     * @return IP
     */
    protected function mapIPInfoToObject(array $ipinfo)
    {
        return (new IPInfo)->setRaw($ipinfo)->map([
            'ip' => $ipinfo['ip'],
            'country_code' => Arr::get($ipinfo, 'country_code', ''),
            'province' => $this->formatProvince(Arr::get($ipinfo, 'region_name', '')),
            'city' => $this->formatCity(Arr::get($ipinfo, 'city', '')),
            'district' => '',
            'address' => $ipinfo['region_name'] . $ipinfo['city'],
            'longitude' => Arr::get($ipinfo, 'longitude', ''),
            'latitude' => Arr::get($ipinfo, 'latitude', ''),
            'isp' => '',
        ]);
    }
}
