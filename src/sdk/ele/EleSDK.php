<?php
/**
 * Created by PhpStorm.
 * User: Teemo
 * Date: 2018/6/20
 * Time: 15:46
 */

include('tool/AnubisApiHelper.php');
include('tool/HttpClient.php');
//include('./tool/EleOpenConfig.php');
date_default_timezone_set('PRC');

class EleSDK
{
    private $appId;
    private $secretKey;
    private $token;
    private $apiUrl = 'https://exam-anubis.ele.me/anubis-webapi'; // 测试地址
    // private $apiUrl = 'https://open-anubis.ele.me/anubis-webapi'; // 正式地址
    private $apiGetToken  = '/get_access_token';   // 获取token
    private $apiCreate    = '/v2/order';           // 下单
    private $apiCancle    = '/v2/order/cancel';    // 撤单
    private $apiQuery     = '/v2/order/query';     // 查单
    private $apicomplaint = '/v2/order/complaint'; // 设诉

    public function __construct($appId, $secretKey)
    {
        $this->appId = $appId;
        $this->secretKey = $secretKey;
        $this->apiGetToken = $this->apiUrl . $this->apiGetToken;

        $this->requestToken();
    }

    public function requestToken() {
        $salt = mt_rand(1000, 9999);
        // 获取签名
        $sig = AnubisApiHelper::generateSign($this->appId, $salt, $this->secretKey);
        $url = $this->apiGetToken;
        $tokenStr = HttpClient::doGet($url, array('app_id' => $this->appId, 'salt' => $salt, 'signature' => $sig));
        $this->token = json_decode($tokenStr, true)['data']['access_token'];
    }

    public function createOrder($dataArray)
    {
        $salt = mt_rand(1000, 9999);
        $dataJson = json_encode($dataArray, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        $urlencodeData = urlencode($dataJson);
        $sig = AnubisApiHelper::generateBusinessSign($this->appId, $this->token, $urlencodeData, $salt);   //生成签名
        $requestJson = json_encode(array(
            'app_id' => $this->appId,
            'salt' => $salt,
            'data' => $urlencodeData,
            'signature' => $sig
        ));
        $url = $this->apiUrl . $this->apiCreate;
        return HttpClient::doPost($url, $requestJson);
    }
}