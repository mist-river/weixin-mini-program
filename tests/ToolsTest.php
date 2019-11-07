<?php
/***************************************************************************
 * @Description:
 * 
 * @FileName:         ToolsTest.php
 * @Author :          jintao5@staff.sina.com.cn
 * @CreatedTime:      Thu 07 Nov 2019 12:34:22 PM CST
 ************************************************************************/
namespace Weixin\Tests\MiniProgram;

use PHPUnit\Framework\TestCase;
use Weixin\Tests\MiniProgram\Config;
use Weixin\MiniProgram\WxException;
use Weixin\MiniProgram\Common\Tools;

class ToolsTest extends TestCase
{

    //测试解析加密数据
    public function testDecryptData()
    {
        $res = '';
        try {
            $configObj = new Config();
            //用户sessionKey
            $sessionKey = '';
            $encryptedData = "";
            $iv = "";
            $res = Tools::decryptData($configObj, $sessionKey, $encryptedData, $iv);
            $this->assertArrayHasKey('openId', $res);
            $this->assertArrayHasKey('nickName', $res);
            print_r($res);
        } catch (WxException $e) {
            print_r($e->getMessage());
        }
    }
}
