<?php
namespace Lib;

class Time
{
	public static function myLib()
	{
		return 'this is my library';
	}

	public static function cal_time_diff($time_1, $time_2)
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
}
