<?php
/***************************************************************************
 * @Description:      异步通知测试类
 * 
 * @FileName:         Notify.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Mon 04 Nov 2019 03:23:01 PM CST
 ************************************************************************/
namespace Weixin\Tests\MiniProgram;

use PHPUnit\Framework\TestCase;
use Weixin\MiniProgram\Pay\Notify;
use Weixin\MiniProgram\Pay\DataOrder;
use Weixin\MiniProgram\WxException;

class NotifyTest extends TestCase
{
    //测试支付通知
    public function testPayNotify()
    {
        $xml = "";
        $configObj = new Config();
        $res = Notify::payNotify($configObj, $xml);
        //print_r($res);
        //以下代码仅为测试时使用
        $arr = json_decode(json_encode(simplexml_load_string($res)), true);
        $this->assertArrayHasKey('return_code', $arr);
        $this->assertArrayHasKey('return_msg', $arr);
        print_r($arr);
    }

    //测试退款通知
    public function testRefundNotify()
    {
        $xml = "";
        $configObj = new Config();
        $res = Notify::refundNotify($configObj, $xml);
        //print_r($res);
        //以下代码仅为测试时使用
        $arr = json_decode(json_encode(simplexml_load_string($res)), true);
        $this->assertArrayHasKey('return_code', $arr);
        $this->assertArrayHasKey('return_msg', $arr);
        print_r($arr);
    }
}
