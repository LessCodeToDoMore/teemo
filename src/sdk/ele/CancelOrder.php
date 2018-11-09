<?php
include('./tool/AnubisApiHelper.php');
include('./tool/HttpClient.php');
include('./tool/EleOpenConfig.php');

$appId = APP_ID;
$url = API_URL . "/v2/order/cancel";
$partner_order_code = "1234567890xx124";  //推单时 第三方订单号
$token = "53e2dcc7-ca8b-4526-b09e-a3628cca16df";

$data = array(
        "partner_order_code" => $partner_order_code,
        "order_cancel_reason_code" => 2,
        "order_cancel_description" => "货品不新鲜",
        "order_cancel_time" => time() * 1000
    );
$dataJson = json_encode($data);
echo "data json is " . $dataJson . PHP_EOL;

$salt = mt_rand(1000, 9999);
$urlencodeData = urlencode($dataJson);
$sig = AnubisApiHelper::generateBusinessSign($appId, $token, $urlencodeData, $salt);   //生成签名

$requestJson = json_encode(array(
  'app_id' => APP_ID,
  'salt' => $salt,
  'data' => $urlencodeData,
  'signature' => $sig
));
echo $requestJson . PHP_EOL;

echo HttpClient::doPost($url, $requestJson);
