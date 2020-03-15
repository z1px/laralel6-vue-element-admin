<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2020/2/4
 * Time: 2:49 上午
 */


namespace App\Http\Controllers\Admin;


use Z1px\App\Http\Services\ConfigService;

class ConfigController extends Controller
{

    public function __construct()
    {
        $this->model = ConfigService::class;
    }

    /**
     * 配置列表
     */
    public function getList()
    {
        return $this->_list();
    }

    /**
     * 所有配置
     */
    public function all()
    {
        return $this->_all();
    }

    /**
     * 配置信息
     */
    public function info()
    {
        return $this->_info();
    }

    /**
     * 根据键值获取配置信息
     */
    public function config()
    {
        return $this->json(['data' => app($this->model)->toConfig()]);
    }

    /**
     * 添加配置
     */
    public function add()
    {
        return $this->_add();
    }

    /**
     * 修改配置
     */
    public function update()
    {
        return $this->_update();
    }

    /**
     * 删除配置
     */
    public function delete()
    {
        return $this->_delete();
    }

}
