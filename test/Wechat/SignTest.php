<?php

/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2019/1/23
 * Time: 下午4:37
 */

namespace Ddup\Payments\Test\Wechat;

use Ddup\Payments\Wechat\Kernel\Support;
use Ddup\Payments\Test\PaymentTest;

class SignTest extends PaymentTest
{

    function test_string()
    {
        $param = [
            'package'   => 'prepay_id=wx2017033010242291fcfe0db70013231072',
            'timeStamp' => '1490840662',
            'nonceStr'  => '5K8264ILTKCH16CQ2502SI8ZNMTM67VS',
            'appId'     => 'wxAppId',
            'signType'  => 'MD5',
        ];

        $key = '{key}';

        $sign = Support::jsApiSign($param, $key);

        $this->assertEquals('appId=wxd678efh567hg6787&nonceStr=5K8264ILTKCH16CQ2502SI8ZNMTM67VS&package=prepay_id=wx2017033010242291fcfe0db70013231072&signType=MD5&timeStamp=1490840662&key=qazwsxedcrfvtgbyhnujmikolp111111', Support::jsApiSignString());

        $this->assertEquals('22D9B4E54AB1950F51E0649E8810ACD6', $sign);
    }
}

