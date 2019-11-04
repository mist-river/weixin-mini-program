<?php
/***************************************************************************
 * @Description:      支付接口访问类，包含微信支付API的封装，
 *                    类中方法为static方法
 * 
 * @FileName:         Client.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Fri 18 Oct 2019 08:51:49 AM CST
 ************************************************************************/
namespace Weixin\MiniProgram\Pay;

use Weixin\MiniProgram\WxException;
use Weixin\MiniProgram\LogInterface;

class Client
{
    const UNIFIED_ORDER_API = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    const ORDER_QUERY_API   = 'https://api.mch.weixin.qq.com/pay/orderquery';
    const ORDER_REFUND_API   = 'https://api.mch.weixin.qq.com/secapi/pay/refund';

    //统一下单
    public static function unifiedOrder($configObj, $inputObj, $timeOut = 5)
    {
        $inputObj->init($configObj);
        $inputObj->set('notify_url', $configObj->getNotifyUrl());
        $inputObj->setSign($configObj);
        $xml = $inputObj->toXml();

        $logdata['url'] = self::UNIFIED_ORDER_API;
        $logdata['xml'] = $xml;
        $logdata['header'] = [];
        $logdata['timeOut'] = $timeOut;
        $payStart = time();

        //发起支付请求
        $response = self::postXmlCurl($configObj, $xml, self::UNIFIED_ORDER_API, false, $timeOut);

        $payEnd   = time();
        $logdata['response'] = $response;
        $logdata['costTime'] = $payEnd - $payStart;
        $logdata['type'] = 'PAY';
        $logdata['ct'] = $logdata['mt'] = microtime(true);
        $logClass = $configObj->getPayLogClass();
        if ($logClass instanceof LogInterface) {
            $logClass->add($logdata);
        }

        $result = DataResult::init($configObj, $response);
        $dataJs = new DataJs();
        $parameters = $dataJs->getParameters($result, $configObj);
        return $parameters;
    }

    //订单查询
    public static function orderQuery($configObj, $inputObj, $timeOut = 5)
    {
        //初始化公共参数
        $inputObj->init($configObj);
        $inputObj->setSign($configObj);
        $xml = $inputObj->toXml();

        $logdata['url'] = self::ORDER_QUERY_API;
        $logdata['xml'] = $xml;
        $logdata['header'] = [];
        $logdata['timeOut'] = $timeOut;
        $start = time();

        //发起查询请求
        $response = self::postXmlCurl($configObj, $xml, self::ORDER_QUERY_API, false, $timeOut);

        $end   = time();
        $logdata['response'] = $response;
        $logdata['costTime'] = $end - $start;
        $logdata['type'] = 'QUERY';
        $logdata['ct'] = $logdata['mt'] = microtime(true);
        $logClass = $configObj->getPayLogClass();
        if ($logClass instanceof LogInterface) {
            $logClass->add($logdata);
        }

        $result = DataResult::init($configObj, $response);
        return $result;
    }

    //订单退款
    public static function orderRefund($configObj, $inputObj, $timeOut = 5)
    {
        $inputObj->init($configObj);
        $inputObj->set('notify_url', $configObj->getRefundNotifyUrl());
        $inputObj->setSign($configObj);
        $xml = $inputObj->toXml();

        $logdata['url'] = self::ORDER_REFUND_API;
        $logdata['xml'] = $xml;
        $logdata['header'] = [];
        $logdata['timeOut'] = $timeOut;
        $start = time();

        //发起退款请求
        $response = self::postXmlCurl($configObj, $xml, self::ORDER_REFUND_API, true);
        
        $end   = time();
        $logdata['response'] = $response;
        $logdata['costTime'] = $end - $start;
        $logdata['type'] = 'REFUND';
        $logdata['ct'] = $logdata['mt'] = microtime(true);
        $logClass = $configObj->getPayLogClass();
        if ($logClass instanceof LogInterface) {
            $logClass->add($logdata);
        }

        $result = DataResult::init($configObj, $response);
        return $result;
    }

    /**
     * 以post方式提交xml到对应的接口url
     * 
     * @param ConfigInterface $configObj  配置对象
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxException
     */
    private static function postXmlCurl($configObj, $xml, $url, $useCert = false, $second = 5)
    {       
        $ch = curl_init();
        $curlVersion = curl_version();
        $ua = "WxPay/CURL/".$curlVersion['version'];

        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        curl_setopt($ch,CURLOPT_USERAGENT, $ua); 
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            //证书文件请放入服务器的非web目录下
            $sslCertPath = "";
            $sslKeyPath = "";
            $configObj->getSSLCertPath($sslCertPath, $sslKeyPath);
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, $sslCertPath);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, $sslKeyPath);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            throw new WxException("curl出错，错误码:$error");
        }
    }
}
