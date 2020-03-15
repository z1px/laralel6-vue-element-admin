<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2019/11/1
 * Time: 3:13 下午
 */


namespace App\Http\Controllers\Admin;


use Z1px\App\Http\Services\FilesService;

class FilesController extends Controller
{

    public function __construct()
    {
        $this->model = FilesService::class;
    }

    /**
     * 文件资源列表
     */
    public function getList()
    {
        return $this->_list();
    }

    /**
     * 文件上传
     */
    public function upload()
    {
        return $this->json(app($this->model)->upload());
    }

    /**
     * 设置文件可见
     */
    public function visible()
    {
        return $this->json(app($this->model)->toVisible());
    }

    /**
     * 设置文件不可见
     */
    public function invisible()
    {
        return $this->json(app($this->model)->toInvisible());
    }

    /**
     * 删除文件
     */
    public function delete()
    {
        return $this->_delete();
    }
}
