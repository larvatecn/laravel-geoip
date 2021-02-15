<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\GeoIP\Contracts;

/**
 * IP信息接口
 * @author Tongle Xu <xutongle@gmail.com>
 */
interface IP
{
    /**
     * 获取IP
     * @return string
     */
    public function getIp(): string;

    /**
     * 获取国家
     *
     * @return string
     */
    public function getCountryCode(): string;

    /**
     * 获取省
     * @return string
     */
    public function getProvince(): string;

    /**
     * 获取市
     * @return string
     */
    public function getCity(): string;

    /**
     * 获取区县
     * @return string
     */
    public function getDistrict(): string;

    /**
     * 获取地址
     * @return string
     */
    public function getAddress(): string;

    /**
     * 获取经度
     * @return float
     */
    public function getLongitude(): float;

    /**
     * 获取维度
     * @return float
     */
    public function getLatitude(): float;

    /**
     * 获取ISP运营商
     * @return string
     */
    public function getISP(): string;

    /**
     * 获取国家
     *
     * @return string
     */
    public function getCountryName(): string;

    /**
     * 获取经纬度
     *
     * @return string
     */
    public function getLocation(): string;
}
