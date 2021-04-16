<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\GeoIP\Providers;

use Larva\GeoIP\Contracts\IP;
use Larva\GeoIP\IPInfo;
use Larva\GeoIP\Models\GeoIPv4;
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
    protected $ip;

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
     * Map the raw ipinfo array to a IPInfo instance.
     *
     * @param array $ipinfo
     * @return IP
     */
    protected function mapIPInfoToObject(array $ipinfo)
    {
        $location = LBSHelper::getCenterFromDegrees(LBSHelper::getAMAPRectangle($ipinfo['rectangle']));
        list($longitude, $latitude) = LBSHelper::GCJ02ToWGS84($location[0], $location[1]);
        $ipinfo['isp'] = null;
        //通过非高精IP查询运营商
        $fuzzyIPInfo = GeoIPv4::getFuzzyIPInfo($this->ip);
        if ($fuzzyIPInfo) {
            $ipinfo['isp'] = $fuzzyIPInfo->getISP();
        }
        return (new IPInfo)->setRaw($ipinfo)->map([
            'ip' => $this->ip,
            'province' => $this->formatProvince($ipinfo['province']),
            'city' => $this->formatCity($ipinfo['city']),
            'address' => $ipinfo['province'] . $ipinfo['city'],
            'longitude' => $longitude,
            'latitude' => $latitude,
            'isp' => $ipinfo['isp']
        ]);
    }
}
