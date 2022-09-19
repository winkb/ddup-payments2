<?php

namespace Ddup\Payments\Fuyou\Kernel;


use Ddup\Part\Libs\Arr;
use Ddup\Part\Message\MsgFromArray;
use Ddup\Part\Message\MsgToXml;
use Ddup\Payments\Exceptions\PayPaymentException;

class Support
{

    public static function getBaseUri(FuyouConfig $config)
    {
        switch (strtolower($config->mode)) {
            case $config::MODE_PROD:
                return 'https://spay-mc.fuioupay.com';
                break;
            default:
                return 'https://fundwx.fuiou.com';
                break;
        }
    }

    private static function argSort($param)
    {
        ksort($param);
        reset($param);
        return $param;
    }

    private static function createLinkstring($param)
    {
        $tmp = [];

        foreach ($param as $key => $val) {
            $tmp[] = "{$key}={$val}";
        }

        return join('&', $tmp);
    }

    private static function encode($param, $pem_path)
    {
        if (!file_exists($pem_path)) {
            throw new PayPaymentException('富友秘钥不存在');
        }

        $pem    = file_get_contents($pem_path);
        $pkeyid = openssl_pkey_get_private($pem);

        openssl_sign($param, $sign, $pkeyid, OPENSSL_ALGO_MD5);

        return base64_encode($sign);
    }

    public static function signString($param)
    {
        Arr::filterCallback($param, function ($k) {
            return $k == 'sign' || strstr($k, 'reserved');
        });

        $param = self::argSort($param);

        return self::createLinkstring($param);
    }

    public static function sign($param, $pem_path)
    {
        $string = self::signString($param);

        return self::encode($string, $pem_path);
    }

    public static function charsetGbk(&$params)
    {
        foreach ($params as &$str) {
            if (!is_string($str)) continue;

            if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $str) > 0) {
                $str = iconv('UTF-8', 'GBK', $str);
            }
        }
    }

    public static function toXml($data)
    {
        $xml_content = new MsgToXml(new MsgFromArray($data));

        return "<?xml version=\"1.0\" encoding=\"GBK\" standalone=\"yes\"?><xml>" . $xml_content . "</xml>";
    }

    public static function bodyEncode($xml)
    {
        return urlencode(urlencode($xml));
    }

    public static function bodyDecode($xml)
    {
        return urldecode($xml);
    }
}
