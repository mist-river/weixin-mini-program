<?php
/***************************************************************************
 * @Description:
 * 
 * @FileName:         DataOrder.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Sun 20 Oct 2019 05:18:49 PM CST
 ************************************************************************/
namespace Weixin\MiniProgram\Pay;

use Weixin\MiniProgram\WxException;

class DataOrder extends DataBase
{
    public function init($configObj)
    {
        $this->set('appid', $configObj->getAppid());
        $this->set('mch_id', $configObj->getMchId());
        $this->set('sign_type', $configObj->getSignType());
        $this->set('nonce_str', $this->getNonceStr());
    }
}
