<?php
/***************************************************************************
 * @Description:
 * 
 * @FileName:         PayLog.php
 * @Author :          jintao5@staff.sina.com.cn
 * @CreatedTime:      Mon 04 Nov 2019 12:23:45 PM CST
 ************************************************************************/
namespace Weixin\Tests\MiniProgram;

use Weixin\MiniProgram\LogInterface;

class PayLog implements LogInterface
{
    public function add(array $data)
    {
        return true;
    }
}
