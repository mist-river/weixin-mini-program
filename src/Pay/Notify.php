<?php
/***************************************************************************
 * @Description:      异步通知处理类
 * 
 * @FileName:         Notify.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Mon 21 Oct 2019 05:11:32 PM CST
 ************************************************************************/
namespace Weixin\MiniProgram\Pay;

use Weixin\MiniProgram\WxException;
use Weixin\MiniProgram\LogInterface;
use Weixin\MiniProgram\NotifyHandleInterface;

class Notify
{

    //支付通知
    public static function payNotify($configObj, $xml)
    {
        $flag = true;
        try {
            $data = DataNotify::init($configObj, $xml); 
        } catch (WxException $e) {
            $flag = false;
            $errmsg = $e->getMessage();
        }
        $notifyHandle = $configObj->getNotifyHandleClass();
        if ($notifyHandle instanceof NotifyHandleInterface) {
            $res = $notifyHandle->handlePayNotify($configObj, $data);
        } else {
            $flag = false;
            $errmsg = 'error!';
        }
        $dataObj = new DataBase();
        if ($res && $flag) {
            $dataObj->set('return_code', 'SUCCESS');
            $dataObj->set('return_msg', 'OK');
        } else {
            $errmsg = $errmsg ? $errmsg : 'FAIL';
            $dataObj->set('return_code', 'FAIL');
            $dataObj->set('return_msg', $errmsg);
        }
        $returnXml = $dataObj->toXml();
        $logdata['url'] = $configObj->getNotifyUrl();
        $logdata['xml'] = $xml;
        $logdata['response'] = $returnXml;
        $logdata['type'] = 'PAYNOTIFY';
        $logdata['ct'] = $logdata['mt'] = microtime(true);
        $logClass = $configObj->getPayLogClass();
        if ($logClass instanceof LogInterface) {
            $logClass->add($logdata);
        }
        return $returnXml;
    }

    //退款通知
    public static function refundNotify($configObj, $xml)
    {
        $flag = true;
        try {
            $data = DataRefundNotify::init($configObj, $xml); 
        } catch (WxException $e) {
            $flag = false;
            $errmsg = $e->getMessage();
        }
        $notifyHandle = $configObj->getNotifyHandleClass();
        if ($notifyHandle instanceof NotifyHandleInterface) {
            $res = $notifyHandle->handleRefundNotify($configObj, $data);
        } else {
            $flag = false;
            $errmsg = 'error!';
        }

        $dataObj = new DataBase();
        if ($res && $flag) {
            $dataObj->set('return_code', 'SUCCESS');
            $dataObj->set('return_msg', 'OK');
        } else {
            $errmsg = $errmsg ? $errmsg : 'FAIL';
            $dataObj->set('return_code', 'FAIL');
            $dataObj->set('return_msg', $errmsg);
        }
        $returnXml = $dataObj->toXml();
        $logdata['url'] = $configObj->getRefundNotifyUrl();
        $logdata['xml'] = $xml;
        $logdata['response'] = $returnXml;
        $logdata['type'] = 'REFUNDNOTIFY';
        $logdata['ct'] = $logdata['mt'] = microtime(true);
        $logClass = $configObj->getPayLogClass();
        if ($logClass instanceof LogInterface) {
            $logClass->add($logdata);
        }
        return $returnXml;
    }
}
