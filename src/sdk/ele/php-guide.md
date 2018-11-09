###PHP请求接口示例(PHP 版本: 5.6.19)
1. 在`tool/EleOpenConfig.php`配置正确的API_URL、APP\_ID、SECRET\_KEY;
2. `CreateOrder.php`（创建订单）, 使用PHP命令执行这个脚本：
	`php CreateOrder.php `

 - requestToken(), 获取token
 - sendOrder(), 创建订单

3.  `QueryOrder.php`（查询订单）, 填入正确的token, 使用PHP命令执行这个脚本：
	`php QueryOrder.php`
4.  `CancelOrder.php`（取消订单）, 填入正确的token, 使用PHP命令执行这个脚本：
	`php CancelOrder.php`
5. `OrderComplaint.php` (投诉接口), 填入正确的token, 使用PHP命令执行这个脚本:
  `php OrderComplaint.php`
其中有签名和获取token的示例，good luck.....
