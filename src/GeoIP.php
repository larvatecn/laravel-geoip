<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\GeoIP;

use Illuminate\Support\Facades\Facade;
use Larva\GeoIP\Contracts\Factory;

/**
 * Class GeoIP
 * @method static \Larva\GeoIP\Contracts\Provider with(string $driver = null)
 * @method static \Larva\GeoIP\IPInfo get(string $ip = '')
 * @method static array getRaw(string $ip = '')
 * @see \Larva\GeoIP\GeoIPManager
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class GeoIP extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return Factory::class;
    }
}
