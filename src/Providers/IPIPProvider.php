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
 * IPIP
 * @author Tongle Xu <xutongle@gmail.com>
 */
class IPIPProvider extends AbstractProvider
{
    /**
     * @var string
     */
    public string $ip;

    /**
     * Get the name for the provider.
     *
     * @return string
     */
    protected function getName(): string
    {
        return 'IPIP';
    }

    /**
     * Get the ip info response for the given ip.
     * @param string $ip
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getIPInfoResponse(string $ip): array
    {
        $this->ip = $ip;
        $response = $this->getHttpClient()->get('https://ipapi.ipip.net/location/geo', [
            'query' => [
                'ip' => $ip,
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Token' => $this->apiKey
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
        $province = $ipInfo['data']['gps_district']['province'] ?? $ipInfo['data']['location']['province'];
        $city = $ipInfo['data']['gps_district']['city'] ?? $ipInfo['data']['location']['city'];
        $district = $ipInfo['data']['gps_district']['district'] ?? '';
        return (new IPInfo())->setRaw($ipInfo)->map([
            'ip' => $ipInfo['data']['ip'],
            'province' => $this->formatProvince($province),
            'city' => $this->formatCity($city),
            'district' => $this->formatDistrict($district),
            'address' => $province . $city . $district,
            'longitude' => $ipInfo['data']['longitude'],
            'latitude' => $ipInfo['data']['latitude'],
        ])->refreshCache($refresh);
    }
}
