<?php
/***************************************************************************
 * @Description:      微信常用功能接口封装
 * 
 * @FileName:         Api.php
 * @Author :          taofeiyang@gmail.com
 * @CreatedTime:      Mon 04 Nov 2019 09:20:04 PM CST
 ************************************************************************/
namespace Weixin\MiniProgram\Common;

use Weixin\MiniProgram\WxException;
use Weixin\Tests\MiniProgram\Config;

class Api
{
    const LOGIN_API = 'https://api.weixin.qq.com/sns/jscode2session';
    const TOKEN_API = 'https://api.weixin.qq.com/cgi-bin/token';
    const WXACODE_UNLIMITED_API = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit';

    //小程序登录
    public static function login($code)
    {   
        if(! $code) {
            throw new WxException("缺失code参数！");
        }
        $configObj = new Config();
        $params['appid'] = $configObj->getAppid();
        $params['secret'] = $configObj->getAppSecret();
        $params['js_code'] = $code;
        $params['grant_type'] = 'authorization_code';
        $url = self::LOGIN_API . '?' . http_build_query($params);
        $resJson = self::curlGet($url, array(), 0.5);
        $resArr  = json_decode($resJson, true);
        return $resArr;
    }

    //获取access_token
    public static function getAccessToken()
    {   
        $configObj = new Config();
        $params['appid'] = $configObj->getAppid();
        $params['secret'] = $configObj->getAppSecret();
        $params['grant_type'] = 'client_credential';
        $url = self::TOKEN_API . '?' . http_build_query($params);
        $resJson = self::curlGet($url, array(), 0.5);
        $resArr  = json_decode($resJson, true);
        return $resArr;
    }

    //curl
    public static function curlGet($url, $headerArray, $timeout)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headerArray);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,$timeout);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            throw new WxException("curl出错，错误码:$error");
        }
    }
}
