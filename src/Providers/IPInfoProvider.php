<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\GeoIP\Providers;

use Larva\GeoIP\Contracts\IP;
use Larva\GeoIP\IPInfo;
use Larva\GeoIP\Models\GeoIPv4;

/**
 * Class IPInfo
 * @author Tongle Xu <xutongle@gmail.com>
 */
class IPInfoProvider extends AbstractProvider
{
    /**
     * Get the name for the provider.
     * @return string
     */
    protected function getName(): string
    {
        return 'IPInfo';
    }

    /**
     * Get the ip info response for the given ip.
     * @param string $ip
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getIPInfoResponse(string $ip): array
    {
        $response = $this->getHttpClient()->get('https://ipinfo.io/' . $ip . '/geo', [
            'query' => [
                'token' => $this->apiKey,
            ],
            'headers' => ['Accept' => 'application/json',]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * Map the raw ipinfo array to a IPInfo instance.
     * @param array $ipinfo
     * @return IP
     */
    protected function mapIPInfoToObject(array $ipinfo): IP
    {
        $location = explode(',', $ipinfo['loc']);
        $ipinfo['isp'] = null;
        //通过非高精IP查询运营商
        $fuzzyIPInfo = GeoIPv4::getFuzzyIPInfo($ipinfo['ip']);
        if ($fuzzyIPInfo) {
            $ipinfo['isp'] = $fuzzyIPInfo->getISP();
        }
        return (new IPInfo())->setRaw($ipinfo)->map([
            'ip' => $ipinfo['ip'],
            'country_code' => $ipinfo['country'],
            'province' => $this->formatProvince($ipinfo['region']),
            'city' => $this->formatCity($ipinfo['city']),
            'district' => null,
            'address' => $ipinfo['region'] . $ipinfo['city'],
            'longitude' => $location[0],
            'latitude' => $location[1],
            'isp' => $ipinfo['isp']
        ]);
    }
}
