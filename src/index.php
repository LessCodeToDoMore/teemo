<?php
// +----------------------------------------------------------------------
// | 初始执行文件
// +----------------------------------------------------------------------

use think\{Session, Cookie, Route};

defineDoman();
defineTime();
initSession();

// 域名
function defineDoman()
{
    if (isset($_SERVER['REQUEST_SCHEME'])  &&  isset($_SERVER['SERVER_NAME'])) {
        define('BASE_URL', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']);
    }
    if (isset($_SERVER['SERVER_NAME'])) {
        define('DOMAIN', $_SERVER['SERVER_NAME']);
    }
    if (isset($_REQUEST['s'])) {
        define('URL_AFTER', ltrim($_REQUEST['s'], "//"));
    }
}

// 时间
function defineTime()
{
    $now = date("Y-m-d H:i:s", time());
    define('NOW_DATE_TIME', $now);
    define('NOW_MICROTIME', date("YmdHis", time()) . substr(microtime(), 2, 6));
    define('NOW_DATE', substr($now, 0, 10));
    define('NOW_TIME', substr($now, 11));
    define('NOW_WEEK', array("日","一","二","三","四","五","六")[date("w")]);
}

// 预定义session
function initSession()
{
    /*if (Session::get('addons') === null) {
        Session::set('addons', array());
        // 每个插件的session
        foreach (get_addon_list() as $key=>$value) {
            Session::set('addons.' . $key, array());
        }
    }*/
    if (empty(Session::get(''))) {
        // 每个插件的session
        foreach (get_addon_list() as $key=>$value) {
            Session::set($key, array());
        }
    }
}

// 用户数据
function definedCustomer()
{
    $isLogin  = Session::has('customerData') ? true : false;
    if ($isLogin===false){
        \app\admin\model\Customer::loginByCookie();
        $isLogin  = Session::has('customerData') ? true : false;
    }

    $customer = $isLogin ? Session::get('customer') : null;
    $userData = $isLogin ? Session::get('customerData') : null;
    $GLOBALS['CUSTOMER'] = $customer;
    $GLOBALS['USER']     = $userData;
    define('IS_LOGIN', $isLogin);
}
