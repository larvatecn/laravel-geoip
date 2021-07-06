<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\GeoIP\Providers;

use Larva\GeoIP\Contracts\IP;
use Larva\GeoIP\IPInfo;

/**
 * LibreSpeed Api
 * @author Tongle Xu <xutongle@gmail.com>
 */
class LibreSpeedProvider extends AbstractProvider
{
    /**
     * Get the name for the provider.
     *
     * @return string
     */
    protected function getName(): string
    {
        return 'LibreSpeed';
    }

    /**
     * Get the ip info response for the given ip.
     * @param string $ip
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getIPInfoResponse(string $ip): array
    {
        $response = $this->getHttpClient()->post('https://www.librespeed.cn/api/location/ip', [
            'query' => [
                'ip' => $ip,
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * @param array $ipinfo
     * @return IP
     */
    protected function mapIPInfoToObject(array $ipinfo): IP
    {
        return (new IPInfo())->setRaw($ipinfo)->map([
            'ip' => $ipinfo['ip'],
            'country_code' => $ipinfo['country_code'],
            'province' => $this->formatProvince($ipinfo['province']),
            'city' => $this->formatCity($ipinfo['city']),
            'district' => $this->formatDistrict($ipinfo['district']),
            'address' => $ipinfo['address'],
            'longitude' => $ipinfo['longitude'],
            'latitude' => $ipinfo['latitude'],
            'isp' => $ipinfo['isp'],
        ]);
    }
}
