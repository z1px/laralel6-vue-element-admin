<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2019/11/5
 * Time: 1:50 下午
 */


namespace App\Http\Controllers\Admin\Logs;


use App\Http\Controllers\Admin\Controller;
use Z1px\App\Http\Services\TablesOperatedService;

class TablesOperatedController extends Controller
{

    public function __construct()
    {
        $this->model = TablesOperatedService::class;
    }

    /**
     * 数据库表操作日志列表
     */
    public function getList()
    {
        return $this->_list();
    }

    /**
     * 数据库表操作日志信息
     */
    public function info()
    {
        return $this->_info();
    }

}
