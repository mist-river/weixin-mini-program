<?php
/***************************************************************************
 * @Description:      通知处理接口类
 * 
 * @FileName:         NotifyHandleInterface.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Sun 20 Oct 2019 06:56:06 AM CST
 ************************************************************************/
namespace Weixin\MiniProgram;

interface NotifyHandleInterface
{

    //处理支付异步通知
    public function handlePayNotify($configObj, $data);

    //处理退款异步通知
    public function handleRefundNotify($configObj, $data);
}
