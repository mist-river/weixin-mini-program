<?php
/***************************************************************************
 * @Description:      微信支付回调数据类
 * 
 * @FileName:         DataNotify.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Sun 20 Oct 2019 05:18:49 PM CST
 ************************************************************************/
namespace Weixin\MiniProgram\Pay;

use Weixin\MiniProgram\WxException;

class DataNotify extends DataBase
{
    /**
     * 生成签名 - 重写该方法
     * @param ConfigInterface $configObj  配置对象
     * @return 签名
     */
    public function makeSign($configObj)
    {
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->toUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$configObj->getMchKey();
        //签名步骤三：MD5加密或者HMAC-SHA256
        if(strlen($this->get('sign')) <= 32){
            //如果签名小于等于32个,则使用md5验证
            $string = md5($string);
        } else {
            //是用sha256校验
            $string = hash_hmac("sha256",$string ,$configObj->getMchKey());
        }
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * @param ConfigInterface $configObj  配置对象
     * 检测签名
     */
    public function checkSign($configObj)
    {
        if(!$this->isSignSet()){
            throw new WxException("签名错误！");
        }
        
        $sign = $this->makeSign($configObj, false);
        if($this->get('sign') == $sign){
            //签名正确
            return true;
        }   
        throw new WxException("签名错误！");
    }
    
    /**
     * 将xml转为array
     * @param ConfigInterface $configObj  配置对象
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
        } else {
            if($obj->values['result_code'] != 'SUCCESS') {
                throw new WxException($obj->values['err_code_des']);
            }
        }
        $obj->checkSign($configObj);
        return $obj->getValues();
    }
}
