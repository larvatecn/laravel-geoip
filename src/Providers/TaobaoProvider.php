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
 * Class Taobao
 * @author Tongle Xu <xutongle@gmail.com>
 */
class TaobaoProvider extends AbstractProvider
{
    /**
     * Get the name for the provider.
     *
     * @return string
     */
    protected function getName(): string
    {
        return 'taobao';
    }

    /**
     * Get the ip info response for the given ip.
     * @param string $ip
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getIPInfoResponse(string $ip): array
    {
        $response = $this->getHttpClient()->get('http://ip.taobao.com/service/outGetIpInfo', [
            'query' => [
                'ip' => $ip,
                'accessKey' => $this->apiKey,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * Map the raw ipinfo array to a IPInfo instance.
     *
     * @param array $ipInfo
     * @param bool $refresh
     * @return IP
     */
    protected function mapIPInfoToObject(array $ipInfo, bool $refresh = false): IP
    {
        return (new IPInfo())->setRaw($ipInfo)->map([
            'ip' => $ipInfo['data']['ip'],
            'province' => $this->formatProvince($ipInfo['data']['region']),
            'city' => $this->formatProvince($ipInfo['data']['city']),
            'district' => $this->formatDistrict($ipInfo['data']['county']),
            'address' => $ipInfo['data']['region'] . $ipInfo['data']['city'] . $ipInfo['data']['county'],
            'longitude' => null,
            'latitude' => null,
            'isp' => $ipInfo['data']['isp'],
        ])->refreshCache($refresh);
    }
}
