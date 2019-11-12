<?php
/***************************************************************************
 * @Description:      微信通用功能测试类
 * 
 * @FileName:         CommonTest.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Tue 05 Nov 2019 08:50:36 AM CST
 ************************************************************************/
namespace Weixin\Tests\MiniProgram;

use PHPUnit\Framework\TestCase;
use Weixin\MiniProgram\WxException;
use Weixin\MiniProgram\Common\Api;

class ApiTest extends TestCase
{
    //测试登录
    public function testLogin()
    {
        $configObj = new Config();
        $code = '043e4V312m7sVW0j6L212bpH312e4V3w';
        $res = '';
        try {
            $res = Api::login($configObj, $code);
            $this->assertArrayHasKey('session_key', $res);
            $this->assertArrayHasKey('openid', $res);
            print_r($res);
        } catch (WxException $e) {
            print_r($e->getMessage());
        }
    }
    
    //测试获取access_token
    public function testGetAccessToken()
    {
        $configObj = new Config();
        $res = '';
        try {
            $res = Api::getAccessToken($configObj);
            $this->assertArrayHasKey('access_token', $res);
            $this->assertArrayHasKey('expires_in', $res);
            print_r($res);
        } catch (WxException $e) {
            print_r($e->getMessage());
        }
    }

    //测试发送模板消息
    public function testSendSubscribeMessage()
    {
        $res = '';
        try {
            $accessToken = '';
            //用户openid
            $touser = '';
            //模板id
            $template_id = '';
            $data['thing4']['value'] = '测试报名活动';
            $data['date5']['value'] = '2017-01-05 9:30';
            $data['phrase3']['value'] = '报名成功';
            $page = 'index';
            $res = Api::sendSubscribeMessage($accessToken, $touser, $template_id, $data, $page);
            $this->assertArrayHasKey('errcode', $res);
            $this->assertArrayHasKey('errmsg', $res);
            print_r($res);
        } catch (WxException $e) {
            print_r($e->getMessage());
        }
    }

    //测试生成小程序码
    //https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.getUnlimited.html
    public function testGetWxacodeUnlimited()
    {
        try {
            $accessToken = '';
            $params['scene'] = 'xxxx';
            $params['is_hyaline'] = true;
            $params['auto_color'] = true;
            $params['line_color'] = ["r"=>185, "g"=>185, "b"=>185];
            $res = Api::getWxacodeUnlimited($accessToken, $params);
            $this->assertNotEmpty($res);
            print_r($res);
        } catch (WxException $e) {
            print_r($e->getMessage());
        }
    }
}
