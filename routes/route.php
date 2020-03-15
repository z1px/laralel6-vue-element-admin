<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 通用路由

Route::get('file', "FilesController@file")->name('files'); // 文件
Route::get('image', "FilesController@image")->name('files.image'); // 图片
Route::get('audio', "FilesController@audio")->name('files.audio'); // 音频
Route::get('video', "FilesController@video")->name('files.video'); // 视频
Route::get('text', "FilesController@text")->name('files.text'); // 文本
Route::get('app', "FilesController@application")->name('files.application'); // 应用
Route::get('archive', "FilesController@archive")->name('files.archive'); // 归档
Route::get('download', "FilesController@download")->name('files.download'); // 下载
