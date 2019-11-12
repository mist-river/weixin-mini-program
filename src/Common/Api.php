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

class Api
{
    const LOGIN_API = 'https://api.weixin.qq.com/sns/jscode2session';
    const TOKEN_API = 'https://api.weixin.qq.com/cgi-bin/token';
    const WXACODE_UNLIMITED_API = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit';
    const SUBSCRIBE_MESSAGE_SEND_API = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send';

    //小程序登录
    public static function login($configObj, $code)
    {   
        if(! $code) {
            throw new WxException("缺失code参数！");
        }
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
    public static function getAccessToken($configObj)
    {   
        $params['appid'] = $configObj->getAppid();
        $params['secret'] = $configObj->getAppSecret();
        $params['grant_type'] = 'client_credential';
        $url = self::TOKEN_API . '?' . http_build_query($params);
        $resJson = self::curlGet($url, array(), 0.5);
        $resArr  = json_decode($resJson, true);
        return $resArr;
    }

    //发送订阅消息
    public static function sendSubscribeMessage($accessToken, $touser, $template_id, $data, $page = '')
    {
        $params['touser'] = $touser;
        $params['template_id'] = $template_id;
        $params['page'] = $page;
        $params['data'] = $data;
        $paramsJson = json_encode($params, JSON_UNESCAPED_UNICODE);
        $url = self::SUBSCRIBE_MESSAGE_SEND_API . '?access_token=' . $accessToken;
        $resJson = self::curlPost($url, $paramsJson, 0.5);
        $resArr  = json_decode($resJson, true);
        return $resArr;
    }

    //生成小程序码
    public static function getWxacodeUnlimited($accessToken, $params)
    {   
        $url = self::WXACODE_UNLIMITED_API . '?access_token=' . $accessToken;
        $paramsJson = json_encode($params);
        $resJson = self::curlPost($url, $paramsJson, 3); 
        $resArr = json_decode($resJson, true);
        if ($resArr['errcode']) {
            throw new WxException($resArr['errmsg']);
        }   
        return "data:image/png;base64," . base64_encode($resJson);
    }   

    //curlGet
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

    //curlPost
    public static function curlPost($url, $params, $timeout)
    {
        $ch = curl_init();
        $curlVersion = curl_version();
        $ua = "WxTool/CURL/".$curlVersion['version'];

        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_USERAGENT, $ua);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
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
