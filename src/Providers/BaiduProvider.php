<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\GeoIP\Providers;

use Larva\GeoIP\IPInfo;
use Larva\GeoIP\Models\GeoIPv4;
use Larva\Support\LBSHelper;

/**
 * 百度地图接口
 * @author Tongle Xu <xutongle@gmail.com>
 */
class BaiduProvider extends AbstractProvider
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
        return 'baidu';
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
        $response = $this->getHttpClient()->post('https://api.map.baidu.com/location/ip', [
            'form_params' => [
                'ip' => $ip,
                'ak' => $this->apiKey,
                'coor' => 'gcj02'
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * @param array $ipinfo
     * @return IPInfo
     */
    protected function mapIPInfoToObject(array $ipinfo)
    {
        list($longitude, $latitude) = LBSHelper::GCJ02ToWGS84(doubleval($ipinfo['content']['point']['x']), doubleval($ipinfo['content']['point']['y']));
        $ipinfo['isp'] = null;
        $ipinfo['country_code'] = null;
        //通过非高精IP查询运营商
        $fuzzyIPInfo = GeoIPv4::getFuzzyIPInfo($this->ip);
        if ($fuzzyIPInfo) {
            $ipinfo['country_code'] = $fuzzyIPInfo->getCountryCode();
            $ipinfo['isp'] = $fuzzyIPInfo->getISP();
        }
        return (new IPInfo)->setRaw($ipinfo)->map([
            'ip' => $this->ip,
            'country_code' => $ipinfo['country_code'],
            'province' => $this->formatProvince($ipinfo['content']['address_detail']['province']),
            'city' => $this->formatCity($ipinfo['content']['address_detail']['city']),
            'district' => $this->formatDistrict($ipinfo['content']['address_detail']['district']),
            'address' => $ipinfo['content']['address'],
            'longitude' => $longitude,
            'latitude' => $latitude,
            'isp' => $ipinfo['isp']
        ]);
    }
}
