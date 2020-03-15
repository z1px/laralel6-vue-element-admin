<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2019/11/6
 * Time: 10:55 上午
 */


namespace App\Http\Controllers\Admin\Logs;


use App\Http\Controllers\Admin\Controller;
use Z1px\App\Http\Services\Admins\AdminsBehaviorService;
use Z1px\App\Http\Services\Users\UsersBehaviorService;

class BehaviorController extends Controller
{

    private $admins_behavior_model = AdminsBehaviorService::class;
    private $users_behavior_model = UsersBehaviorService::class;

    /**
     * 管理员日志列表
     */
    public function admins()
    {
        $this->model = $this->admins_behavior_model;
        return $this->_list();
    }

    /**
     * 管理员日志列表
     */
    public function users()
    {
        $this->model = $this->users_behavior_model;
        return $this->_list();
    }
}
