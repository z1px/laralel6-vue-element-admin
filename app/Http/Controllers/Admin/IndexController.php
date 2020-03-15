<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2019/9/27
 * Time: 10:23 上午
 */


namespace App\Http\Controllers\Admin;


use Z1px\App\Http\Logics\AdminsLogic;

class IndexController extends Controller
{

    public function __construct()
    {
        $this->model = AdminsLogic::class;
    }

    public function index()
    {
        return view('backend');
    }

    /**
     * 登录
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return $this->json(app($this->model)->login());
    }

    /**
     * 获取登录账号信息
     * @return \Illuminate\Http\Response
     */
    public function info()
    {
        return $this->json(app($this->model)->info());
    }

    /**
     * 修改登录账号信息
     * @return \Illuminate\Http\Response
     */
    public function updateInfo()
    {
        return $this->json(app($this->model)->update());
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        return $this->json(app($this->model)->logout());
    }

    /**
     * 获取权限规则
     * @return \Illuminate\Http\Response
     */
    public function rules()
    {
        return $this->json(app($this->model)->permissions());
    }
}
