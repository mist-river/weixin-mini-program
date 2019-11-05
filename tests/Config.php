<?php
/***************************************************************************
 * @Description:      微信具体配置类
 * 
 * @FileName:         Config.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Sun 20 Oct 2019 04:30:14 PM CST
 ************************************************************************/
namespace Weixin\Tests\MiniProgram;

use Weixin\MiniProgram\ConfigInterface;

class Config implements ConfigInterface
{
    public function getAppid()
    {
        return WxConfig::APPID;
    }

    public function getAppSecret()
    {
        return WxConfig::APPSECRET;
    }

    //获取商户id
    public function getMchId()
    {
        return WxConfig::MCH_ID; 
    }
    
    //获取商户key
    public function getMchKey()
    {
        return WxConfig::MCH_KEY; 
    }

    //获取加密方式
    public function getSignType()
    {
        return WxConfig::MCH_SIGN_TYPE;
    }   
    
    //获取回调地址
    public function getNotifyUrl()
    {
        return WxConfig::MCH_NOTIFY_URL;
    }
    
    //获取退款回调地址
    public function getRefundNotifyUrl()
    {
        return WxConfig::MCH_REFUND_NOTIFY_URL;
    }

    //获取支付日志记录类
    public function getPayLogClass()
    {
        return new PayLog();
    }

    //获取回调业务处理类
    public function getNotifyHandleClass()
    {
        return new NotifyHandle();
    }

    //获取证书
    public function getSSLCertPath(&$sslCertPath, &$sslKeyPath)
    {
        $root = getcwd();
        $sslCertPath = $root . '/cert/cert.pem';
        $sslKeyPath = $root . '/cert/key.pem';
    }
}
