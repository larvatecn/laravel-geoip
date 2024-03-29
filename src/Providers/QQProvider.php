<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\GeoIP\Providers;

use Larva\GeoIP\Contracts\IP;
use Larva\GeoIP\IPInfo;
use Larva\GeoIP\Models\GeoIPv4;
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
     * Map the raw ip info array to a IPInfo instance.
     *
     * @param array $ipInfo
     * @return IP
     */
    protected function mapIPInfoToObject(array $ipInfo): IP
    {
        [$longitude, $latitude] = LBSHelper::GCJ02ToWGS84($ipInfo['result']['location']['lng'], $ipInfo['result']['location']['lat']);
        $ipInfo['isp'] = null;
        $ipInfo['country_code'] = null;
        return (new IPInfo())->setRaw($ipInfo)->map([
            'ip' => $ipInfo['result']['ip'],
            'country_code' => $ipInfo['country_code'],
            'province' => $ipInfo['result']['ad_info']['province'],
            'city' => $ipInfo['result']['ad_info']['city'],
            'district' => $ipInfo['result']['ad_info']['district'],
            'address' => $ipInfo['result']['ad_info']['province'] . $ipInfo['result']['ad_info']['city'] . $ipInfo['result']['ad_info']['district'],
            'longitude' => $longitude,
            'latitude' => $latitude,
            'isp' => $ipInfo['isp']
        ]);
    }
}
