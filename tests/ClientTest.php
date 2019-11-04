<?php
/***************************************************************************
 * @Description:      支付客户端测试类
 * 
 * @FileName:         ClientTest.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Sun 03 Nov 2019 09:07:44 PM CST
 ************************************************************************/
namespace Weixin\Tests\MiniProgram;

use PHPUnit\Framework\TestCase;
use Weixin\MiniProgram\Pay\Client;
use Weixin\MiniProgram\Pay\DataOrder;
use Weixin\MiniProgram\WxException;

class ClientTest extends TestCase
{
    //测试统一下单
    public function testUnifiedOrder()
    {
        $configObj = new Config();
        $inputObj = new DataOrder();
        $inputObj->set('body', '商品标题');
        $inputObj->set('out_trade_no', 'TEST_369171060773659042');
        //单位为分
        $inputObj->set('total_fee', 1);
        //$inputObj->set('spbill_create_ip', $_SERVER['SERVER_ADDR']);
        $inputObj->set('notify_url', "https://test.com.cn/");
        $inputObj->set('trade_type', "JSAPI");
        $inputObj->set('openid', "");
        try {
            $order = Client::unifiedOrder($configObj, $inputObj);
            $this->assertArrayHasKey('appId', $order);
            $this->assertArrayHasKey('nonceStr', $order);
            $this->assertArrayHasKey('package', $order);
            $this->assertArrayHasKey('signType', $order);
            $this->assertArrayHasKey('timeStamp', $order);
            $this->assertArrayHasKey('paySign', $order);
            print_r($order);
        } catch (WxException $e) {
            print_r($e->getMessage());
        }
    }

    //测试订单查询
    public function testOrderQuery()
    {
        $configObj = new Config();
        $inputObj = new DataOrder();
        //$inputObj->set('transaction_id', '');
        $inputObj->set('out_trade_no', 'TEST_369171060773659042');
        try {
            $order = Client::orderQuery($configObj, $inputObj);
            $this->assertArrayHasKey('result_code', $order);
            $this->assertArrayHasKey('return_code', $order);
            print_r($order);
        } catch (WxException $e) {
            print_r($e->getMessage());
        }
    }

    //测试退款请求
    public function testRefundOrder()
    {
        $configObj = new Config();
        //发起退款请求
        $inputObj = new DataOrder();
        $inputObj->set('out_trade_no', 'TEST_371650969939910672');
        $inputObj->set('out_refund_no', "371964288019245632");
        $inputObj->set('total_fee', 1);
        $inputObj->set('refund_fee', 1);
        $inputObj->set('refund_desc', '测试退款');
        try {
            $res = Client::orderRefund($configObj, $inputObj);
            $this->assertArrayHasKey('refund_id', $res);
            print_r($res);
        } catch (WxException $e) {
            print_r($e->getMessage());
        }
    }

}
