<?php
include('../tool/AnubisApiHelper.php');
include('../tool/HttpClient.php');
include('../tool/EleOpenConfig.php');

function requestToken() {
    $salt = mt_rand(1000, 9999);
    // 获取签名
    $sig = AnubisApiHelper::generateSign(APP_ID, $salt, SECRET_KEY);
    $url = API_URL . '/get_access_token';
    $tokenStr = HttpClient::doGet($url, array('app_id' => APP_ID, 'salt' => $salt, 'signature' => $sig));
    // echo $tokenStr;
    // 获取token
    echo json_decode($tokenStr, true)['data']['access_token'] . PHP_EOL;
  }

  requestToken();
