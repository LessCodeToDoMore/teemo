<?php
namespace Lib;

class Picture
{
    // 依赖phpqrcode
    public static function url_to_qrcode($url, $pathImg, $size=4)
    {
        vendor('phpqrcode.phpqrcode');
        \QRcode::png($url, $pathImg, $level="L", $size, $margin=1);
    }

    // 批量生成二维码
    public static function urls_to_qrcodes($karrayUrlWithName, $pathSave='imgs/', $ext='.png', $size=4)
    {
        $karrayImgUrl = [];
        $baseUrl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/';
        foreach($karrayUrlWithName as $picName=>$url)
        {
            $pathImg = $pathSave.$picName.$ext;
            self::url_to_qrcode($url, $pathImg, $size);
            $karrayImgUrl[$picName] = $baseUrl.$pathImg;
        }
        return $karrayImgUrl;
    }

}