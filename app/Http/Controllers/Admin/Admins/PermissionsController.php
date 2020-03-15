<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2019/10/19
 * Time: 3:02 下午
 */


namespace App\Http\Controllers\Admin\Admins;


use App\Http\Controllers\Admin\Controller;
use Z1px\App\Http\Services\Admins\PermissionsService;

class PermissionsController extends Controller
{

    public function __construct()
    {
        $this->model = PermissionsService::class;
    }

    /**
     * 权限列表
     */
    public function getList()
    {
        return $this->_list();
    }

    /**
     * 所有权限
     */
    public function all()
    {
        return $this->_all();
    }

    /**
     * 权限详情
     */
    public function info()
    {
        return $this->_info();
    }

    /**
     * 添加权限
     */
    public function add()
    {
        return $this->_add();
    }

    /**
     * 修改权限
     */
    public function update()
    {
        return $this->_update();
    }

    /**
     * 删除权限
     */
    public function delete()
    {
        return $this->_delete();
    }

}
