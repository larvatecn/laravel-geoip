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
            'query' => [
                'lang' => 'zh-CN',
                'fields' => 'status,message,country,countryCode,region,regionName,city,district,lat,lon,isp,query'
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
        return (new IPInfo())->setRaw($ipInfo)->map([
            'ip' => $ipInfo['query'],
            'country_code' => $ipInfo['countryCode'],
            'province' => $ipInfo['regionName'],
            'city' => $ipInfo['city'],
            'district' => $ipInfo['district'],
            'address' => $ipInfo['regionName'] . $ipInfo['city'] . $ipInfo['district'],
            'longitude' => $ipInfo['lon'],
            'latitude' => $ipInfo['lat'],
            'isp' => $ipInfo['isp'],
        ]);
    }
}
