<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\GeoIP\Contracts;

/**
 * Interface Factory
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
interface Factory
{
    /**
     * Get an GeoIP provider implementation.
     *
     * @param string $driver
     * @return \Larva\GeoIP\Contracts\Provider
     */
    public function driver($driver = null);
}
