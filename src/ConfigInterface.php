<?php
/***************************************************************************
 * @Description:      配置接口类
 * 
 * @FileName:         ConfigInterface.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Sun 20 Oct 2019 06:56:06 AM CST
 ************************************************************************/
namespace Weixin\MiniProgram;

interface ConfigInterface
{
    //获取appid
    public function getAppid();

    //获取商户id
    public function getMchId();
    
    //获取商户key
    public function getMchKey();
    
    //获取加密方式
    public function getSignType();
    
    //获取回调地址
    public function getNotifyUrl();
    
    //获取退款回调地址
    public function getRefundNotifyUrl();

    //获取支付日志记录类
    public function getPayLogClass();

    //获取回调业务处理类
    public function getNotifyHandleClass();

    //获取证书
    public function getSSLCertPath(&$sslCertPath, &$sslKeyPath);

}
