<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\GeoIP\Providers;

use Larva\GeoIP\Contracts\IP;
use Larva\GeoIP\IPInfo;
use Larva\Support\LBSHelper;

/**
 * 高德地图API
 * @author Tongle Xu <xutongle@gmail.com>
 */
class AMapProvider extends AbstractProvider
{
    /**
     * @var string
     */
    protected string $ip;

    /**
     * Get the name for the provider.
     *
     * @return string
     */
    protected function getName(): string
    {
        return 'amap';
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
        $response = $this->getHttpClient()->get('https://restapi.amap.com/v3/ip', [
            'query' => [
                'ip' => $ip,
                'key' => $this->apiKey,
                'output' => 'JSON'
            ]
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
        $location = LBSHelper::getCenterFromDegrees(LBSHelper::getAMAPRectangle($ipInfo['rectangle']));
        [$longitude, $latitude] = LBSHelper::GCJ02ToWGS84($location[0], $location[1]);
        $ipInfo['isp'] = null;
        $ipInfo['country_code'] = null;
        return (new IPInfo())->setRaw($ipInfo)->map([
            'ip' => $this->ip,
            'country_code' => $ipInfo['country_code'],
            'province' => $this->formatProvince($ipInfo['province']),
            'city' => $this->formatCity($ipInfo['city']),
            'address' => $ipInfo['province'] . $ipInfo['city'],
            'longitude' => $longitude,
            'latitude' => $latitude,
            'isp' => $ipInfo['isp']
        ]);
    }
}
