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
     * @param array $ipinfo
     * @return IP
     */
    protected function mapIPInfoToObject(array $ipinfo)
    {
        return (new IPInfo)->setRaw($ipinfo)->map([
            'ip' => $ipinfo['data']['ip'],
            'province' => $this->formatProvince($ipinfo['data']['region']),
            'city' => $this->formatProvince($ipinfo['data']['city']),
            'district' => $this->formatDistrict($ipinfo['data']['county']),
            'address' => $ipinfo['data']['region'] . $ipinfo['data']['city'] . $ipinfo['data']['county'],
            'longitude' => null,
            'latitude' => null,
            'isp' => $ipinfo['data']['isp'],
        ]);
    }
}
