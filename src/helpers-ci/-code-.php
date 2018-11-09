<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function need_admin( $isPage=false )
{
    $_SESSION['strAdminIp'] = $_SERVER['HTTP_HOST'];

    if ( ! (isset($_SESSION['isAdmin'])&&($_SESSION['isAdmin']===true)) )
    {
        $_SESSION['strTempUrl'] = $_SERVER['REDIRECT_URL'] ;
        $control_url = base_url().URL_ADMIN_LOGIN ;

        if( $isPage===true )
            header("Location:$control_url");
        else
            header("http/1.1 403 Forbidden");

        exit;
    }
}