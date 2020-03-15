<?php

use Illuminate\Database\Seeder;
use Z1px\App\Models\Admins\PermissionsModel;

class PermissionsTableSeeder extends Seeder
{

    private $permissions_model = PermissionsModel::class;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        request()->offsetSet('command',  "console: php artisan {$this->command->getName()}");
        # 添加默认权限
        $data = app($this->permissions_model)->create([
            'title' => '首页',
            'route_name' => 'admin.index'
        ]);
        array_map(function ($data){
            app($this->permissions_model)->create($data);
        }, [
            [
                'title' => '登录',
                'route_name' => 'admin.login',
                'pid' => $data->id
            ],
            [
                'title' => '登录用户信息',
                'route_name' => 'admin.info',
                'pid' => $data->id
            ],
            [
                'title' => '更新登录用户信息',
                'route_name' => 'admin.updateInfo',
                'pid' => $data->id
            ],
            [
                'title' => '登录用户权限',
                'route_name' => 'admin.rules',
                'pid' => $data->id
            ],
            [
                'title' => '登录用户退出',
                'route_name' => 'admin.logout',
                'pid' => $data->id
            ],
            [
                'title' => '文件上传',
                'route_name' => 'admin.upload',
                'pid' => $data->id
            ]
        ]);
        unset($data);

        $data_manage = app($this->permissions_model)->create([
            'title' => '后台管理',
            'route_name' => 'admin.manage'
        ]);

        $data_admins = app($this->permissions_model)->create([
            'title' => '管理员管理',
            'route_name' => 'admin.admins.index',
            'pid' => $data_manage->id
        ]);
        array_map(function ($data){
            app($this->permissions_model)->create($data);
        }, [
            [
                'title' => '管理员列表',
                'route_name' => 'admin.admins.list',
                'pid' => $data_admins->id
            ],
            [
                'title' => '管理员信息',
                'route_name' => 'admin.admins.info',
                'pid' => $data_admins->id
            ],
            [
                'title' => '添加管理员',
                'route_name' => 'admin.admins.add',
                'pid' => $data_admins->id
            ],
            [
                'title' => '修改管理员',
                'route_name' => 'admin.admins.update',
                'pid' => $data_admins->id
            ],
            [
                'title' => '获取管理员角色',
                'route_name' => 'admin.admins.getRoles',
                'pid' => $data_admins->id
            ],
            [
                'title' => '设置管理员角色',
                'route_name' => 'admin.admins.setRoles',
                'pid' => $data_admins->id
            ]
        ]);
        unset($data_admins);

        $data_roles = app($this->permissions_model)->create([
            'title' => '角色管理',
            'route_name' => 'admin.roles.index',
            'pid' => $data_manage->id
        ]);
        array_map(function ($data){
            app($this->permissions_model)->create($data);
        }, [
            [
                'title' => '所有角色',
                'route_name' => 'admin.roles.all',
                'pid' => $data_roles->id
            ],
            [
                'title' => '角色列表',
                'route_name' => 'admin.roles.list',
                'pid' => $data_roles->id
            ],
            [
                'title' => '角色信息',
                'route_name' => 'admin.roles.info',
                'pid' => $data_roles->id
            ],
            [
                'title' => '添加角色',
                'route_name' => 'admin.roles.add',
                'pid' => $data_roles->id
            ],
            [
                'title' => '修改角色',
                'route_name' => 'admin.roles.update',
                'pid' => $data_roles->id
            ],
            [
                'title' => '删除角色',
                'route_name' => 'admin.roles.delete',
                'pid' => $data_roles->id
            ],
            [
                'title' => '获取角色权限',
                'route_name' => 'admin.roles.getPermissions',
                'pid' => $data_roles->id
            ],
            [
                'title' => '设置角色权限',
                'route_name' => 'admin.roles.setPermissions',
                'pid' => $data_roles->id
            ]
        ]);
        unset($data_roles);

        $data_permissions = app($this->permissions_model)->create([
            'title' => '权限管理',
            'route_name' => 'admin.permissions.index',
            'pid' => $data_manage->id
        ]);
        array_map(function ($data){
            app($this->permissions_model)->create($data);
        }, [
            [
                'title' => '所有权限',
                'route_name' => 'admin.permissions.all',
                'pid' => $data_permissions->id
            ],
            [
                'title' => '权限列表',
                'route_name' => 'admin.permissions.list',
                'pid' => $data_permissions->id
            ],
            [
                'title' => '权限信息',
                'route_name' => 'admin.permissions.info',
                'pid' => $data_permissions->id
            ],
            [
                'title' => '添加权限',
                'route_name' => 'admin.permissions.add',
                'pid' => $data_permissions->id
            ],
            [
                'title' => '修改权限',
                'route_name' => 'admin.permissions.update',
                'pid' => $data_permissions->id
            ],
            [
                'title' => '删除权限',
                'route_name' => 'admin.permissions.delete',
                'pid' => $data_permissions->id
            ]
        ]);
        unset($data_permissions);
        unset($data_manage);


        $data_system = app($this->permissions_model)->create([
            'title' => '系统管理',
            'route_name' => 'admin.system'
        ]);

        $data_config = app($this->permissions_model)->create([
            'title' => '系统配置',
            'route_name' => 'admin.config.index',
            'pid' => $data_system->id
        ]);
        array_map(function ($data){
            app($this->permissions_model)->create($data);
        }, [
            [
                'title' => '所有配置',
                'route_name' => 'admin.config.all',
                'pid' => $data_config->id
            ],
            [
                'title' => '配置列表',
                'route_name' => 'admin.config.list',
                'pid' => $data_config->id
            ],
            [
                'title' => '配置信息',
                'route_name' => 'admin.config.info',
                'pid' => $data_config->id
            ],
            [
                'title' => '获取配置',
                'route_name' => 'admin.config.config',
                'pid' => $data_config->id
            ],
            [
                'title' => '添加配置',
                'route_name' => 'admin.config.add',
                'pid' => $data_config->id
            ],
            [
                'title' => '修改配置',
                'route_name' => 'admin.config.update',
                'pid' => $data_config->id
            ],
            [
                'title' => '删除配置',
                'route_name' => 'admin.config.delete',
                'pid' => $data_config->id
            ]
        ]);
        unset($data_config);

        $data_files = app($this->permissions_model)->create([
            'title' => '文件管理',
            'route_name' => 'admin.files.index',
            'pid' => $data_system->id
        ]);
        array_map(function ($data){
            app($this->permissions_model)->create($data);
        }, [
            [
                'title' => '文件列表',
                'route_name' => 'admin.files.list',
                'pid' => $data_files->id
            ],
            [
                'title' => '设置文件可见',
                'route_name' => 'admin.files.visible',
                'pid' => $data_files->id
            ],
            [
                'title' => '设置文件不可见',
                'route_name' => 'admin.files.invisible',
                'pid' => $data_files->id
            ],
            [
                'title' => '删除文件',
                'route_name' => 'admin.files.delete',
                'pid' => $data_files->id
            ]
        ]);
        unset($data_files);
        unset($data_system);


        $data_logs = app($this->permissions_model)->create([
            'title' => '日志管理',
            'route_name' => 'admin.logs'
        ]);
        array_map(function ($data){
            app($this->permissions_model)->create($data);
        }, [
            [
                'title' => '登录日志',
                'route_name' => 'admin.logs.login.admins',
                'pid' => $data_logs->id
            ],
            [
                'title' => '行为日志',
                'route_name' => 'admin.logs.behavior.admins',
                'pid' => $data_logs->id
            ]
        ]);
        $data_tables_operated = app($this->permissions_model)->create([
            'title' => '表操作日志',
            'route_name' => 'admin.logs.tables_operated',
            'pid' => $data_logs->id
        ]);
        app($this->permissions_model)->create([
            'title' => '表操作日志信息',
            'route_name' => 'admin.logs.tables_operated.info',
            'pid' => $data_tables_operated->id
        ]);
        unset($data_tables_operated);
        unset($data_logs);
    }
}
