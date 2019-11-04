<?php
/***************************************************************************
 * @Description:
 * 
 * @FileName:         PayLog.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Mon 04 Nov 2019 12:23:45 PM CST
 ************************************************************************/
namespace Weixin\Tests\MiniProgram;

use Weixin\MiniProgram\LogInterface;

class PayLog implements LogInterface
{
    public function add(array $data)
    {
        //实现该方法以记录支付日志
        return true;
    }
}
