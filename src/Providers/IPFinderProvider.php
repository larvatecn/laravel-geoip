<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
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
     * Map the raw ip info array to a IPInfo instance.
     *
     * @param array $ipInfo
     * @return IP
     */
    protected function mapIPInfoToObject(array $ipInfo): IP
    {
        return (new IPInfo())->setRaw($ipInfo)->map([
            'ip' => $ipInfo['ip'],
            'country_code' => Arr::get($ipInfo, 'country_code', ''),
            'province' => Arr::get($ipInfo, 'region_name', ''),
            'city' => Arr::get($ipInfo, 'city', ''),
            'district' => null,
            'address' => $ipInfo['region_name'] . $ipInfo['city'],
            'longitude' => Arr::get($ipInfo, 'longitude', ''),
            'latitude' => Arr::get($ipInfo, 'latitude', ''),
            'isp' => $ipInfo['isp'],
        ]);
    }
}
