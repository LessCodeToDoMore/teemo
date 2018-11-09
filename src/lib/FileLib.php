<?php
namespace Lib;

class FileLib
{
    /**
     * PHPExcle
     */

    // 给二维数组设置位置键, 返回
    public static function setExcelPosition($karrayContentData=array())
    {
        vendor('Teemo.library.LibArray');

        foreach($karrayContentData as $intSheetIndex=>$karraySheetData) // 表
        {
            foreach($karraySheetData['content'] as $intRowIndex=>$arrayRowData) // 内容
            {
                $arrayKeys = array_slice(\ArrayLib::getAllLetters(), 0, count($arrayRowData));
                $krrayRow = array_combine($arrayKeys, $arrayRowData);
                $karrayContentData[$intSheetIndex]['content'][$intRowIndex] = $krrayRow;
                foreach($krrayRow as $latter=>$value)
                {
                    unset($karrayContentData[$intSheetIndex]['content'][$intRowIndex][$latter]);
                    $karrayContentData[$intSheetIndex]['content'][$intRowIndex][$latter.$intRowIndex] = $value;
                }
            }
        }
        return $karrayContentData;
    }

    // 给PHPExcel对象设置数据
    public static function setExcelData($objPHPExcel, $karrayData)
    {
        foreach($karrayData as $intSheetIndex=>$karraySheetData)
        {
            if($intSheetIndex!==0){
                $objPHPExcel->createSheet();
            }

            $read = $objPHPExcel->setActiveSheetIndex($intSheetIndex)->setTitle($karraySheetData['title']);

            foreach ($karraySheetData['content'] as $intRowIndex=>$karrayRow)
            {
                foreach($karrayRow as $key=>$value)
                {
                    $read->setCellValue($key, $value);
                }
            }
        }
        return $objPHPExcel;
    }

    // PHPExcel内容格式参考
    public static function getExcelDefaultData()
    {
        return [
            0 => [
                'title'   => '表一',
                'content' => [
                    1   => ['姓名', '场景', '迟到', '早退', '未签到', '未签退'],
                    2   => ['张三', '上下班', '2', '3', '0', '1'],
                    3   => ['李四', '上下班', '3', '3', '4', '4']
                ]
            ],
            1 => [
                'title'   => '表二',
                'content' => [
                    1   => ['姓名', '场景', '迟到', '早退', '未签到', '未签退'],
                    2   => ['张三', '上下班', '4', '5', '6', '7']
                ]
            ],
            2 => [
                'title'   => '表三',
                'content' => [
                    1   => ['姓名', '场景', '迟到', '早退', '未签到', '未签退'],
                    2   => ['张三', '上下班', '4', '5', '6', '7']
                ]
            ]
        ];
    }

    public static function myExcel($pathExcel, $kContentData=array(), $strGetStyle='create', $sDealStyle='download')
    {
        vendor('lib.phpexcel.PHPExcel');
        vendor('lib.phpexcel.PHPExcel/IOFactory');

        $objPHPExcel = null;
        switch($strGetStyle)
        {
            case 'create':
                $objPHPExcel = new \PHPExcel();
                break;
            case 'load':
                $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                $objPHPExcel = $objReader->load($pathExcel);
                break;
        }

        $objPHPExcel = self::setExcelData($objPHPExcel, $kContentData);
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($pathExcel);

        switch($sDealStyle)
        {
            case 'download_and_save':
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $pathExcel . '"');
                header('Cache-Control: max-age=0');
                $objWriter->save('php://output');
                break;
            case 'download':
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $pathExcel . '"');
                header('Cache-Control: max-age=0');
                $objWriter->save('php://output');
                unlink($pathExcel);
                break;
            case 'save':
                break;
        }
    }


    /**
     * 无条件删除一个目录
     * @param $dir
     * @return bool
     */
    public static function delTree($dir) {
        if (!is_dir($dir)) {
            return true;
        }
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    /**
     * 清空一个目录
     * @param $dir
     * @param bool $isRetain : 是否保留目录结构
     */
    public static function clearDir($dir, $isRetain=false)
    {
        if (!is_dir($dir)) {
            return false;
        }
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            $path = "$dir/$file";
            if (is_dir($path)) {
                self::clearDir($path, $isRetain);
                if ($isRetain === true) {
                    rmdir($path);
                }
            } else {
                unlink($path);
            }
        }
    }

    /**
     * 复制目录中所有文件到一个已存在的目录中
     * @param $src
     * @param $dst
     */
    public static function copyTree($src, $dst)
    {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    @mkdir($dst . '/' . $file);
                    self::copyTree($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    /**
     * 复制文件夹（新文件夹需命名）
     * @param $src
     * @param $dst
     */
    public static function copyDir($src, $dst)
    {
        @mkdir($dst);
        return self::copyTree($src, $dst);
    }

    /**
     * 移动目录下的所有文件
     * @param $src
     * @param $dst
     */
    public static function removeTree($src, $dst)
    {
        self::copyTree($src, $dst);
        self::clearDir($src);
    }

    /**
     * 移动文件夹
     * @param $src
     * @param $dst
     */
    public static function removeDir($src, $dst)
    {
        self::copyDir($src, $dst);
        rmdir($src);
    }


    /**
     * 将文件夹中的元素添加至zip对象中
     */
    public static function addTreeToZip($zipObj, $srcDir, $zipDir = '')
    {
        $handler = opendir($srcDir);
        while (($elementName = readdir($handler)) !== false) {
            if (( $elementName != '.' ) && ( $elementName != '..' )) {
                $pathElement = $srcDir . '/' . $elementName;
                $depart = $zipDir === '' ? '' : '/';
                if (is_dir($pathElement)) {
                    $newDir = $zipDir . $depart . $elementName;
                    $zipObj->addEmptyDir($newDir);
                    self::addTreeToZip($zipObj, $pathElement, $newDir);
                } else {
                    $ret = $zipObj->addFile($pathElement, $zipDir . $depart . $elementName);
                }
            }
        }
        @closedir($handler);
    }

    /**
     * 压缩文件夹
     * @param $src
     * @param $dst
     * @return bool
     */
    public static function zipFolder($src, $dst)
    {
        $zip = new ZipArchive();
        if ($zip->open($dst, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE)) {
            self::addTreeToZip($zip, $src);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }

}
