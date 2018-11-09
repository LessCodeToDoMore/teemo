<?php
/**
 * Created by PhpStorm.
 * Auther: Teemo
 * Date: 2018/8/16
 * Time: 15:30
 */

class AmapSDK
{
    private $URL = 'http://yuntuapi.amap.com';
    private $KEY = null;
    private $TABLE_ID = null;
    private $API_CREATE = '/datamanage/data/create';
    private $API_UPDATE = '/datamanage/data/update';
    private $API_DELETE = '/datamanage/data/delete';
    private $API_QUERY  = '/datasearch/id';

    public $loctype = 1;  // 添加数据模式:1=经纬度,2=地区名称

    public function __construct($key = null, $tableId = null)
    {
        $this->KEY = $key ?: config('site.amap_key');
        $this->TABLE_ID = $tableId ?: config('site.amap_table_id');
    }

    /**
     * 添加地址
     */
    function create($data)
    {
        $url = $this->URL . $this->API_CREATE;
        $postData = [
            'key' => $this->KEY,
            'tableid' => $this->TABLE_ID,
            'loctype' => $this->loctype,
            'data' => json_encode($data),
        ];
        return \fast\Http::post($url, $postData);
    }

    /**
     * 修改地址
     */
    function update($data)
    {
        $url = $this->URL . $this->API_UPDATE;
        $postData = [
            'key' => $this->KEY,
            'tableid' => $this->TABLE_ID,
            'loctype' => $this->loctype,
            'data' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ];
        return \fast\Http::post($url, $postData);
    }

    /**
     * 删除地址
     */
    function delete($ids)
    {
        $url = $this->URL . $this->API_DELETE;
        $postData = [
            'key' => $this->KEY,
            'tableid' => $this->TABLE_ID,
            'ids' => $ids ,
        ];
        return \fast\Http::post($url, $postData);
    }

    /**
     * 查询地址
     */
    function query($ids)
    {
        $url = $this->URL . $this->API_QUERY;
        $postData = [
            'key' => $this->KEY,
            'tableid' => $this->TABLE_ID,
            '_id' => $ids ,
        ];
        return \fast\Http::post($url, $postData);
    }

}
