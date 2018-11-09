<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function file_original()
{
	/*
    //解压
    $obj_zip = new ZipArchive;      //新建一个ZipArchive的对象
    if( $zip->open('test_file/poshytip-1.2.zip') === TRUE ) {   //要处理的对象
        $zip->extractTo('test_file/poshytip');  //解压路径
        $zip->close();              //关闭句柄
    */
}

// 获取文件名
function get_file_name($pathFile, $isIncludeExt=true)
{
	$arraySubName = explode('/', $pathFile);
	$strWholeName = end( $arraySubName );
	if( $isIncludeExt===true )
		return $strWholeName;
	else
	{
		$strExt = get_file_ext($strWholeName);
		return rtrim($strWholeName, ".{$strExt}");
	}
}

// 获取文件目录
function get_file_dir( $strFilePath )
{
	$arraySegment = explode('/', $strFilePath);
	if( count($arraySegment)===1 )
		return '';
	else
	{
		array_pop($arraySegment);
		return implode('/', $arraySegment).'/';
	}
}

// 取文件名后缀
function getExt($filename)
{
    return strrchr($filename,'.');
}

function get_file_ext($strFileName)
{
	$arraySubName = explode('.', $strFileName);
	return count($arraySubName) === 1 ? '' : end($arraySubName);
}

// 以时间命名文件
function named_file_by_time($strFileName)
{
	$strName = get_file_name($strFileName, false);
	$strExt  = get_file_ext($strFileName);
	$strTime = get_time_string();
	return "{$strName}-{$strTime}.{$strExt}";
}

// 上传文件 时间命名
function upload_named_time( $strFileKey=STR_FILE_KEY, $strSavePath=PATH_SAVE, $numMaxSizeKb=NUM_MAX_SIZE, $arrayExt=array() )
{
	//后缀
	$strFileExt = get_file_ext($_FILES[$strFileKey]['name']);

	//检查后缀
	if( count($arrayExt) !== 0 )
		if( in_array($strFileExt, $arrayExt, false) === false )
			return false;

	//检查大小
	if( $_FILES[$strFileKey]['size'] / 1024  > $numMaxSizeKb )
		return false;

	//保存文件
	$strFilePath = $strSavePath . get_time_string() . '.' . $strFileExt;
	if( copy($_FILES[$strFileKey]['tmp_name'], $strFilePath) === true )
		return $strFilePath;
	else
		return false;
}

// 上传文件 原名命名（依赖时间命名函数）
function upload_named_same( $strFileKey=TR_FILE_KEY, $strSavePath=PATH_SAVE, $numMaxSizeKb=NUM_MAX_SIZE, $arrayExt=array() )
{
	$result = upload_named_time($strFileKey, $strSavePath, $numMaxSizeKb, $arrayExt);

	// 上传失败
	if( $result === false )
		return false;

	$strFileWillName = $_FILES[$strFileKey]['name'];
	$strFileWillPath = $strSavePath.$strFileWillName;

	// 已存在同名
	if( file_exists($strFileWillPath) === false )
		return rename($result, $strFileWillPath) === true ? $strFileWillPath : false;

	$strFileExt   = get_file_ext($strFileWillName);
	$strMicroTime = get_time_string();

	$strFileWillNameFront = rtrim($strFileWillName, ".$strFileExt");
	$strFileRealName      = "{$strFileWillNameFront}_{$strMicroTime}.{$strFileExt}";
	// $strFileRealName   = $strFileWillNameFront . "_$strMicroTime" . ".$strFileExt";
	$strFileRealPath      = $strSavePath.$strFileRealName;

	return rename($result, $strFileRealPath) === true ? $strFileRealPath : false;
}

// 删除文件
function delete_file( $strPath )
{
	is_file($strPath) OR exit('true');
	return unlink($strPath);
}

// 压缩文件夹
function zip_folder( $pathTarget=PATH_ZIP_TARGET, $strZipFileName=STR_ZIP_FILE_NAME, $pathSaveTo=PATH_ZIP_SAVE, $isReserve=true, $isDownLoad=false )
{
	$strZipFileName .= '_'.get_time_string().'.zip';	  //压缩后zip文件名

	ci()->load->library('zip');
	ci()->zip->compression_level = INT_COMPRESSION_LEVEL;	  //压缩等级
	ci()->zip->read_dir($pathTarget);					      //遍历压缩（bug:空子夹没含进去）

	$isReserve  AND ci()->zip->archive($pathSaveTo.$strZipFileName);	//存至服务器
	$isDownLoad AND ci()->zip->download($strZipFileName);	  			//下载至浏览器,此函数会exit
}

// 压缩图片
function thumb_img( $strPath, $numSizeBegin=NUM_SIZE_BEGIN_REDUCE, $numSizeTo=NUM_SIZE_TO_BE)
{
	if( file_exists($strPath) === false )
		return '';

	$numSizeKb = filesize( $strPath ) / 1024;
	if( $numSizeKb < $numSizeBegin )
		return '';

	require( APPPATH . 'third_party/phpthumb/ThumbLib.inc.php' );

	$numProportion  = $numSizeTo / $numSizeKb;
	$strThumbName   = microtime();
	$strSavePath	= get_file_dir($strPath).$strThumbName.'.'.get_file_ext($strPath);

	$thumb = PhpThumbFactory::create( $strPath );
	$thumb->resizePercent( $numProportion*100 );
	$thumb->save($strSavePath);
	return $strSavePath;
}


// 保存或更新 DOM 为 HTML 文件
function save_or_updata_dom_as_file( $v_dom='', $v_str_save_path='upload/html/', $v_str_save_file_name=NULL )
{
	$str_file_name          = ( $v_str_save_file_name === NULL ? date("Y-m-d-H-i-s", time()).'html' : $v_str_save_file_name );
	$str_file_destination   = $v_str_save_path.$str_file_name;

	//备份原同名文件
	if( file_exists($str_file_destination) === TRUE )
		rename( $str_file_destination, $str_file_destination.'_back_'.date("Y-m-d-H-i-s", time()) );

	//写入文件流
	$stream_fow = fopen( $str_file_destination, 'w' );
	fwrite( $stream_fow, $v_dom );
	fclose( $stream_fow );

	//检查
	return file_exists($str_file_destination) === true ? true : false ;
}

// 设置原xlsx文件并导出下载
function export_xlsx( $pathResExcel, $arraySet=array() )
{
	require( APPPATH . 'third_party/phpexcel/PHPExcel.php' );
	require( APPPATH . 'third_party/phpexcel/PHPExcel/IOFactory.php' );

	// 读文件
	$objReader = PHPExcel_IOFactory::createReader('Excel2007');
	$objPHPExcel = $objReader->load($pathResExcel);

	// 设置数据
	$objSetData = $objPHPExcel->setActiveSheetIndex(0);
	foreach ($arraySet as $strPosition => $value)
		$objSetData->setCellValue($strPosition, $value);

	// 生成
	$strFileName = named_file_by_time($pathResExcel);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="' . $strFileName . '"');
	header('Cache-Control: max-age=0');

	// 下载
	$objWriter = PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
}

// 下载文件
function download_file( $pathFile )
{
	$this->load->helper('download');
	force_download($pathFile, null);
}

// 遍历目录
function get_dir($pathDir, $isIncludePath=false)
{
	ci()->load->helper('directory');
	$arrayFile = directory_map($pathDir, 0, true);

	if( $isIncludePath===true )
	{
		foreach( $arrayFile as $key=>$strFileName )
			$arrayFile[$key] = $pathDir.'/'.$strFileName;
	}

	return $arrayFile;
}