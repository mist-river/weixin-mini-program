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

class CommonTest extends TestCase
{
    //测试登录
    public function testLogin()
    {
        $code = '043PYgrY0a5icV1BwBqY06norY0PYgrK';
        $res = '';
        try {
            $res = Api::login($code);
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
        $res = '';
        try {
            $res = Api::getAccessToken();
            $this->assertArrayHasKey('access_token', $res);
            $this->assertArrayHasKey('expires_in', $res);
            print_r($res);
        } catch (WxException $e) {
            print_r($e->getMessage());
        }
    }
}
