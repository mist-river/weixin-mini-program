<?php
/***************************************************************************
 * @Description:      日志接口类
 * 
 * @FileName:         LogInterface.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Sun 20 Oct 2019 06:56:06 AM CST
 ************************************************************************/
namespace Weixin\MiniProgram;

interface LogInterface
{

    //记录日志
    public function add(array $data);

}
