# larvacms-geoip

<p align="center">LarvaCMS 的IP位置查询模块</p>

<p align="center">
<a href="https://packagist.org/packages/larvacms/geoip"><img src="https://poser.pugx.org/larvacms/geoip/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/larvacms/geoip"><img src="https://poser.pugx.org/larvacms/geoip/v/unstable.svg" alt="Latest Unstable Version"></a>
</p>

## 平台支持

- [百度](http://lbsyun.baidu.com)
- [高德](https://lbs.amap.com)
- [QQ](https://lbs.qq.com)
- [淘宝](http://ip.taobao.com)
- [IP-API](https://ip-api.com)
- [IPFinder](https://ipfinder.io)
- [IPInfo](https://ipinfo.io/)
- [IPIP](https://www.ipip.net)
- [LibreSpeed](https://www.librespeed.cn)
- [IPGeoLocation](https://ipgeolocation.io)

## 环境需求

- PHP >= 7.2.5

## Installation

```bash
composer require larvacms/geoip -vv
```

```php
    $info=  \LarvaCMS\GeoIP\GeoIP::get('218.1.2.3');
    $info=  \LarvaCMS\GeoIP\GeoIP::getRaw('218.1.2.3');
    $info=  \LarvaCMS\GeoIP\GeoIP::with('baidu')->get('218.1.2.3');
    $info=  \LarvaCMS\GeoIP\GeoIP::with('baidu')->getRaw('218.1.2.3');
```
