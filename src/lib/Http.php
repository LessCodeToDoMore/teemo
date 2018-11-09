<?php
namespace Lib;

class Http{
    public $method = 'POST';
    public $params = [];
    public $query = ""; // 针对GET参数
    public $headers = array('Content-type: application/json');
    public $connect_timeout = 5; // 连接成功时间
    public $timeout = 20; // 接收完成时间

    public function request($url)
    {
        $curl = curl_init();

        $method = strtoupper($this->method);
        switch ($method)
        {
            case 'GET':
                curl_setopt($curl, CURLOPT_HTTPGET, 1);
                $query = http_build_query($params);
                $url = $query ? $url . (stripos($url, "?") !== FALSE ? "&" : "?") . $query : $url;
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case "PATCH":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }
        curl_setopt($curl, CURLOPT_URL, $url);

        if ($method !== 'GET') {
            $params = json_encode($this->params);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params);
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.98 Safari/537.36');
        if ('https' === substr($url, 0, 5))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        }

        $ret = curl_exec($curl);
        $err = curl_error($curl);

        // 请求失败
        if (false === $ret || !empty(($err))) {
            $errno = curl_errno($curl);
            $info = curl_getinfo($curl);
            curl_close($curl);
            return json_decode(json_encode([
                'result' => false,
                'errno'  => $errno,
                'info'   => $info,
                'msg'    => $err,
                'return' => json_decode($ret),
            ]));
        }

        // 请求成功
        curl_close($curl);
        $return = json_decode($ret);

        // 只接受json格式，否则认为报错
        if ($return === null) {
            return json_decode(json_encode([
                'result' => false,
                'errno' => 0,
                'msg' => $ret
            ]));
        }

        return json_decode(json_encode([
            'result' => true,
            'errno' => 0,
            'json' => json_decode($ret)
        ]));
    }
}