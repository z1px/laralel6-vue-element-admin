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

// 在 「App\Http\Controllers\Admin」 命名空间下的控制器

Route::get('/', "IndexController@index")->name('index'); // 首页

Route::post('login', "IndexController@login")->name('login'); // 登录

Route::middleware('admin.auth')->group(function () {

    Route::post('info', "IndexController@info")->name('info'); // 当前登录用户信息
    Route::post('updateInfo', "IndexController@updateInfo")->name('updateInfo'); // 修改当前登录用户信息
    Route::post('rules', "IndexController@rules")->name('rules'); // 规则
    Route::post('logout', "IndexController@logout")->name('logout'); // 退出

    // 配置设置
    Route::post('config.list', "ConfigController@getList")->name('config.list'); // 配置列表
    Route::post('config.all', "ConfigController@all")->name('config.all'); // 所有配置
    Route::post('config.info', "ConfigController@info")->name('config.info'); // 配置信息
    Route::post('config.config', "ConfigController@config")->name('config.config'); // 根据键值获取配置信息
    Route::post('config.add', "ConfigController@add")->name('config.add'); // 添加配置
    Route::post('config.update', "ConfigController@update")->name('config.update'); // 修改配置
    Route::post('config.delete', "ConfigController@delete")->name('config.delete'); // 删除配置

    // 文件管理
    Route::post('upload', "FilesController@upload")->name('upload'); // 文件上传

    Route::post('files.list', "FilesController@getList")->name('files.list'); // 文件列表
    Route::post('files.visible', "FilesController@visible")->name('files.visible'); // 设置文件可见
    Route::post('files.invisible', "FilesController@invisible")->name('files.invisible'); // 设置文件不可见
    Route::post('files.delete', "FilesController@delete")->name('files.delete'); // 删除文件

    // 数据库表操作日志
    Route::post('logs.tables_operated', "Logs\TablesOperatedController@getList")->name('logs.tables_operated'); // 数据库表操作日志列表
    Route::post('logs.tables_operated.info', "Logs\TablesOperatedController@info")->name('logs.tables_operated.info'); // 数据库表操作日志列表
    // 管理员日志
    Route::post('logs.login.admins', "Logs\LoginController@admins")->name('logs.login.admins'); // 登录日志列表
    Route::post('logs.behavior.admins', "Logs\BehaviorController@admins")->name('logs.behavior.admins'); // 行为日志列表
    // 用户日志
    Route::post('logs.login.users', "Logs\LoginController@users")->name('logs.login.users'); // 登录日志列表
    Route::post('logs.behavior.users', "Logs\BehaviorController@users")->name('logs.behavior.users'); // 行为日志列表

    // 管理员账号管理
    Route::post('admins.list', "Admins\AdminsController@getList")->name('admins.list'); // 账号列表
    Route::post('admins.info', "Admins\AdminsController@info")->name('admins.info'); // 账号信息
    Route::post('admins.add', "Admins\AdminsController@add")->name('admins.add'); // 添加账号
    Route::post('admins.update', "Admins\AdminsController@update")->name('admins.update'); // 修改账号
    Route::post('admins.delete', "Admins\AdminsController@delete")->name('admins.delete'); // 删除账号
    Route::post('admins.restore', "Admins\AdminsController@restore")->name('admins.restore'); // 恢复账号
    Route::post('admins.getRoles', "Admins\AdminsController@getRoles")->name('admins.getRoles'); // 获取角色权限
    Route::post('admins.setRoles', "Admins\AdminsController@setRoles")->name('admins.setRoles'); // 设置角色权限

    // 权限设置
    Route::post('permissions.list', "Admins\PermissionsController@getList")->name('permissions.list'); // 权限列表
    Route::post('permissions.all', "Admins\PermissionsController@all")->name('permissions.all'); // 所有权限
    Route::post('permissions.info', "Admins\PermissionsController@info")->name('permissions.info'); // 权限信息
    Route::post('permissions.add', "Admins\PermissionsController@add")->name('permissions.add'); // 添加权限
    Route::post('permissions.update', "Admins\PermissionsController@update")->name('permissions.update'); // 修改权限
    Route::post('permissions.delete', "Admins\PermissionsController@delete")->name('permissions.delete'); // 删除权限

    // 角色设置
    Route::post('roles.list', "Admins\RolesController@getList")->name('roles.list'); // 角色列表
    Route::post('roles.all', "Admins\RolesController@all")->name('roles.all'); // 所有角色
    Route::post('roles.info', "Admins\RolesController@info")->name('roles.info'); // 角色信息
    Route::post('roles.add', "Admins\RolesController@add")->name('roles.add'); // 添加角色
    Route::post('roles.update', "Admins\RolesController@update")->name('roles.update'); // 修改角色
    Route::post('roles.delete', "Admins\RolesController@delete")->name('roles.delete'); // 删除角色
    Route::post('roles.getPermissions', "Admins\RolesController@getPermissions")->name('roles.getPermissions'); // 获取角色权限
    Route::post('roles.setPermissions', "Admins\RolesController@setPermissions")->name('roles.setPermissions'); // 设置角色权限

    // 用户账号管理
    Route::post('users.list', "Users\UsersController@getList")->name('users.list'); // 账号列表
    Route::post('users.info', "Admins\UsersController@info")->name('users.info'); // 账号信息
    Route::post('users.add', "Users\UsersController@add")->name('users.add'); // 添加账号
    Route::post('users.update', "Users\UsersController@update")->name('users.update'); // 修改账号
    Route::post('users.delete', "Users\UsersController@delete")->name('users.delete'); // 删除账号
    Route::post('users.restore', "Users\UsersController@restore")->name('users.restore'); // 恢复账号

});
