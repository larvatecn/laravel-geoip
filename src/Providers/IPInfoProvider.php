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
     * @param array $ipInfo
     * @param bool $refresh
     * @return IP
     */
    protected function mapIPInfoToObject(array $ipInfo, bool $refresh = false): IP
    {
        $location = explode(',', $ipInfo['loc']);
        $ipInfo['isp'] = null;
        //通过非高精IP查询运营商
        $fuzzyIPInfo = GeoIPv4::getFuzzyIPInfo($ipInfo['ip']);
        if ($fuzzyIPInfo) {
            $ipInfo['isp'] = $fuzzyIPInfo->getISP();
        }
        return (new IPInfo())->setRaw($ipInfo)->map([
            'ip' => $ipInfo['ip'],
            'country_code' => $ipInfo['country'],
            'province' => $this->formatProvince($ipInfo['region']),
            'city' => $this->formatCity($ipInfo['city']),
            'district' => null,
            'address' => $ipInfo['region'] . $ipInfo['city'],
            'longitude' => $location[0],
            'latitude' => $location[1],
            'isp' => $ipInfo['isp']
        ])->refreshCache($refresh);
    }
}
