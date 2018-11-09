<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 说明:
 * 日期和时间默认格式 Y-m-d H:m:s
 */

// 原生函数
function time_original()
{
	/*
       date(str 格式, str 秒数);       //生成时间基本函数
       date("m");
       time();                         //生成此刻秒数(十位数)
       date("Y-m-d", time());
       date("Y-m-d H:i:s", time());
       date("Y/01 d H:01:s", time());
       microtime()                     //"0.微秒数 时间戳"  0.76416400 1484540804
       strtotime("18 june 2008");      //生成当时秒数
       date("Y-m-d H:i:s", strtotime("+1 year"));
       date("Y-m-d H:i:s", strtotime("2000-01-01 11:30:40 +1 year -2 month +3 week -4 day +5 hour -6 minute +7 second"));
       $t_time = '2016-01-20'; date("Y-m-d H:i:s", strtotime("$t_time -6 minute +7 second"));  //默认time为 2016-01-20 00:00:00
       date("Y-m-d",strtotime("next Thursday"));
       date("Y-m-d",strtotime("last Month"));
       mktime(20, 59, 11, date("m"), date("d")+10, date("Y");  //生成当时秒数
       date("Y-m-d H:i:s" , mktime(20, 59, 11, date("m"), date("d")+10, date("Y")));   //(时,分,秒,月,日,年)
       */
}

// 获取当前时间的微秒数
function get_microtime()
{
	return substr(microtime(), 2, 8);
}

// 获取当前时间的完整字符串,精确到微秒
function get_time_string()
{
	return date("YmdHis", time()).get_microtime();
}

// 取得当前时间信息()
function get_time_info( $strUnit=null )
{
	$arrayPart = explode( '/', date("Y/m/d/H/i/s", time()) );
	list($floatSec, $sec) = explode( ' ', microtime() );

	$arrayTime['year']   = $arrayPart[0];
	$arrayTime['month']  = $arrayPart[1];
	$arrayTime['day']    = $arrayPart[2];
	$arrayTime['hour']   = $arrayPart[3];
	$arrayTime['minute'] = $arrayPart[4];
	$arrayTime['second'] = $arrayPart[5];
	$arrayTime['ms']     = substr($floatSec, 2, 3);
	$arrayTime['us']     = substr($floatSec, 5, 3);

	return $strUnit===null ? $arrayTime : $arrayTime[$strUnit];
}

function get_mini_time( $strUnit=null )
{
	list($floatSec, $sec) = explode( ' ', microtime() );
	return $sec . substr($floatSec, 1, 7);
}

function time_to_path()
{
	$arrayTime = get_time_info();
	return $arrayTime['year'] . '/' . $arrayTime['month'] . '-' . $arrayTime['day'] . '/' . $arrayTime['hour'] . '.' . $arrayTime['minute'] . '/';
}

// 计算时间差,不够则截断
function cal_time_diff($time_1, $time_2)    //str, str
{
	$time_1     = strtotime($time_1);
	$time_2     = strtotime($time_2);
	$time_diff  = ($time_1 > $time_2) ? ($time_1 - $time_2) : ( $time_2 - $time_1 );

	//计算混合字符串
	$days       = intval( $time_diff / 86400 );
	$hours      = intval( $time_diff % 86400 /3600 );
	$minutes    = intval( $time_diff % 86400 % 3600 /60 );
	$seconds    = intval( $time_diff % 86400 % 3600 % 60);
	$arrayMix   = array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$seconds);
	$strMix		= "{$days}days {$hours}hours {$minutes}minutes {$seconds}seconds";

	return array(
		'seconds'	=>$time_diff,
		'minutes'	=>$time_diff/60,
		'hours'		=>$time_diff/3600,
		'days'		=>$time_diff/86400,
		'arrayMix'	=>$arrayMix,
		'strMix'	=>$strMix
	);
}

//将时间段(日期)拆成日期数组 (不含结束值)
function array_date_a_time_to_date_array($v_date_begin, $v_date_end)   //str, str
{
	$time_begin = date("Y-m-d", strtotime($v_date_begin));
	$time_end   = date("Y-m-d", strtotime($v_date_end));
	$r_data_array = array();
	for( $this_date=$time_begin; $this_date<$time_end; $this_date=date("Y-m-d", strtotime("$this_date +1 day")) )
	{
		array_push( $r_data_array, $this_date );
	}
	return $r_data_array;
}

function next_day( $date="0000-01-01", $isUntil31=false )
{
	if( $isUntil31===false )
		return date("Y-m-d", strtotime("$date +1 day"));
	else
	{
		$strResultYear  = $strThatYear  = substr($date, 0, 4);
		$strResultMonth = $strThatMonth = substr($date, 5, 2);
		$strResultDay   = $strThatDay   = substr($date, 8, 2);

		if( $strThatDay!=='31' )
		{
			$strResultDay   = $strThatDay + 1;
			$strResultMonth = $strThatMonth;
			$strResultYear  = $strThatYear;
		}
		else
		{
			$strResultDay = '01';
			if( $strThatMonth!=='12' )
			{
				$strResultMonth = $strThatMonth + 1;
				$strResultYear  = $strThatYear;
			}
			else
			{
				$strResultMonth = '01';
				$strResultYear  = $strThatYear + 1;
			}
		}
	}
	$strResultYear  = str_pad( $strResultYear,  4, '0', STR_PAD_LEFT );
	$strResultMonth = str_pad( $strResultMonth, 2, '0', STR_PAD_LEFT );
	$strResultDay   = str_pad( $strResultDay,   2, '0', STR_PAD_LEFT );

	return $strResultYear . '-' . $strResultMonth . '-' . $strResultDay;
}

// 将时间段(日期)拆成日期数组 (包含结束值, 每個月都有31號)
function array_date_a_time_to_31_day( $dateStart="0000-00-00", $dateEnd="0000-00-00" )
{
	$arrayReturn = array();

	if( $dateStart > $dateEnd )
		return $arrayReturn;

	for( $dateCurrent=$dateStart; $dateCurrent<=$dateEnd; $dateCurrent=next_day($dateCurrent, true) )
		array_push( $arrayReturn, $dateCurrent );

	return $arrayReturn;
}

// 检查一个日期是否是几号
function is_appoint_day( $date, $intDay )
{
	$strThatDay = substr( $date, 8, 2 );
	return $strThatDay==$intDay ? true : false;
}

// 检查一个日期是星期几
function str_witch_week( $date, $isEnglish )
{
	$strNumber = date("w", strtotime($date));
	//return mb_substr( "日一二三四五六", date("w"), 1, "utf-8" );
	switch( $strNumber ){
		case '1' : return $isEnglish===true ? 'Monday'    : '1';
		case '2' : return $isEnglish===true ? 'Tuesday'   : '2';
		case '3' : return $isEnglish===true ? 'Wednesday' : '3';
		case '4' : return $isEnglish===true ? 'Thursday'  : '4';
		case '5' : return $isEnglish===true ? 'Friday'    : '5';
		case '6' : return $isEnglish===true ? 'Saturday'  : '6';
		case '0' : return $isEnglish===true ? 'Sunday'    : '0';
		default  : return false;
	};
}

// 合法化一个日期
function str_legalize_date( $date )
{
	$strResultYear  = $strThatYear  = substr($date, 0, 4);
	$strResultMonth = $strThatMonth = substr($date, 5, 2);
	$strResultDay   = $strThatDay   = substr($date, 8, 2);

	if( $strThatDay>'31' )
		$strResultDay = '31';

	$is_small_month = ($strThatMonth==='02') || ($strThatMonth==='04') || ($strThatMonth==='06') || ($strThatMonth==='09') || ($strThatMonth==='11');

	if( $strThatDay>'30' && $is_small_month )
		$strResultDay = '30';

	if( $strThatDay>'29' && $strThatMonth==='02' )
		$strResultDay = ( ($strThatYear%4===0)&&($strThatYear%100!==0) ) ?  '29' : '28';

	return $strResultYear . '-' . $strResultMonth . '-' . $strResultDay;
}

// 取得一下次的几号
function get_next_appoint_day( $date, $strDay )
{
	$strResultYear  = $strThatYear  = substr($date, 0, 4);
	$strResultMonth = $strThatMonth = substr($date, 5, 2);
	$strResultDay   = $strThatDay   = substr($date, 8, 2);

	if( $strDay < $strThatDay )
	{
		if( $strThatMonth!=='12' )
		{
			$strResultMonth = $strThatMonth + 1;
			$strResultYear  = $strThatYear;
		}
		else
		{
			$strResultMonth = '01';
			$strResultYear  = $strThatYear + 1;
		}
	}

	$strResultYear  = str_pad( $strResultYear,  4, '0', STR_PAD_LEFT );
	$strResultMonth = str_pad( $strResultMonth, 2, '0', STR_PAD_LEFT );
	$strResultDay   = str_pad( $strResultDay,   2, '0', STR_PAD_LEFT );

	return str_legalize_date( $strResultYear . '-' . $strResultMonth . '-' . $strDay );
}

// 第一個指定日(若無此日, 取最後一日)
function get_first_day( $intDay, $dateStart='0000-00-00' )
{
	$intResultYear  = $intStartYear  = substr($dateStart, 0, 4);
	$intResultMonth = $intStartMonth = substr($dateStart, 5, 2);
	$intResultDay   = $intStartDay   = substr($dateStart, 8, 2);

	if( $intDay >= $intStartDay )
	{

	}
	else
	{

	}
}