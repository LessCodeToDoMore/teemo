<?php
function capitalize($element) {
  $element = strtolower($element);
  return ucwords($element);
}
$capitals = array(
  'Alabama' => 'montGoMEry',
  'Alaska'  => 'Juneau',
  'Arizona' => 'phoeniX'
);
$capitals = array_map("capitalize", $capitals);

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
  'order_type' => 1,
  'order_total_amount' => 50.00,
  'order_actual_amount' => 48.00,
  'is_invoiced' => 1,
  'invoice' => '饿了么',
  'order_payment_status' => 1,
  'order_payment_method' => 1,
  'require_payment_pay' => 50.00,
  'goods_count' => 4,
  'is_agent_payment' => 1,
  'require_receive_time' => 100* 1000  //注意这是毫秒数
);

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

function removeFieldUseMap($key, $dataArray) {
  return array_map(function ($arr) use ($key) {
      if (is_array($arr) && array_key_exists($key, $arr)) {
        unset($arr[$key]);
      }
      return $arr;
  }, $dataArray);
}

function removeFieldUseArrayWalk($value, $key) {
  echo "$value, $key\n";
}

// $tempDataArray = removeOneField('position_source', $dataArray);
// $tempDataArray = removeFieldUseMap("item_name", $dataArray);

//
array_walk_recursive($dataArray, 'removeFieldUseArrayWalk');
// print_r($tempDataArray);
