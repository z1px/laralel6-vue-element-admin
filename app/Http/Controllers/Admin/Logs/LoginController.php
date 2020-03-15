<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2019/11/6
 * Time: 10:55 上午
 */


namespace App\Http\Controllers\Admin\Logs;


use App\Http\Controllers\Admin\Controller;
use Z1px\App\Http\Services\Admins\AdminsLoginService;
use Z1px\App\Http\Services\Users\UsersLoginService;

class LoginController extends Controller
{

    private $admins_login_model = AdminsLoginService::class;
    private $users_login_model = UsersLoginService::class;

    /**
     * 管理员日志列表
     */
    public function admins()
    {
        $this->model = $this->admins_login_model;
        return $this->_list();
    }

    /**
     * 管理员日志列表
     */
    public function users()
    {
        $this->model = $this->users_login_model;
        return $this->_list();
    }
}
