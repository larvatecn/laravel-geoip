<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\GeoIP\Providers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Larva\GeoIP\Contracts\IP;
use Larva\GeoIP\Contracts\Provider as ProviderContract;
use Larva\GeoIP\IPInfo;
use Larva\Support\IPHelper;

/**
 * 供应商基类
 * @author Tongle Xu <xutongle@gmail.com>
 */
abstract class AbstractProvider implements ProviderContract
{
    /**
     * The HTTP request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The HTTP Client instance.
     *
     * @var Client
     */
    protected $httpClient;

    /**
     * The Api Key.
     *
     * @var string
     */
    protected string $apiKey;

    /**
     * The custom Guzzle configuration options.
     *
     * @var array
     */
    protected array $guzzle = [];

    /**
     * Create a new provider instance.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $apiKey
     * @param array $guzzle
     */
    public function __construct(Request $request, string $apiKey, array $guzzle = [])
    {
        $this->guzzle = $guzzle;
        $this->request = $request;
        $this->apiKey = $apiKey;
    }

    /**
     * Get the name for the provider.
     *
     * @return string
     */
    abstract protected function getName(): string;

    /**
     * Map the raw ip info array to a IPInfo instance.
     *
     * @param array $ipInfo
     * @return IP
     */
    abstract protected function mapIPInfoToObject(array $ipInfo): IP;

    /**
     * Get the ip info response for the given ip.
     * @param string $ip
     * @return array
     */
    abstract public function getIPInfoResponse(string $ip): array;

    /**
     * 查询IP位置
     * @param string|null $ip
     * @return IP
     */
    public function get(string $ip = null): IP
    {
        $ip = $ip ?? $this->request->getClientIp();
        if (IPHelper::isPrivateForIpV4($ip)) {
            return $this->getDefaultIPInfo($ip, 'Local IP');
        } else {
            return $this->mapIPInfoToObject($this->getIPInfoResponse($ip));
        }
    }

    /**
     * 获取默认IP信息
     * @param string $ip
     * @param string $isp
     * @return IP
     */
    protected function getDefaultIPInfo(string $ip = '', string $isp = ''): IP
    {
        $ipInfo = [
            'ip' => $ip,
            'country_code' => null, 'country_name' => null, 'province' => null, 'city' => null, 'district' => null,
            'address' => null, 'longitude' => null, 'latitude' => null, 'isp' => $isp,
        ];
        return (new IPInfo())->map($ipInfo)->setRaw($ipInfo);
    }

    /**
     * Get a instance of the Guzzle HTTP client.
     *
     * @return Client
     */
    protected function getHttpClient(): Client
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client($this->guzzle);
        }
        return $this->httpClient;
    }

    /**
     * Set the Guzzle HTTP client instance.
     *
     * @param Client $client
     * @return $this
     */
    public function setHttpClient(Client $client)
    {
        $this->httpClient = $client;
        return $this;
    }

    /**
     * Set the request instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * 格式化省市
     * @param string $province
     * @return string
     */
    public function formatProvince(string $province): string
    {
        return str_replace(['省', '市', '维吾尔自治区', '回族自治区', '壮族自治区', '自治区', '特别行政区'], '', $province);
    }

    /**
     * 格式化省市
     * @param string $province
     * @return string
     */
    public function formatCity(string $province): string
    {
        return str_replace([
            '市', '彝族自治州', '朝鲜族自治州', '土家族苗族自治州', '藏族羌族自治州', '藏族自治州', '彝族自治州',
            '苗族侗族自治州', '布依族苗族自治州', '壮族苗族自治州', '傣族自治州', '白族自治州', '傈僳族自治州',
            '蒙古族藏族自治州', '蒙古自治州', '哈萨克自治州', '柯尔克孜自治州', '傣族景颇族', '哈尼族', '自治州',
            '特别行政区', '地区'
        ], '', $province);
    }

    /**
     * 格式化区
     * @param string $district
     * @return string
     */
    public function formatDistrict(string $district): string
    {
        return str_replace(['市', '区', '县'], '', $district);
    }
}
