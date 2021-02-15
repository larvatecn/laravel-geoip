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
 * Class IPGeoLocation
 * @author Tongle Xu <xutongle@gmail.com>
 */
class IPGeoLocationProvider extends AbstractProvider
{
    /**
     * Get the name for the provider.
     *
     * @return string
     */
    protected function getName(): string
    {
        return 'IP Geolocation';
    }

    /**
     * Get the ip info response for the given ip.
     * @param string $ip
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getIPInfoResponse(string $ip): array
    {
        $response = $this->getHttpClient()->get('https://api.ipgeolocation.io/ipgeo', [
            'query' => [
                'ip' => $ip,
                'apiKey' => $this->apiKey,
                'lang' => 'cn'
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
            'ip' => $ipinfo['ip'],
            'country_code' => $ipinfo['country_code2'],
            'province' => $this->formatProvince($ipinfo['state_prov']),
            'city' => $this->formatCity($ipinfo['city']),
            'district' => $ipinfo['district'],
            'address' => $ipinfo['state_prov'] . $ipinfo['city'],
            'longitude' => $ipinfo['longitude'],
            'latitude' => $ipinfo['latitude'],
            'isp' => $ipinfo['isp'],
        ]);
    }
}
