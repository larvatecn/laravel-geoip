<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\GeoIP\Contracts;

/**
 * IP提供商接口
 * @author Tongle Xu <xutongle@gmail.com>
 */
interface Provider
{
    /**
     * 获取IP位置
     * @param string $ip
     * @param bool $refresh 刷新缓存
     * @return IP
     */
    public function get(string $ip, bool $refresh = false): IP;
}
