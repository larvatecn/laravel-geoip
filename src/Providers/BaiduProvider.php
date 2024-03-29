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
 * 百度地图接口
 * @author Tongle Xu <xutongle@gmail.com>
 */
class BaiduProvider extends AbstractProvider
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
     * @param array $ipInfo
     * @return IP
     */
    protected function mapIPInfoToObject(array $ipInfo): IP
    {
        [$longitude, $latitude] = LBSHelper::GCJ02ToWGS84(doubleval($ipInfo['content']['point']['x']), doubleval($ipInfo['content']['point']['y']));
        $ipInfo['isp'] = null;
        $ipInfo['country_code'] = null;
        return (new IPInfo())->setRaw($ipInfo)->map([
            'ip' => $this->ip,
            'country_code' => $ipInfo['country_code'],
            'province' => $ipInfo['content']['address_detail']['province'],
            'city' => $ipInfo['content']['address_detail']['city'],
            'district' => $ipInfo['content']['address_detail']['district'],
            'address' => $ipInfo['content']['address'],
            'longitude' => $longitude,
            'latitude' => $latitude,
            'isp' => $ipInfo['isp']
        ]);
    }
}
