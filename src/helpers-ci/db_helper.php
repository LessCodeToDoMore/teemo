<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/****************************** 简化辅助 **********************************/
function query($sql)
{
	return ci()->db->query($sql);
}

function array_tb_feild($strTbName, $strDbName=null)
{
	if( (IS_CI===true) && ($strDbName===null) )
		return ci()->db->list_fields($strTbName);

	$sql = '';
	if( $strDbName === null )
		$sql = " SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE table_name = '{$strTbName}' ";
	else
		$sql = " SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE table_name = '{$strTbName}' and table_schema = '{$strDbName}' ";

	return array_column(select($sql), 'COLUMN_NAME');
}


/****************************** 构造SQL ***********************************/
// 查
function str_form_select($arrayKey=array(), $isExcept=false, $strTbName=null) // '排除'式才需要表名
{
	if( count($arrayKey) === 0 )		//全部列
		return '*';

	if( $isExcept === false )			//选择列
	{
		$strKeys = '';
		foreach($arrayKey as $strKey)
			$strKeys .= "$strKey,";
		$strKeys = rtrim($strKeys, ',');
		return "SELECT $strKeys";
	}
	else								//排除列
	{
		$arrayColName = array_tb_feild($strTbName);
		$arraySelectColName = array_diff($arrayColName, $arrayKey);
		return str_form_select($arraySelectColName);
	}
}

// 改
function str_form_set($arraySet=array()) //数组中无设置 : 返null
{
	if( count($arraySet) === 0 )
		return null;

	$sql_set = 'SET ';
	foreach( $arraySet as $key=>$value )
		$sql_set = $sql_set."$key='{$value}',";
	return rtrim($sql_set, ',');
}

// 增
function str_form_vlues($arrayKeyValue=array())
{
	if( count($arrayKeyValue) === 0 )
		return 'VALUES()';

	$strKeys   = '';
	$strValues = '';
	foreach( $arrayKeyValue as $key=>$value )
	{
		$strKeys   .= "$key,";
		$strValues .= "'{$value}',";
	}
	return '(' . rtrim($strKeys, ',') . ') VALUES (' . rtrim($strValues, ',') . ')';
}

// 限定
function str_form_where($arrayRestrict=array(), $strOperator='AND')
{
	if( count($arrayRestrict) === 0 )	//无限制
		return '';

	$sql_where = 'WHERE ';
	foreach( $arrayRestrict as $key=>$value )
		$sql_where .= "$key='{$value}' $strOperator ";

	return $strOperator == 'AND' ? rtrim($sql_where, 'AND ') : rtrim($sql_where, 'OR ');
}

// 排序
function str_form_order($arrayKey=array(), $strOrder=null)	 // $strOrder:null->$arrayKey是键值对形式, $strOrder:'ASC','DESC'->$arrayKey是数字索引数组
{
	if( count($arrayKey) === 0 )	//无排序
		return '';

	$sql_order = 'ORDER BY ';

	if( $strOrder === null )
		foreach( $arrayKey as $key=>$strKeyOrder )
			$sql_order .= "$key $strKeyOrder,";
	else
		foreach( $arrayKey as $strKey )
			$sql_order .= "$strKey $strOrder,";

	return rtrim($sql_order, ',');
}

// 构造where数组(最多三个限定)
function array_form_where($strField_1=null, $value_1=null, $strField_2=null, $value_2=null, $strField_3=null, $value_3=null)
{
	$arrayWhere = array();

	if( !( ($strField_1===null) || ($strField_1==='null') ) )	//第一限定
	{
		if( ($value_1===null) || ($value_1==='null') )
			$value_1 = $_REQUEST['value_1'];
		$arrayWhere[$strField_1] = $value_1;
	}

	if( !( ($strField_2===null) || ($strField_2==='null') ) )	//第二限定
	{
		if( ($value_2===null) || ($value_2==='null') )
			$value_2 = $_REQUEST['value_2'];
		$arrayWhere[$strField_2] = $value_2;
	}

	if( !( ($strField_3===null) || ($strField_3==='null') ) )	//第三限定
	{
		if( ($value_3===null) || ($value_3==='null') )
			$value_3 = $_REQUEST['value_3'];
		$arrayWhere[$strField_3] = $value_3;
	}

	return $arrayWhere;
}


/******************************** 查询操作 **********************************/
// 通查
function select_tb($strTbName, $strField='arrays', $strOrderField='id', $strOrder='ASC', $strField_1=null, $value_1=null, $strField_2=null, $value_2=null)
{	// $strField('arrays'=>arrays, 'row'=>row, 其余=>value);
	ci()->db->order_by($strOrderField, $strOrder);
	$arrayWhere = array_form_where($strField_1, $value_1, $strField_2, $value_2);
	$query = ci()->db->get_where($strTbName, $arrayWhere);

	if( $strField==='arrays' )
		return $query->result_array();
	else if( $strField==='row' )
		return $query->row_array();
	else
		return $query->row_array()[$strField];
}

// 查表
function select_arrays($strTbName, $strField_1=null, $value_1=null, $strField_2=null, $value_2=null, $strField_3=null, $value_3=null)
{
	$arrayWhere = array_form_where($strField_1, $value_1, $strField_2, $value_2, $strField_3, $value_3);
	$arrays = ci()->db->get_where($strTbName, $arrayWhere)->result_array();
	return $arrays;
}

// 查行
function select_row($strTbName, $strField_1=null, $value_1=null, $strField_2=null, $value_2=null, $strField_3=null, $value_3=null)
{
	$arrayWhere = array_form_where($strField_1, $value_1, $strField_2, $value_2, $strField_3, $value_3);
	$row = ci()->db->get_where($strTbName, $arrayWhere)->row_array();
	return $row;
}

// 查值
function select_value($strTbName, $strField, $strField_1=null, $value_1=null, $strField_2=null, $value_2=null, $strField_3=null, $value_3=null)
{
	$arrayWhere = array_form_where($strField_1, $value_1, $strField_2, $value_2, $strField_3, $value_3);
	$row = ci()->db->get_where($strTbName, $arrayWhere)->row_array();
	if( $row===null )
		return null;
	else
		return $row[$strField];
}

// 查某列
function select_column($strTbName, $strField, $isSameValue='true')
{
	$sql		= " SELECT $strField FROM $strTbName ";
	$result		= query($sql)->result_array();
	$arrayCol	= array_column($result, $strField);
	return $isSameValue==='true' ? $arrayCol : array_unique($arrayCol);
}

// 查存在
function check_rows($strTbName, $strField_1=null, $value_1=null, $strField_2=null, $value_2=null, $strField_3=null, $value_3=null)
{
	$arrayWhere = array_form_where($strField_1, $value_1, $strField_2, $value_2, $strField_3, $value_3);
	$numRows = ci()->db->get_where($strTbName, $arrayWhere)->num_rows();
	return $numRows;
}


/******************************** 修改操作 **********************************/
function update_rows($strTbName, $arraySet, $strField_1, $value_1=null, $strField_2=null, $value_2=null, $strField_3=null, $value_3=null)
{
	$arrayWhere = array_form_where($strField_1, $value_1, $strField_2, $value_2, $strField_3, $value_3);
	ci()->db->update($strTbName, $arraySet, $arrayWhere);
	return true;
}

function update_tds($strTbName, $strField, $value='null', $strField_1, $value_1=null, $strField_2=null, $value_2=null, $strField_3=null, $value_3=null)
{
	if( $value==='null' )
		$value = $_POST['value'];

	$arrayWhere = array_form_where($strField_1, $value_1, $strField_2, $value_2, $strField_3, $value_3);
	$arraySet   = array($strField=>$value);

	ci()->db->update($strTbName, $arraySet, $arrayWhere);
		return true;
}


/******************************** 插入操作 **********************************/
function insert_row($strTbName, $arraySet=array())
{
	ci()->db->insert($strTbName, $arraySet);
	return ci()->db->insert_id();
}


/******************************** 删除操作 **********************************/
function delete_rows($strTbName, $strField_1, $value_1=null, $strField_2=null, $value_2=null, $strField_3=null, $value_3=null)
{
	$arrayWhere = array_form_where($strField_1, $value_1, $strField_2, $value_2, $strField_3, $value_3);
	ci()->db->delete($strTbName, $arrayWhere);
	return true;
}


/********************************** DDL *************************************/
function backup_db($strDbName, $isBackupToServer=false)
{
	$isSaveToServer = $isBackupToServer;
	$isDownload     = true;
	//$strDbName      = "{$strDbName}.sql";
	$strFileName    = "db_{$strDbName}_".date("Y_m_d_H_i_s", time()).'.zip';
	$strServerPath  = 'backup/'.$strFileName;

	// 备份选项
	$arraySet = array(
		'tables'     => array(),       // 备份的表名
		'ignore'     => array(),       // 忽略的表名
		'format'     => 'zip',          // gzip, zip, txt
		'filename'   => $strDbName,     // zip可用
		'add_drop'   => true,
		'add_insert' => true,
		'newline'    => "\n",
		'foreign_key_checks' =>	true	// 导出的 SQL 文件中是否继续保持外键约束
	);

	ci()->load->dbutil();
	ci()->load->helper('file');
	ci()->load->helper('download');

	$backup = ci()->dbutil->backup($arraySet);

	if( $isSaveToServer == true )
		write_file($strServerPath, $backup);
	if( $isDownload == true )
		force_download($strFileName, $backup);

	return 'true';
}