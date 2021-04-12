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
 * Class IPApi
 * @author Tongle Xu <xutongle@gmail.com>
 */
class IPApiProvider extends AbstractProvider
{
    /**
     * Get the name for the provider.
     *
     * @return string
     */
    protected function getName(): string
    {
        return 'ip-api';
    }

    /**
     * Get the ip info response for the given ip.
     * @param string $ip
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getIPInfoResponse(string $ip): array
    {
        $response = $this->getHttpClient()->get('http://ip-api.com/json/' . $ip, [
            'query' => ['lang' => 'zh-CN']
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
            'ip' => $ipinfo['query'],
            'country_code' => $this->formatProvince($ipinfo['countryCode']),
            'province' => $this->formatProvince($ipinfo['regionName']),
            'city' => $this->formatCity($ipinfo['city']),
            'district' => null,
            'address' => $ipinfo['regionName'] . $ipinfo['city'],
            'longitude' => $ipinfo['lon'],
            'latitude' => $ipinfo['lat'],
            'isp' => $ipinfo['isp'],
        ]);
    }
}
