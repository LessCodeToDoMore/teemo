<?php
include('../tool/AnubisApiHelper.php');
include('../tool/HttpClient.php');
include('../tool/EleOpenConfig.php');

date_default_timezone_set('PRC');

function sendOrder($dataArray, $token) {
  $salt = mt_rand(1000, 9999);
  $dataJson =  json_encode($dataArray, JSON_UNESCAPED_UNICODE) . PHP_EOL;
  // echo 'data json is ' . $dataJson . PHP_EOL;

  // $urlencodeData = urlencode($dataJson);
  $urlencodeData = urlencode($dataJson);

  // echo 'urlencode data is ' . $urlencodeData . PHP_EOL;
  $sig = AnubisApiHelper::generateBusinessSign(APP_ID, $token, $urlencodeData, $salt);   //生成签名
  $requestJson = json_encode(array(
    'app_id' => APP_ID,
    'salt' => $salt,
    'data' => $urlencodeData,
    'signature' => $sig
  ));
  // echo $requestJson . PHP_EOL;
  // $this->url = 'http://127.0.0.1:8080/anubis-webapi/order';
  $url = API_URL . '/v2/order';
  return HttpClient::doPost($url, $requestJson);
}

// http://stackoverflow.com/questions/1708860/php-recursively-unset-array-keys-if-match
// PHP Recursively unset array keys if match
function removeOneField($removeValueKey, $tempDataArray) {
  if (array_key_exists($removeValueKey, $tempDataArray)) {
    unset($tempDataArray[$removeValueKey]);
  }
  foreach ($tempDataArray as $key => $value) {
    if (is_array($value)) {   //如果是数组
      $tempDataArray[$key] = removeOneField($removeValueKey, $value);
    }
  }
  return $tempDataArray;
}

// 定点配送和普通配送都要填的字段
$sameRequireFields = array(
  'require_receive_time',

  'partner_order_code',
  'notify_url',
  'order_type',
  'order_total_amount',
  'order_actual_amount',
  'is_invoiced',
  'order_payment_status',
  'order_payment_method',
  'is_agent_payment',
  'goods_count',

  'receiver_name',
  'receiver_primary_phone',
  'receiver_address',
  'receiver_city_code',
  'receiver_city_name',

  'item_name',
  'item_quantity',
  'item_price',
  'item_actual_price',
  'is_need_package',
  'is_agent_purchase');

// 普通配送需要额外判断的字段
  $instantOrderRequireField = array(
    'transport_latitude',
    'transport_longitude',
    'transport_address',
    'position_source',
    'transport_tel'
  );

// 定点配送需要额外判断的字段
$preOrderRequiredField = array(
  'receiver_latitude',
  'receiver_longitude'
);

//拼装data数据
$dataArray = array(
  'transport_info' => array(
    'transport_id' => '1023',
    'transport_name' => '饿了么Bod上海普陀1站',
    'transport_address' => '上海市普陀区近铁城市广场5楼',
    'transport_longitude' => 121.3718891,
    'transport_latitude' => 31.2306375,
    'position_source' => 1,
    'transport_tel' => '13900000000',
    'transport_remark' => '备注'
  ),
  'receiver_info' => array(
    'receiver_name' => 'jiabuchong',
    'receiver_primary_phone' => '13900000000',
    'receiver_second_phone' => '13911111111',
    'receiver_address' => '太阳',
    'receiver_city_name' => '阿巴顿',
    'receiver_city_code' => '010',
    'receiver_longitude' => 121.3718892,
    'receiver_latitude' => 31.2306375
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
  'partner_order_code' => '12345ijiaobu1',     // 第三方订单号, 需唯一
  'notify_url' => 'http://ele.me:8090',     //第三方回调 url地址
  'order_type' => 2,
  'order_total_amount' => 50.00,
  'order_actual_amount' => 48.00,
  'is_invoiced' => 1,
  'invoice' => '饿了么',
  'order_payment_status' => 1,
  'order_payment_method' => 1,
  'require_payment_pay' => 50.00,
  'goods_count' => 4,
  'is_agent_payment' => 1,
  'require_receive_time' => strtotime('+1 day') * 1000  //注意这是毫秒数
);

$emptyField = array();
if ($dataArray['order_type'] == 1) {    //判断订单类型
  $emptyField = array_merge($sameRequireFields, $instantOrderRequireField);
} else if ($dataArray['order_type'] == 2) {
  $emptyField = array_merge($sameRequireFields, $preOrderRequiredField);
}

$token = 'bb631a72-49f9-4acf-9e55-a9760fa33e6d';

// 遍历空字段数组
$count = 0;
foreach($emptyField as $value) {
  //
  $count++;
  $tempDataArray = $dataArray;
  $tempDataArray = removeOneField($value, $tempDataArray);
  $str = '第 ' . $count . '请求, ' . $value . '字段为空:  ';
  $res = sendOrder($tempDataArray, $token);  // 创建订单
  $str .= $res;
  echo $str . PHP_EOL;
}
