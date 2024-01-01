<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\GeoIP;

use Illuminate\Support\Facades\App;
use Larva\GeoIP\Contracts\IP;
use Larva\Support\IPHelper;
use Larva\Support\ISO3166;

/**
 * IP信息
 * @author Tongle Xu <xutongle@gmail.com>
 */
class IPInfo implements IP
{
    public string $ip;
    public ?string $country_code;
    public ?string $address;
    public ?string $province;
    public ?string $city;
    public ?string $district;

    /**
     * @var float|string|int|null
     */
    public $longitude;

    /**
     * @var float|string|int|null
     */
    public $latitude;

    public ?string $isp;

    /**
     * The ip raw attributes.
     *
     * @var array
     */
    public array $ipInfo = [];

    /**
     * 获取数字IP
     * @return int
     */
    public function getId(): int
    {
        return IPHelper::ip2Long($this->ip);
    }

    /**
     * 获取IP
     *
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * 获取国家代码
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    /**
     * 获取国家
     * @return string
     */
    public function getCountryName(): string
    {
        if (!empty($this->country_code)) {
            return ISO3166::country($this->country_code, App::getLocale());
        }
        return '';
    }

    /**
     * 获取省
     * @return string|null
     */
    public function getProvince(): ?string
    {
        return $this->province;
    }

    /**
     * 获取城市
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * 获取区县
     * @return string|null
     */
    public function getDistrict(): ?string
    {
        return $this->district;
    }

    /**
     * 获取地址
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * 获取经度
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return floatval($this->longitude);
    }

    /**
     * 获取维度
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return floatval($this->latitude);
    }

    /**
     * 获取运营商
     * @return string|null
     */
    public function getISP(): ?string
    {
        return $this->isp;
    }

    /**
     * 获取经纬度
     * @return string
     */
    public function getLocation(): string
    {
        if (!empty($this->longitude) && !empty($this->latitude)) {
            return $this->longitude . ',' . $this->latitude;
        }
        return '';
    }

    /**
     * Map the given array onto the ip properties.
     *
     * @param array $attributes
     * @return $this
     */
    public function map(array $attributes): IPInfo
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }

    /**
     * Get the raw ip array.
     *
     * @return array
     */
    public function getRaw(): array
    {
        return $this->ipInfo;
    }

    /**
     * Set the raw ip info array from the provider.
     *
     * @param array $ipInfo
     * @return $this
     */
    public function setRaw(array $ipInfo): IPInfo
    {
        $this->ipInfo = $ipInfo;
        return $this;
    }

    /**
     * 获取数组
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'ip' => $this->ip,
            'country_code' => $this->country_code,
            'country_name' => $this->getCountryName(),
            'province' => $this->province,
            'city' => $this->city,
            'district' => $this->district,
            'address' => $this->address,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'isp' => $this->isp,
        ];
    }

    /**
     * 输出字符串
     * @return string
     */
    public function __toString(): string
    {
        return $this->province . $this->city . $this->district . ' ' . $this->isp;
    }
}
