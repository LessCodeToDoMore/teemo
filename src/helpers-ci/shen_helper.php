<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------ CI接口 ------------------------------------
function ci()
{
	return get_instance();
}


//--------------------------------------------------------------------------
//                               CI测试类
//--------------------------------------------------------------------------
function mark_time($strMark)
{
	ci()->benchmark->mark($strMark);
	$GLOBALS['time'][$strMark] = get_mini_time();
}

function count_time($strBegin=null, $strFinish=null)
{
	if( ($strBegin===null)||($strFinish===null) )
		echo ( ci()->benchmark->elapsed_time() * 1000 ) . ' ms';
	else
		echo ( ci()->benchmark->elapsed_time($strBegin, $strFinish) * 1000 ) . ' ms';
		//echo ( bcsub( $GLOBALS['time'][$strFinish], $GLOBALS['time'][$strBegin], 6 ) * 1000 ) . ' ms';
	/*{
		list($strSecondBegin, $strUsBegin)   = explode('.', $GLOBALS['time'][$strBegin]);
		list($strSecondFinish, $strUsFinish) = explode('.', $GLOBALS['time'][$strFinish]);

		$intSecondDiff = $strSecondFinish - $strSecondBegin;

		$a = $intSecondDiff.$strUsFinish;
		$b = $strUsBegin;

		echo ($intSecondDiff.$strUsFinish) - $strUsBegin;
	}*/
}

function count_memory()
{
	// 只能用于视图文件
	echo $this->benchmark->memory_usage();
}


//--------------------------------------------------------------------------
//                               device
//--------------------------------------------------------------------------
function is_mobile( $isIncludePad = false )
{
	// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	if( isset($_SERVER['HTTP_X_WAP_PROFILE']) )
		return true;

	// 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	if( isset($_SERVER['HTTP_VIA']) )
		return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;

	// 脑残法，判断手机发送的客户端标志,兼容性有待提高
	if ( isset($_SERVER['HTTP_USER_AGENT']) )
	{
		//排除平板
		if( $isIncludePad === false )
		{
			$padKeywords = array ('ipad');      //平板标识
			if( preg_match("/(" . implode('|', $padKeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])) )
				return false;
		}

		$clientkeywords = array (               //移动和手机标识
			'mobile',    'android',    'iphone',    'ipod',      'samsung',  'htc',
			'nokia',     'sony',       'ericsson',  'mot',       'sgh',      'lg',
			'sharp',     'sie-',       'philips',   'panasonic', 'alcatel',  'lenovo',
			'oppo',      'blackberry', 'meizu',     'netfront',  'symbian',  'ucweb',
			'windowsce', 'palm',       'operamini', 'operamobi', 'openwave', 'nexusone',
			'cldc',      'midp',       'wap'
		);
		if( preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])) )
			return true;
	}

	// 协议法，因为有可能不准确，放到最后判断
	if( isset($_SERVER['HTTP_ACCEPT']) )
	{
		// 如果只支持wml并且不支持html那一定是移动设备, 如果支持wml和html但是wml在html之前则是移动设备
		if(
			(strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) &&
			(strpos($_SERVER['HTTP_ACCEPT'], 'text/html')   === false ||
				(strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))
		)
			return true;
	}
	return false;
}

function str_browser()
{
	if(empty($_SERVER['HTTP_USER_AGENT'])){
		return 'unknown';
	}
	if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 11.0')){
		return 'IE-11';
	}
	if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 10.0')){
		return 'IE-10';
	}
	if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 9.0')){
		return 'IE-9';
	}
	if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 8.0')){
		return 'IE-8';
	}
	if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7.0')){
		return 'IE-7';
	}
	if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0')){
		return 'IE-6';
	}
	if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Firefox')){
		return 'Firefox';
	}
	if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Chrome')){
		return 'Chrome';
	}
	if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Safari')){
		return 'Safari';
	}
	if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'Opera')){
		return 'Opera';
	}
	if(false!==strpos($_SERVER['HTTP_USER_AGENT'],'360SE')){
		return '360SE';
	}
}

function echo_img_by_device( $strPcUrl, $strPhUrl )
{
	echo $_SESSION['isMobile'] === true ? $strPhUrl : $strPcUrl;
}

function echo_dom_by_device( $strPcUrl, $strPhUrl, $dataPc=array(), $dataPh=array() )
{
	$_SESSION['isMobile'] === true ? ci()->load->view($strPhUrl, $dataPh) : ci()->load->view($strPcUrl, $dataPc);
}


//--------------------------------------------------------------------------
//                               路由
//--------------------------------------------------------------------------
function get_visit_url()
{
	if( isset($_SERVER['PATH_INFO']) )
		return ltrim($_SERVER['PATH_INFO'], '/');
	else
		return '';
}


//--------------------------------------------------------------------------
//                              登陆事项
//--------------------------------------------------------------------------
function check_permission( $intUserRank=null, $isPage=false )
{
	// 等级制. null:不需要权限; 0:最高权限; 1:次之

	if($intUserRank===null) return true;

	// 已设置等级
	if( isset($_SESSION['intUserRank']) )
	{
		if( $_SESSION['intUserRank']<=$intUserRank )	// 够权
			return true;
		else											// 不够权
			header("http/1.1 403 Forbidden");
	}
	// 未设置等级
	else
	{
		if( $isPage===true )
		{
			$_SESSION['strTempUrl'] = $_SERVER['REDIRECT_URL'] ;
			$strLoginUrl = base_url().URL_LOGIN ;
			header("Location:$strLoginUrl");
		}
		else
		{
			unset($_SESSION['strTempUrl']) ;
			header("http/1.1 403 Forbidden");
		}
	}
}














