<?php
/***************************************************************************
 * @Description:      工具类
 * 
 * @FileName:         Tools.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Thu 07 Nov 2019 10:57:27 AM CST
 ************************************************************************/
namespace Weixin\MiniProgram\Common;

use Weixin\MiniProgram\WxException;

class Tools
{
    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public static function decryptData($configObj, $sessionKey, $encryptedData, $iv)
    {   
        if (strlen($sessionKey) != 24) {
            throw new WxException("sessionKey有误!");
        }   
        $aesKey = base64_decode($sessionKey);
        if (strlen($iv) != 24) {
            throw new WxException("iv值有误!");
        }   
        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $data=json_decode($result, true);
        if (!$data) {
            throw new WxException("encryptedData有误!");
        }
        //注：时间戳暂未做校验
        if($data['watermark']['appid'] != $configObj->getAppid()) {
            throw new WxException("数据有误!");
        }
        return $data;
    }
}
