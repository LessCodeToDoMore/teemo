<?php
namespace Lib;

class Network
{
    public static function curl_post($url, $postData=[], $isOver=true)
    {
        if (strpos($url, 'http')!==0){
            $url = BASE_URL.$url;
        }

        $strPost = "";
        foreach ($postData as $key=>$value)
        {
            $strPost .= "$key=".urlencode($value)."&";
        }
        $strPost=substr($strPost,0,-1);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);    // POST
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_POSTFIELDS, $strPost);
        curl_setopt($curl, CURLOPT_NOSIGNAL, 1);

        if ($isOver===true){
            curl_setopt($curl, CURLOPT_TIMEOUT_MS, 100);
            curl_exec($curl);
            curl_close($curl);
        }
        else{
            curl_setopt($curl, CURLOPT_TIMEOUT_MS, 5000);
            $objReturn = json_decode(curl_exec($curl));
            curl_close($curl);
            return $objReturn;
        }
    }
}