<?php
defined('BASEPATH') OR exit('No direct script access allowed');


//原生函数
function network_original()
{
    /*
    E-mail:
    mail(to,subject,message,headers,parameters)     //从脚本中发送电子邮件
    --------------------------------------------------------------
    $to = "someone@example.com";                            接收者。
    $subject = "Test mail";                                 主题
    $message = "Hello! This is a simple email message.";    要发送的消息。LF (\n) 分隔各行。
    $from = "someonelse@example.com";
    $headers = "From: $from";                               [附加的标题]
    mail($to,$subject,$message,$headers);
    ---------------------------------------------------------------
    */
}

// 发送邮件 (部分邮箱需要设置开启smtp; 常用:smtp.163.com/smtp.vip.163.com/smtp.qq.com/smtp.126.com/ pop.163.com)
function send_an_email($arraySet=array())
{
    $arrayDefault = array(
        'isSmtp'          => IS_SMTP,

        'strFromHost'     => STR_FROM_HOST,
        'port'            => NUM_HOST_PORT,
        'SMTPSecure'      => STR_SMTPSECURE,
        'strFromMail'     => STR_FROM_MAIL,
        'strFrompassword' => STR_FROM_PASSWORD,
        'strFromName'     => STR_FROM_MAIL,

        'strToMail'       => STR_TO_MAIL,
        'strToName'       => STR_TO_MAIL,

        'strReplyMail'    => STR_FROM_MAIL,

        'strSubject'      => 'SMTP方式发送邮件测试',
        'textContent'     => '开始发送时间: '.date("Y-m-d H:i:s", time()),
        'strAttachment'   => '',    //也可以是字串数组(自动识别)

        'cc'              => '',
        'ccName'          => '',
        'dd'              => '',
        'ddName'          => ''
    );

    $arraySet = array_replace($arrayDefault, $arraySet);

    require( APPPATH . 'third_party/PhpMailer/class.phpmailer.php' );
    require( APPPATH . 'third_party/PhpMailer/class.smtp.php' );

    $mail = new PHPMailer();
    $mail->CharSet = "UTF-8";

    //调用配置并发送
    try
    {
        //基本配置
        if( $arraySet['isSmtp']===true )
        {
            $mail->IsSMTP();
            $mail->SMTPAuth   = true;
            $mail->Host       = $arraySet['strFromHost'];
            $mail->Port       = $arraySet['port'];
            $mail->Username   = $arraySet['strFromMail'];
            $mail->Password   = $arraySet['strFrompassword'];

            if( $arraySet['SMTPSecure']!='' )
                $mail->SMTPSecure = $arraySet['SMTPSecure'];
        }

        //接收者
        if( $arraySet['strToMail']!='' )
            $mail->AddAddress($arraySet['strToMail'], $arraySet['strToName']);

        $mail->SetFrom($arraySet['strFromMail'], $arraySet['strFromName']);

        //抄送
        if( $arraySet['cc']!='' )
            $mail->AddCC($arraySet['cc'], $arraySet['ccName']);

        if( $arraySet['dd']!='' )
            $mail->AddBCC($arraySet['dd'], $arraySet['ddName']);

        //标题及内容
        $mail->Subject = $arraySet['strSubject'];
        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
        $mail->MsgHTML( stripslashes($arraySet['textContent']) );

        //附件
        if( $arraySet['strAttachment']!='' )
        {
            if( is_string($arraySet['strAttachment']) )
                $mail->AddstrAttachment( $arraySet['strAttachment'] );
            else if( is_array($arraySet['strAttachment']) )
            {
                foreach( $arraySet['strAttachment'] as $each )
                    $mail->AddAttachment( $each );
            }
        }

        //发送
        if( $mail->Send() )
            return true;
        else
            return false;
    }
    catch ( Exception $e ) {
        return $mail->ErrorInfo;
    }
}

// 通过url获取html
function get_html_by_url($strUrl='https://www.baidu.com/', $intTimeoutSecond = 5) {

    //测试网址
    //$url = 'https://www.google.com.hk/';
    //$url = "https://hk.finance.yahoo.com/q?s=2727&ql=1";

    set_time_limit(5);
    //int_set('max_execution_time', '45');

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $strUrl);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // 自动识别301跳转
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    // 设置各种超时限制
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $intTimeoutSecond);
    curl_setopt($ch, CURLOPT_TIMEOUT, $intTimeoutSecond);
    //curl_setopt($ch, CURLOPT_NOSIGNAL, true);    //注意，毫秒超时一定要设置这个
    //curl_setopt($ch, CURLOPT_TIMEOUT_MS, 200);  //超时毫秒

    $html = curl_exec($ch);
    //var_dump( curl_getinfo($ch) );

    // 处理各种错误
    if (false === $html) {
        return false;
    }

    // 处理http错误
    if (200 != curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
        return false;
    }

    curl_close($ch);
    //echo $html;
    return $html;
}

// 允许跨域向本站发出请求
function allow_all_origin()
{
    // 星号表示所有的域都可以接受
    header("Access-Control-Allow-Origin:*");
    header("Access-Control-Allow-Methods:GET,POST");
}