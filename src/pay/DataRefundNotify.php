<?php
/***************************************************************************
 * @Description:      微信退款通知数据处理类
 * 
 * @FileName:         DataRefundNotify.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Thu 24 Oct 2019 02:54:29 PM CST
 ************************************************************************/
namespace Weixin\MiniProgram\Pay;

use Weixin\MiniProgram\WxException;

class DataRefundNotify extends DataBase
{
     /* 将xml转为array
     * @param WxPayConfigInterface $configObj  配置对象
     * @param string $xml
     * @throws WxException
     */
    public static function init($configObj, $xml)
    {   
        $obj = new self();
        $obj->fromXml($xml);
        //失败则直接返回失败
        if($obj->values['return_code'] != 'SUCCESS') {
            throw new WxException("通信失败！");
        }
        if ($obj->values['appid'] != $configObj->getAppid()) {
            throw new WxException("appid不匹配！");
        }
        if ($obj->values['mch_id'] != $configObj->getMchId()) {
            throw new WxException("mch_id不匹配！");
        }
        $obj->set('req_info', $obj->decrypt($configObj, $obj->values['req_info']));
        return $obj->getValues();
    }

    //解密
    private function decrypt($configObj, $info)
    {
        $xml =  openssl_decrypt(base64_decode($info, true), 'aes-256-ecb', md5($configObj->getMchKey()), OPENSSL_RAW_DATA);
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $arr;
    }
} 
