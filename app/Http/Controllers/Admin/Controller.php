<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{

    protected $model;

    /**
     * 列表
     */
    protected function _list(...$args)
    {
        $list = app($this->model)->toList(...$args);
        return $this->json([
            'data' => $list->items(),
            'total' => $list->total()
        ]);
    }

    /**
     * 所有
     */
    protected function _all(...$args)
    {
        return $this->json(['data' => app($this->model)->toListAll(...$args)]);
    }

    /**
     * 详细
     */
    protected function _info(...$args)
    {
        return $this->json(['data' => app($this->model)->toInfo(...$args)]);
    }

    /**
     * 添加
     */
    protected function _add(...$args)
    {
        return $this->json(app($this->model)->toAdd(...$args));
    }

    /**
     * 修改
     */
    protected function _update(...$args)
    {
        return $this->json(app($this->model)->toUpdate(...$args));
    }

    /**
     * 删除
     */
    protected function _delete(...$args)
    {
        return $this->json(app($this->model)->toDelete(...$args));
    }

    /**
     * 恢复
     */
    protected function _restore(...$args)
    {
        return $this->json(app($this->model)->toRestore(...$args));
    }

}
