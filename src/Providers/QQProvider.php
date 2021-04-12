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
use Larva\Support\LBSHelper;

/**
 * Class QQ
 * @author Tongle Xu <xutongle@gmail.com>
 */
class QQProvider extends AbstractProvider
{
    /**
     * Get the name for the provider.
     *
     * @return string
     */
    protected function getName(): string
    {
        return 'qq';
    }

    /**
     * Get the ip info response for the given ip.
     * @param string $ip
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getIPInfoResponse(string $ip): array
    {
        $response = $this->getHttpClient()->get('https://apis.map.qq.com/ws/location/v1/ip', [
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
        list($longitude, $latitude) = LBSHelper::GCJ02ToWGS84($ipinfo['result']['location']['lng'], $ipinfo['result']['location']['lat']);
        return (new IPInfo)->setRaw($ipinfo)->map([
            'ip' => $ipinfo['result']['ip'],
            'province' => $this->formatProvince($ipinfo['result']['ad_info']['province']),
            'city' => $this->formatProvince($ipinfo['result']['ad_info']['city']),
            'district' => $this->formatDistrict($ipinfo['result']['ad_info']['district']),
            'address' => $ipinfo['result']['ad_info']['province'] . $ipinfo['result']['ad_info']['city'] . $ipinfo['result']['ad_info']['district'],
            'longitude' => $longitude,
            'latitude' => $latitude
        ]);
    }

}
