<?php
/***************************************************************************
 * @Description:      数据对象基础类
 * 
 * @FileName:         DataBase.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Fri 18 Oct 2019 08:55:57 AM CST
 ************************************************************************/
namespace Weixin\MiniProgram\Pay;

use Weixin\MiniProgram\WxException;

class DataBase
{
    protected $values = array();

    public function set($name, $value) 
    {
        $this->values[$name] = $value;
    }

    public function get($name) 
    {
        return $this->values[$name];
    }
    
    public function getValues()
    {
        return $this->values;
    }

    /**
    * 设置签名，详见签名生成算法
    * @param string $value 
    **/
    public function setSign($configObj)
    {
        $sign = $this->makeSign($configObj);
        $this->values['sign'] = $sign;
        return $sign;
    }

    /**
     * 生成签名
     * @param array $configObj  配置数组
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用setSign方法赋值
     */
    public function makeSign($configObj)
    {
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->toUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$configObj->getMchKey();
        //签名步骤三：MD5加密或者HMAC-SHA256
        if($configObj->getSignType() == "MD5"){
            $string = md5($string);
        } else if($configObj->getSignType() == "HMAC-SHA256") {
            $string = hash_hmac("sha256",$string ,$configObj->getMchKey());
        } else {
            throw new WxException("签名类型不支持！");
        }
        
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    
    /**
    * 判断签名是否存在
    * @return true 或 false
    **/
    public function isSignSet()
    {
        return array_key_exists('sign', $this->values);
    }

    /**
     * 格式化参数格式化成url参数
     */
    public function toUrlParams()
    {
        $buff = "";
        foreach ($this->values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 输出xml字符
    **/
    public function toXml()
    {
        if(!is_array($this->values) || count($this->values) <= 0)
        {
            throw new WxException("数组数据异常！");
        }
        
        $xml = "<xml>";
        foreach ($this->values as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml; 
    }

    /**
     * 将xml转为array
     * @param string $xml
     */
    public function fromXml($xml)
    {   
        if(!$xml){
            throw new WxException("xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $this->values;
    }

    /**
    * 
    * 产生随机字符串，不长于32位
    * @param int $length
    * @return 产生的随机字符串
    */
    public function getNonceStr($length = 32) 
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
        $len = strlen($chars);
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {  
            $str .= substr($chars, mt_rand(0, $len-1), 1);  
        } 
        return $str;
    }

}
