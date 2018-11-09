<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/14
 * Time: 17:32
 */

/**
100-199 用于指定客户端应相应的某些动作。
200-299 用于表示请求成功。
300-399 用于已经移动的文件并且常被包含在定位头信息中指定新的地址信息。
400-499 用于指出客户端的错误。
500-599 用于支持服务器错误。
 */

return[
    200 => '成功',            // OK/正常
    400 => '失败',            // Bad Request/错误请求

    301 => '重定向',           //Moved Permanently

    401 => '未授权',           //Unauthorized
    402 => '登陆状态已过期',
    403 => '禁止',             //Forbidden
    404 => '请求目标不存在',   //Not Found

    411 => '参数错误',         //Length Required
    412 => '参数缺失',         //Precondition Failed

    500 => '后端处理错误',     //Internal Server Error, 内部服务器错误
];