<?php
/***************************************************************************
 * @Description:
 * 
 * @FileName:         NotifyHandle.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Mon 04 Nov 2019 03:40:53 PM CST
 ************************************************************************/
namespace Weixin\Tests\MiniProgram;

use Weixin\MiniProgram\NotifyHandleInterface;

class NotifyHandle implements NotifyHandleInterface
{
    //处理支付异步通知
    public function handlePayNotify($configObj, $data)
    {
        //在此处进行业务参数验证及业务处理
        return true;
    }

    //处理退款异步通知
    public function handleRefundNotify($configObj, $data)
    {
        //在此处进行业务参数验证及业务处理
        return true;
    }
}
