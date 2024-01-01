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
     * @param array $ipInfo
     * @param bool $refresh
     * @return IP
     */
    protected function mapIPInfoToObject(array $ipInfo, bool $refresh = false): IP
    {
        $ipInfo['isp'] = null;
        return (new IPInfo())->setRaw($ipInfo)->map([
            'ip' => $ipInfo['query'],
            'country_code' => $this->formatProvince($ipInfo['countryCode']),
            'province' => $this->formatProvince($ipInfo['regionName']),
            'city' => $this->formatCity($ipInfo['city']),
            'district' => null,
            'address' => $ipInfo['regionName'] . $ipInfo['city'],
            'longitude' => $ipInfo['lon'],
            'latitude' => $ipInfo['lat'],
            'isp' => $ipInfo['isp'],
        ])->refreshCache($refresh);
    }
}
