<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
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
        $response = $this->getHttpClient()->post('https://forge.librespeed.cn/api/v2/location/ip', [
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
     * @param array $ipInfo
     * @param bool $refresh
     * @return IP
     */
    protected function mapIPInfoToObject(array $ipInfo, bool $refresh = false): IP
    {
        return (new IPInfo())->setRaw($ipInfo)->map([
            'ip' => $ipInfo['ip'],
            'country_code' => $ipInfo['country_code'],
            'province' => $this->formatProvince($ipInfo['province']),
            'city' => $this->formatCity($ipInfo['city']),
            'district' => $this->formatDistrict($ipInfo['district']),
            'address' => $ipInfo['address'],
            'longitude' => $ipInfo['longitude'],
            'latitude' => $ipInfo['latitude'],
            'isp' => $ipInfo['isp'],
        ])->refreshCache($refresh);
    }
}
