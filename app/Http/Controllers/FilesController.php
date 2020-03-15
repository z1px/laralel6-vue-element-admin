<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 2019/11/1
 * Time: 8:50 上午
 */


namespace App\Http\Controllers;


use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Z1px\App\Http\Services\FilesService;

class FilesController extends Controller
{

    protected $model = FilesService::class;

    /**
     * 文件读取
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function file()
    {
        $data = app($this->model)->toInfo();
        if(empty($data)){
            return $this->error();
        }
        return response(Storage::disk($data->disk)->get($data->path_name))->header('Content-Type', $data->mime);
    }

    /**
     * 图片
     * @return string
     */
    public function image()
    {
        $data = app($this->model)->toInfo();
        if(empty($data) || $data->file_type !== 1){
            return $this->error();
        }
        $img = Image::cache(function($image) use ($data) {
            $image->make(Storage::disk($data->disk)->get($data->path_name));
            if(request()->has('width')){
                switch (request()->input('type', 'resize')){
                    case 'resize': // 填充缩放
                        $image->resize(request()->input('width', null), request()->input('height', null));
                        break;
                    case 'ratio': // 等比例缩放
                        $image->resize(request()->input('width', null), request()->input('height', null), function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        break;
                    case 'fit': // 裁剪
                        if(request()->has('width')){
                            $image->fit(request()->input('width'), request()->input('height'));
                        }
                        break;
                }
            }
//            $image->greyscale(); // 黑白
        }, 10, true);
        return $img->response()->header('Content-Type', $data->mime);
    }

    /**
     * 音频
     * @return string
     */
    public function audio()
    {
        $data = app($this->model)->toInfo();
        if(empty($data) || $data->file_type !== 2){
            return $this->error();
        }
        return response(Storage::disk($data->disk)->get($data->path_name))->header('Content-Type', $data->mime);
    }

    /**
     * 视频
     * @return string
     */
    public function video()
    {
        $data = app($this->model)->toInfo();
        if(empty($data) || $data->file_type !== 3){
            return $this->error();
        }
        return response(Storage::disk($data->disk)->get($data->path_name))->header('Content-Type', $data->mime);
    }

    /**
     * 文本
     * @return string
     */
    public function text()
    {
        $data = app($this->model)->toInfo();
        if(empty($data) || $data->file_type !== 4){
            return $this->error();
        }
        return response(Storage::disk($data->disk)->get($data->path_name))->header('Content-Type', $data->mime);
    }

    /**
     * 应用
     * @return string
     */
    public function application()
    {
        $data = app($this->model)->toInfo();
        if(empty($data) || $data->file_type !== 5){
            return $this->error();
        }
        return response(Storage::disk($data->disk)->get($data->path_name))->header('Content-Type', $data->mime);
    }

    /**
     * 归档
     * @return string
     */
    public function archive()
    {
        $data = app($this->model)->toInfo();
        if(empty($data) || $data->file_type !== 6){
            return $this->error();
        }
        return response(Storage::disk($data->disk)->get($data->path_name))->header('Content-Type', $data->mime);
    }

    /**
     * 下载
     * @return string
     */
    public function download()
    {
        $data = app($this->model)->toInfo();
        if(empty($data)){
            return $this->error();
        }
        return Storage::disk($data->disk)->download($data->path_name);
    }

}
