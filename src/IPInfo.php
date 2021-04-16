<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\GeoIP;

use Larva\GeoIP\Models\GeoIPv4;
use Larva\Support\IPHelper;
use Larva\Support\ISO3166;

/**
 * IP信息
 * @author Tongle Xu <xutongle@gmail.com>
 */
class IPInfo implements Contracts\IP
{
    public $ip;
    public $country_code;
    public $address;
    public $province;
    public $city;
    public $district;
    public $longitude;
    public $latitude;
    public $isp;

    /**
     * The ip raw attributes.
     *
     * @var array
     */
    public $ipInfo = [];

    /**
     * 获取数字IP
     * @return int
     */
    public function getId()
    {
        return IPHelper::ip2Long($this->ip);
    }

    /**
     * 获取IP
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * 获取国家代码
     * @return string|null
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * 获取国家
     * @return string
     */
    public function getCountryName()
    {
        if (!empty($this->country_code)) {
            return ISO3166::country($this->country_code);
        }
        return '';
    }

    /**
     * 获取省
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * 获取城市
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * 获取区县
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * 获取地址
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * 获取经度
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * 获取维度
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * 获取运营商
     * @return string
     */
    public function getISP()
    {
        return $this->isp;
    }

    /**
     * 获取经纬度
     * @return string
     */
    public function getLocation()
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
    public function toArray()
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
     * 保存到数据库
     * @return IPInfo
     */
    public function save(): IPInfo
    {
        if (IPHelper::isPrivateForIpV4($this->ip)) {
            return $this;
        }
        $ipInfo = [];
        if (!empty($this->country_code)) {
            $ipInfo['country_code'] = $this->country_code;
        }
        if (!empty($this->province)) {
            $ipInfo['province'] = $this->province;
        }
        if (!empty($this->city)) {
            $ipInfo['city'] = $this->city;
        }
        if (!empty($this->district)) {
            $ipInfo['district'] = $this->district;
        }
        if (!empty($this->isp)) {
            $ipInfo['isp'] = $this->isp;
        }
        if (!empty($this->latitude) && !empty($this->longitude)) {
            $ipInfo['latitude'] = $this->latitude;
            $ipInfo['longitude'] = $this->longitude;
        }
        if (IPHelper::getIpVersion($this->ip) == IPHelper::IPV4) {
            GeoIPv4::updateOrCreate(['id' => $this->getId()], $ipInfo);
        }
        return $this;
    }
}
