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
}
