<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2019/10/24
 * Time: 9:33 下午
 */


namespace App\Http\Controllers\Admin\Admins;


use App\Http\Controllers\Admin\Controller;
use Z1px\App\Http\Services\Admins\RolesService;

class RolesController extends Controller
{

    public function __construct()
    {
        $this->model = RolesService::class;
    }

    /**
     * 角色列表
     */
    public function getList()
    {
        return $this->_list();
    }

    /**
     * 所有角色
     */
    public function all()
    {
        return $this->_all();
    }

    /**
     * 角色信息
     */
    public function info()
    {
        return $this->_info();
    }

    /**
     * 添加角色
     */
    public function add()
    {
        return $this->_add();
    }

    /**
     * 修改角色
     */
    public function update()
    {
        return $this->_update();
    }

    /**
     * 删除角色
     */
    public function delete()
    {
        return $this->_delete();
    }

    /**
     * 获取角色权限
     */
    public function getPermissions()
    {
        return $this->json(app($this->model)->getPermissions());
    }

    /**
     * 设置角色权限
     */
    public function setPermissions()
    {
        return $this->json(app($this->model)->setPermissions());
    }

}
