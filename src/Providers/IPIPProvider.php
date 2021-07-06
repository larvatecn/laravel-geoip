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
 * IPIP
 * @author Tongle Xu <xutongle@gmail.com>
 */
class IPIPProvider extends AbstractProvider
{
    /**
     * @var string
     */
    public $ip;

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
     * @param array $ipinfo
     * @return IP
     */
    protected function mapIPInfoToObject(array $ipinfo): IP
    {
        $province = $ipinfo['data']['gps_district']['province'] ?? $ipinfo['data']['location']['province'];
        $city = $ipinfo['data']['gps_district']['city'] ?? $ipinfo['data']['location']['city'];
        $district = $ipinfo['data']['gps_district']['district'] ?? '';
        return (new IPInfo())->setRaw($ipinfo)->map([
            'ip' => $ipinfo['data']['ip'],
            'province' => $this->formatProvince($province),
            'city' => $this->formatCity($city),
            'district' => $this->formatDistrict($district),
            'address' => $province . $city . $district,
            'longitude' => $ipinfo['data']['longitude'],
            'latitude' => $ipinfo['data']['latitude'],
        ]);
    }
}
