<?php
include('./tool/AnubisApiHelper.php');
include('./tool/HttpClient.php');
include('./tool/EleOpenConfig.php');

$appId = APP_ID;

$url = API_URL . "/v2/order/complaint";
$token = "77570058-85de-48d6-a6f7-11895ef0fa03";

$partner_order_code = "99181467018702387";  //推单时 第三方订单号
$partner_complaint_code = 150;
$order_complaint_time = time() * 1000;

$data = array("partner_order_code" => $partner_order_code,
"partner_complaint_code" => $$partner_complaint_code,
"order_complaint_time" => $$order_complaint_time);
$dataJson = json_encode($data, JSON_UNESCAPED_UNICODE);
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

echo HttpClient::doPost($url, $requestJson) . PHP_EOL;   //发送请求
