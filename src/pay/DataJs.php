<?php
/***************************************************************************
 * @Description:
 * 
 * @FileName:         DataJs.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Mon 21 Oct 2019 08:36:15 AM CST
 ************************************************************************/
namespace Weixin\MiniProgram\Pay;

use Weixin\MiniProgram\WxException;

class DataJs extends DataBase
{
    //获取JSAPI支付参数
    public function getParameters($order, $configObj)
    {
        $this->set('appId', $configObj->getAppid());
        $this->set('timeStamp', time());
        $this->set('nonceStr', self::getNonceStr());
        $this->set('package', "prepay_id=" . $order['prepay_id']);
        $this->set('signType', $configObj->getSignType());
        $this->set('paySign', $this->makeSign($configObj));
        return $this->getValues();
    }
}
