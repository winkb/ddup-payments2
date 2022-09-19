<?php

/**
 * Created by PhpStorm.
 * User: lixingbo
 * Date: 2019/1/23
 * Time: 下午4:37
 */

namespace Ddup\Payments\Test\Upay;

use Ddup\Part\Libs\Str;
use Ddup\Payments\Config\PayOrderStruct;
use Ddup\Payments\Test\PaymentTest;

class UpayTest extends PaymentTest
{

    public function test_upayPay()
    {

        try {
            $param = [
                "pay"          => 0,
                "id"           => null,
                "empty"        => "",
                'total_amount' => 0.01,
                'amount'       => 0.01,
                'order_no'     => Str::rand(20),
                'auth_code'    => 'this is mook auth_code',
                'subject'      => '收卷机'
            ];

            $this->app->create(
                'upay',
                [
                    'key'         => '',
                    'merchant_id' => '',
                    'terminal_id' => ''
                ]
            )->pay('microWechat', new PayOrderStruct($param));
        } catch (\Exception $exception) {
            $this->assertEquals('银联通道报错：授权码异常', $exception->getMessage());
        }
    }
}

