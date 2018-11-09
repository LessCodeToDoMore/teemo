<?php
// +----------------------------------------------------------------------
// | 助手函数
// +----------------------------------------------------------------------

// 渲染函数
function get_lib($lib, $subPath = '')
{
    $libDir = '/assets/libs';
    $mapLib = [
        // Jquery
        'jquery' => $libDir . '/jquery',
        'jquery.js' => $libDir . '/jquery/dist/jquery.js',
        'jquery.min.js' => $libDir . '/jquery/dist/jquery.min.js',

        // MUI
        'mui' => $libDir . '/dcloud-mui',
        'mui.css' => $libDir . '/dcloud-mui/dist/css/mui.css',
        'mui.js' => $libDir . '/dcloud-mui/dist/js/mui.js',
        'mui.min.css' => $libDir . '/dcloud-mui/dist/css/mui.min.css',
        'mui.min.js' => $libDir . '/dcloud-mui/dist/js/mui.min.js',

        // bootstrap
        'bootstrap' => $libDir . '/bootstrap',
        'bootstrap.css' => $libDir . '/bootstrap/dist/css/bootstrap.css',
        'bootstrap.js' => $libDir . '/bootstrap/dist/js/bootstrap.js',
        'bootstrap.min.css' => $libDir . '/bootstrap/dist/css/bootstrap.min.css',
        'bootstrap.min.js' => $libDir . '/bootstrap/dist/js/bootstrap.min.js',
    ];

    $dir = isset($mapLib[$lib]) ? $mapLib[$lib] : $lib;
    $url = $subPath === '' ? $dir : $dir . $subPath;
    return $url;
}

function get_addon_public($addonname, $subPath = '')
{
    $addonDir = '/assets/addons';
    $dir = $addonDir . '/' . $addonname;
    $url = $subPath === '' ? $dir : $dir . $subPath;
    return $url;
}

function import_file($lib, $subPath = '')
{
    $url = get_lib($lib, $subPath);
    $ext = get_file_ext($url);
    switch ($ext) {
        case 'css' :
            echo '<link href="' . $url . '" rel="stylesheet">';
            break;
        case 'js' :
            echo '<script src="' . $url . '"></script>';
            break;
        default :
            echo $url;
    }
}

// 文件
/**
 * 获取文件名后缀
 */
function get_file_ext($strFileName)
{
    $arraySubName = explode('.', $strFileName);
    return count($arraySubName) === 1 ? '' : end($arraySubName);
}

/// 时间
function getNowDatetime()
{
	return date("Y-m-d H:i:s", time());
}

function getNowDate()
{
	$nowDatetime = date("Y-m-d H:i:s", time());
	return substr($nowDatetime, 0, 10);
}

function getNowTime()
{
	$nowDatetime = date("Y-m-d H:i:s", time());
	return substr($nowDatetime, 11);
}

function getNowWeek()
{
	return array("日","一","二","三","四","五","六")[date("w")];
}

function getNowMicroTime()
{
	return date("YmdHis", time()).substr(microtime(), 2, 6);
}


//////////////////////////      改造内置函数       ////////////////////////
function explode_ex($strDelimiter, $strString, $intLimit=null)
{
	if ($strString===''){
		return array();
	}

	if ($intLimit===null){
		return explode($strDelimiter, $strString);
	}
	else{
		return explode($strDelimiter, $strString, $intLimit);
	}
}

function array_columns($input, $columnKey, $indexKey = null)
{
	$result = array();

	if(!is_array($input))
		return $result;

	$isFetchAll = false;
	foreach($input as $item)
	{
		if(is_array($columnKey)) // 数组
		{
			if(empty($columnKey))
				$isFetchAll = true;

			if(!empty($columnKey) || $isFetchAll)
			{
				$tempItem = '';
				if(!$isFetchAll)
				{
					foreach($columnKey as $colKey)
					{
						if(isset ($item[$colKey]))
							$tempItem[$colKey] = $item[$colKey];
					}
				}
				else
					$tempItem = $item;

				if(null !== $indexKey && isset($item[$indexKey]) && !is_array($item[$indexKey]))
					$result[$item[$indexKey]] = $tempItem;
				else
					$result[] = $tempItem;
			}
		}
		else // 整数、字符串
		{
			if(isset ($item[$columnKey]))
			{
				if(null !== $indexKey && isset($item[$indexKey]) && !is_array($item[$indexKey]))
					$result[$item[$indexKey]] = $item[$columnKey];
				else
					$result[] = $item[$columnKey];
			}
		}
	}

	return $result;
}


/// 控制器
function ok($msgOrData='ok', $dataPack=[])
{
	$message  = is_string($msgOrData) ? $msgOrData : 'ok';
	$dataPack = is_array($msgOrData)  ? $msgOrData : $dataPack;
	$data = [
		'code'   => 1,
		'msg'    => $message,
		'time'   => time(),
		'data'   => $dataPack
	];
	$response = \think\Response::create($data, 'json', 200, []);
	throw new \think\exception\HttpResponseException($response);
	exit();
}

function bad($msgOrCode='bad')
{
	$message  = $msgOrCode;

	if (is_int($msgOrCode))
	{
		$badcode = $message;
		$httpCode = parse_ini_file('StatusCode.php');
		if (isset($httpCod))
		{
			$message = $httpCode[$badcode];
		}
	}

	$data = [
		'code'   => 0,
		'msg'    => $message,
		'time'   => time(),
		'data'   => []
	];
	$response = \think\Response::create($data, 'json', 200, []);
	throw new \think\exception\HttpResponseException($response);
	exit();
}

function error($msgOrCode='failed', $code=null)
{
	$message  = is_int($msgOrCode) ? 'failed' : $msgOrCode;
	$code     = $code===null ? 400 : $code;
	if ($message==='failed')
	{
		$httpCode = parse_ini_file('StatusCode.php');
		if (isset($httpCode[$code]))
		{
			$message = $httpCode[$code];
		}
	}
	$data = [
		'code'   => 1,
		'msg'    => $message,
		'type'   => 'json',
		'data'   => []
	];
	return json(Response::create($data, 'json', $code, []));
}

/**
 *  参检, 必备字段与数字字段
 */
function checkInput($necessary=[], $numberParam=[])
{
	// 必备字段
    if (is_string($necessary)) {
        $necessary = [$necessary];
    }
    if (is_string($numberParam)) {
        $numberParam = [$numberParam];
    }

	foreach($necessary as $field)
	{
		if (input($field)===null) {
			bad('参数缺失:'.$field);
		}
		else {
			continue;
		}
	}

	// 类型限制
	foreach($numberParam as $field)
	{
		if (input($field)) {
			if (is_numeric(input($field))) {
				bad('参数类型不正确:'.$field);
			}
			else {
				continue;
			}
		}
	}
}

function checkRow($rowArray, $necessary = [])
{
    if (!$rowArray) {
        bad('参数组缺失');
    }
    foreach ($necessary as $k => $v) {
        if (!isset($rowArray[$v])) {
            bad('参数组缺失参数：' . $v);
            exit;
        }
    }
}


///////////  网络   /////////
function postToUrl($url, $postData)
{
	$strPost = "";
	foreach ($postData as $key=>$value)
	{
		$strPost .= "$key=".urlencode($value)."&";
	}
	$strPost=substr($strPost,0,-1);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $strPost);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	return curl_exec($curl);
}

function checkWhitelistIPs($arrayWhitelistIPs)
{
	$ret = in_array($_SERVER['REMOTE_ADDR'], $arrayWhitelistIPs);

	if ($ret===false){
		echo json_encode(['code'=>1, 'msg'=>'IP不存在于白名单']);
		exit;
	}
}

function inputToFields($arrayField)
{
	$karrayField = [];
	foreach($arrayField as $filed)
	{
		if (input('?'.$filed)){
			$karrayField[$filed] = input($filed);
		}
	}
	return $karrayField;
}

// 命令
/**
 * 生成优化缓存
 */
function generateOptimize()
{
    // debug模式无效
    $commands = [
        'optimize:autoload', // 生成类库映射文件 classmap.php
        'optimize:config',   // 生成配置缓存文件 init.php(config.php, common.php, tags.php)
        'optimize:route',    // 生成路由缓存文件 route.php
        'optimize:schema',   // 生成数据表字段缓存 schema目录
        // 'clear'           // 清空runtime目录下的所有文件
    ];
    chdir(ROOT_PATH);
    foreach ($commands as $command) {
        shell_exec('php think ' . $command);
    }
}

/**
 * 清除优化缓存
 */
function clearOptimize()
{
    if (is_file(RUNTIME_PATH . 'classmap.php'))
        unlink(RUNTIME_PATH . 'classmap.php');
    if (is_file(RUNTIME_PATH . 'init.php'))
        unlink(RUNTIME_PATH . 'init.php');
    if (is_file(RUNTIME_PATH . 'route.php'))
        unlink(RUNTIME_PATH . 'route.php');
    if (is_dir(RUNTIME_PATH . 'schema' . DS))
        rmdirs(RUNTIME_PATH . 'schema' . DS);
}