<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\GeoIP\Providers;

use GuzzleHttp\Exception\GuzzleException;
use Larva\GeoIP\Contracts\IP;
use Larva\GeoIP\GeoIPException;
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
     * @throws GuzzleException
     */
    public function getIPInfoResponse(string $ip): array
    {
        $response = $this->getHttpClient()->get('https://ip.taobao.com/outGetIpInfo', [
            'query' => [
                'ip' => $ip,
                'accessKey' => $this->apiKey,
            ]
        ]);
        $ipInfo = json_decode($response->getBody(), true);
        if ($ipInfo && $ipInfo['code'] == 0) {
            return $ipInfo['data'];
        }
        if ($ipInfo['code'] == 4) {
            throw new GeoIPException('qps超出');
        } else {
            throw new GeoIPException('服务器繁忙');
        }
    }

    /**
     * Map the raw ip info array to a IPInfo instance.
     *
     * @param array $ipInfo
     * @return IP
     */
    protected function mapIPInfoToObject(array $ipInfo): IP
    {
        $isp = match ($ipInfo['isp']) {
            '联通' => '中国联通',
            '电信' => '中国电信',
            '移动' => '中国移动',
            '教育网' => '中国教育网',
            default => $ipInfo['isp'],
        };
        return (new IPInfo())->setRaw($ipInfo)->map([
            'ip' => $ipInfo['ip'],
            'country_code' => $ipInfo['country_id'],
            'province' => $ipInfo['region'],
            'city' => $ipInfo['city'],
            'district' => $ipInfo['county'],
            'address' => $ipInfo['region'] . $ipInfo['city'] . $ipInfo['county'],
            'longitude' => null,
            'latitude' => null,
            'isp' => $isp,
        ]);
    }
}
