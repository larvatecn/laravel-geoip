<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 */

namespace Larva\GeoIP\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Larva\GeoIP\IPInfo;
use Larva\Support\IPHelper;
use Larva\Support\ISO3166;

/**
 * IP位置
 * @property int $id 数字IP地址
 * @property-read string $ip IP地址
 * @property string|null $country_code 国家代码
 * @property string|null $province 省
 * @property string|null $city 市
 * @property string|null $district 区县
 * @property string|null $isp 运营商
 * @property string|null $scenario 使用场景
 * @property float|null $latitude 纬度
 * @property float|null $longitude 经度
 * @property Carbon $updated_at 最后更新时间
 *
 * @property-read string $countryName 国家名称
 * @property-read string $address 粗略地址
 * @property-read string $location 经纬度
 * @method static Builder|GeoIPv4 ip($ip)
 * @method static Builder|GeoIPv4 originalIp($ip)
 * @method static GeoIPv4|null find($id)
 */
class GeoIPv4 extends Model
{
    public const CREATED_AT = null;

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'geo_ipv4';

    /**
     * 关闭主键自增
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array 批量赋值属性
     */
    public $fillable = [
        'id', 'country_code', 'province', 'city', 'district', 'isp', 'longitude', 'latitude', 'scenario'
    ];

    /**
     * 应该被转化为原生类型的属性
     *
     * @var array
     */
    protected $casts = [
        'longitude' => 'double',
        'latitude' => 'double',
        'updated_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['country_name', 'address', 'location'];

    /**
     * 查询指定IP
     *
     * @param Builder $query
     * @param string $ip
     * @return Builder
     */
    public function scopeOriginalIp(Builder $query, string $ip): Builder
    {
        $ipLong = IPHelper::ip2Long($ip);
        return $query->where('id', $ipLong);
    }

    /**
     * 查询指定IP段
     *
     * @param Builder $query
     * @param string $ip
     * @return Builder
     */
    public function scopeIp(Builder $query, string $ip): Builder
    {
        $ipLong = IPHelper::startIpv4Long($ip);
        return $query->where('id', $ipLong);
    }

    /**
     * 获取国家
     *
     * @return string
     */
    public function getCountryNameAttribute(): string
    {
        if (!is_null($this->attributes['country_code'])) {
            return ISO3166::country($this->attributes['country_code'], \Illuminate\Support\Facades\App::getLocale());
        }
        return '';
    }

    /**
     * 获取粗略的地址
     *
     * @return string
     */
    public function getAddressAttribute(): string
    {
        if (empty($this->province) && empty($this->city)) {//全空就返回国家
            return $this->countryName;
        } else {//返回省市区
            if ($this->country_code == 'CN' && in_array($this->province, ['北京', '上海', '天津', '重庆'])) {
                return $this->city . $this->district;
            }
            return $this->province . $this->city . $this->district;
        }
    }

    /**
     * 获取经纬度 经度在前
     *
     * @return string
     */
    public function getLocationAttribute(): string
    {
        if (!empty($this->longitude) && !empty($this->latitude)) {
            return $this->longitude . ',' . $this->latitude;
        }
        return '';
    }

    /**
     * 获取高精IP位置
     * @param string $ip
     * @return IPInfo|null
     */
    public static function getPrecisionIPInfo(string $ip): ?IPInfo
    {
        if (($geoIPModel = static::originalIp($ip)->first()) != null) {
            $ipInfo = $geoIPModel->toArray();
            return (new IPInfo())->map($ipInfo)->setRaw($ipInfo);
        }
        return null;
    }

    /**
     * 获取模糊IP位置
     * @param string $ip
     * @return IPInfo|null
     */
    public static function getFuzzyIPInfo(string $ip): ?IPInfo
    {
        if (($geoIPModel = static::ip($ip)->first()) != null) {
            $ipInfo = $geoIPModel->toArray();
            $ipInfo['ip'] = $ip;
            return (new IPInfo())->map($ipInfo)->setRaw($ipInfo);
        }
        return null;
    }

    /**
     * 获取IP位置
     * @param string $ip
     * @return IPInfo|null
     */
    public static function getIPInfo(string $ip): ?IPInfo
    {
        if (config('geoip.precision')) {
            return static::getPrecisionIPInfo($ip);
        }
        return static::getFuzzyIPInfo($ip);
    }
}
