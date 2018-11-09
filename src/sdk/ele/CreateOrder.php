<?php
include('./tool/AnubisApiHelper.php');
include('./tool/HttpClient.php');
include('./tool/EleOpenConfig.php');
date_default_timezone_set('PRC');

class RequestOrderProcess {
  private $token;

// step 1
  public function requestToken() {
    $salt = mt_rand(1000, 9999);
    // 获取签名
    $sig = AnubisApiHelper::generateSign(APP_ID, $salt, SECRET_KEY);
    $url = API_URL . '/get_access_token';
    $tokenStr = HttpClient::doGet($url, array('app_id' => APP_ID, 'salt' => $salt, 'signature' => $sig));
    // echo $tokenStr;
    // 获取token
    $this->token = json_decode($tokenStr, true)['data']['access_token'];
  }

  // step 2 创建订单
  public function sendOrder($dataArray) {
    $salt = mt_rand(1000, 9999);
    $dataJson =  json_encode($dataArray, JSON_UNESCAPED_UNICODE) . PHP_EOL;
    echo 'data json is ' . $dataJson . PHP_EOL;

    // $urlencodeData = urlencode($dataJson);
    $urlencodeData = urlencode($dataJson);

    echo 'urlencode data is ' . $urlencodeData . PHP_EOL;
    $sig = AnubisApiHelper::generateBusinessSign(APP_ID, $this->token, $urlencodeData, $salt);   //生成签名
    $requestJson = json_encode(array(
      'app_id' => APP_ID,
      'salt' => $salt,
      'data' => $urlencodeData,
      'signature' => $sig
    ));
    echo $requestJson . PHP_EOL;
    // $this->url = 'http://127.0.0.1:8080/anubis-webapi/order';
    $url = API_URL . '/v2/order';
    return HttpClient::doPost($url, $requestJson);
  }

  public function getToken() {
    return $this->token;
  }
}

$rop = new RequestOrderProcess();

$rop->requestToken();    // 请求token

//拼装data数据
$dataArray = array(
  'transport_info' => array(
    'transport_name' => '饿了么Bod上海普陀1站',
    'transport_address' => '上海市普陀区近铁城市广场5楼',
    'transport_longitude' => 121.5156496362,
    'transport_latitude' => 31.2331643501,
    'position_source' => 1,
    'transport_tel' => '13900000000',
    'transport_remark' => '备注'
  ),
  'receiver_info' => array(
    'receiver_name' => 'jiabuchong',
    'receiver_primary_phone' => '13900000000',
    'receiver_second_phone' => '13911111111',
    'receiver_address' => '太阳',
    'receiver_longitude' => 121.5156496362,
    'position_source' => 3,
    'receiver_latitude' => 31.2331643501
  ),
  'items_json' => array(
    array(
      'item_name' => '苹果',
      'item_quantity'=> 5,
      'item_price' => 9.50,
      'item_actual_price' => 10.00,
      'is_need_package' => 1,
      'is_agent_purchase' => 1
    ),
    array(
      'item_name' => '香蕉',
      'item_quantity'=> 20,
      'item_price' => 100.00,
      'item_actual_price' => 300.59,
      'is_need_package' => 1,
      'is_agent_purchase' => 1
    )
  ),
  'partner_remark' => '天下萨拉',
  'partner_order_code' => '1234567890xx124',     // 第三方订单号, 需唯一
  'notify_url' => 'http://vpcb-anubis-web-base-2.vm.elenet.me:5000',     //第三方回调 url地址
  'order_type' => 2,
  'order_total_amount' => 50.00,
  'order_actual_amount' => 48.00,
  'order_weight'=> 12.0,
  'is_invoiced' => 1,
  'invoice' => '饿了么',
  'order_payment_status' => 1,
  'order_payment_method' => 1,
  'require_payment_pay' => 50.00,
  'goods_count' => 4,
  'is_agent_payment' => 1,
  'require_receive_time' => strtotime('+1 day') * 1000  //注意这是毫秒数
);

echo 'The token is ' . $rop->getToken() . PHP_EOL;   // first 获取token
echo $rop->sendOrder($dataArray);  // second 创建订单
