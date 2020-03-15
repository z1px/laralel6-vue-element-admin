<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Z1px\App\Models\Admins\AdminsModel;
use Z1px\App\Models\Admins\AdminsPermissionsModel;
use Z1px\App\Models\Admins\AdminsRolesModel;
use Z1px\App\Models\Admins\PermissionsModel;
use Z1px\App\Models\Admins\RolesModel;
use Z1px\App\Models\Admins\RolesPermissionsModel;

class CreateAdminsTables extends Migration
{

    private $admins_model = AdminsModel::class;
    private $permissions_model = PermissionsModel::class;
    private $roles_model = RolesModel::class;
    private $roles_permissions_model = RolesPermissionsModel::class;
    private $admins_roles_model = AdminsRolesModel::class;
    private $admins_permissions_model = AdminsPermissionsModel::class;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 创建后台管理员账号表
         * m_admins
         */
        Schema::create(app($this->admins_model)->getTable(), function (Blueprint $table) {

            $table->engine = 'InnoDB'; // 指定表存储引擎 (MySQL).
            $table->charset = 'utf8mb4'; // 指定表的默认字符编码 (MySQL).
            $table->collation = 'utf8mb4_unicode_ci'; // 指定表的默认排序格式 (MySQL).

            // 创建表字段
            $table->bigIncrements('id')->comment('ID');
            $table->string('nickname', 30)->nullable()->comment('昵称');
            $table->string('username', 20)->unique()->nullable()->comment('账号');
            $table->string('mobile', 20)->unique()->nullable()->comment('手机号');
            $table->string('email', 30)->unique()->nullable()->comment('邮箱号');
//            $table->string('avatar', 100)->nullable()->comment('头像');
            $table->unsignedBigInteger('file_id')->index()->default(0)->comment('头像-关联文件ID');
            $table->string('password', 120)->nullable()->comment('密码');
            $table->string('google_secret', 30)->nullable()->comment('谷歌验证器密钥');
            $table->string('access_token', 100)->nullable()->comment('访问令牌');
            $table->tinyInteger('status')->default(1)->comment('账号状态：1-正常，2-禁用');
            $table->tinyInteger('login_failure')->default(0)->comment('连续登录失败次数');
            $table->timestamp('login_at')->nullable()->comment('最后登录时间');
            $table->timestamp('created_at')->useCurrent()->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->useCurrent()->nullable()->comment('更新时间');
            $table->timestamp('deleted_at')->nullable()->comment('软删除时间');
        });
        app('db')->statement("ALTER TABLE `" . app($this->admins_model)->getTable() . "` comment '后台管理员账号表'"); // 表注释

        /**
         * 创建权限表
         * m_permissions
         */
        Schema::create(app($this->permissions_model)->getTable(), function (Blueprint $table) {

            $table->engine = 'InnoDB'; // 指定表存储引擎 (MySQL).
            $table->charset = 'utf8mb4'; // 指定表的默认字符编码 (MySQL).
            $table->collation = 'utf8mb4_unicode_ci'; // 指定表的默认排序格式 (MySQL).

            $table->bigIncrements('id')->comment('ID');
            $table->string('title', 30)->unique()->comment('权限名称');
            $table->string('route_name', 50)->unique()->comment('路由名称');
            $table->text('brief')->nullable()->comment('权限简介');
            $table->tinyInteger('status')->default(1)->comment('状态：1-正常，2-禁用');
            $table->unsignedBigInteger('pid')->index()->default(0)->comment('父ID');
            $table->timestamp('created_at')->useCurrent()->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->useCurrent()->nullable()->comment('更新时间');
        });
        app('db')->statement("ALTER TABLE `" . app($this->permissions_model)->getTable() . "` comment '权限表'"); // 表注释

        /**
         * 创建角色表
         * m_roles
         */
        Schema::create(app($this->roles_model)->getTable(), function (Blueprint $table) {

            $table->engine = 'InnoDB'; // 指定表存储引擎 (MySQL).
            $table->charset = 'utf8mb4'; // 指定表的默认字符编码 (MySQL).
            $table->collation = 'utf8mb4_unicode_ci'; // 指定表的默认排序格式 (MySQL).

            $table->bigIncrements('id')->comment('ID');
            $table->string('title', 30)->unique()->comment('角色名称');
            $table->text('brief')->nullable()->comment('角色简介');
            $table->tinyInteger('status')->default(1)->comment('状态：1-正常，2-禁用');
            $table->timestamp('created_at')->useCurrent()->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->useCurrent()->nullable()->comment('更新时间');
        });
        app('db')->statement("ALTER TABLE `" . app($this->roles_model)->getTable() . "` comment '角色表'"); // 表注释

        /**
         * 创建角色-权限关系表
         * m_roles_permissions
         */
        Schema::create(app($this->roles_permissions_model)->getTable(), function (Blueprint $table) {

            $table->engine = 'InnoDB'; // 指定表存储引擎 (MySQL).
            $table->charset = 'utf8mb4'; // 指定表的默认字符编码 (MySQL).
            $table->collation = 'utf8mb4_unicode_ci'; // 指定表的默认排序格式 (MySQL).

            $table->bigIncrements('id')->comment('ID');
            $table->unsignedBigInteger('role_id')->index()->default(0)->comment('角色ID');
            $table->unsignedBigInteger('permission_id')->index()->default(0)->comment('权限ID');
            $table->timestamp('created_at')->useCurrent()->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->useCurrent()->nullable()->comment('更新时间');

            // 创建索引
            $table->unique(['role_id', 'permission_id']);
        });
        app('db')->statement("ALTER TABLE `" . app($this->roles_permissions_model)->getTable() . "` comment '角色-权限关系表'"); // 表注释

        /**
         * 创建管理员-角色关系表
         * m_admins_roles
         */
        Schema::create(app($this->admins_roles_model)->getTable(), function (Blueprint $table) {

            $table->engine = 'InnoDB'; // 指定表存储引擎 (MySQL).
            $table->charset = 'utf8mb4'; // 指定表的默认字符编码 (MySQL).
            $table->collation = 'utf8mb4_unicode_ci'; // 指定表的默认排序格式 (MySQL).

            $table->bigIncrements('id')->comment('ID');
            $table->unsignedBigInteger('admin_id')->index()->default(0)->comment('管理员ID');
            $table->unsignedBigInteger('role_id')->index()->default(0)->comment('角色ID');
            $table->timestamp('created_at')->useCurrent()->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->useCurrent()->nullable()->comment('更新时间');

            // 创建索引
            $table->unique(['admin_id', 'role_id']);
        });
        app('db')->statement("ALTER TABLE `" . app($this->admins_roles_model)->getTable() . "` comment '管理员-角色关系表'"); // 表注释

        /**
         * 创建管理员-权限关系表
         * m_admins_permissions
         */
        Schema::create(app($this->admins_permissions_model)->getTable(), function (Blueprint $table) {

            $table->engine = 'InnoDB'; // 指定表存储引擎 (MySQL).
            $table->charset = 'utf8mb4'; // 指定表的默认字符编码 (MySQL).
            $table->collation = 'utf8mb4_unicode_ci'; // 指定表的默认排序格式 (MySQL).

            $table->bigIncrements('id')->comment('ID');
            $table->unsignedBigInteger('admin_id')->index()->default(0)->comment('管理员ID');
            $table->unsignedBigInteger('permission_id')->index()->default(0)->comment('权限ID');
            $table->timestamp('created_at')->useCurrent()->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->useCurrent()->nullable()->comment('更新时间');

            // 创建索引
            $table->unique(['admin_id', 'permission_id']);
        });
        app('db')->statement("ALTER TABLE `" . app($this->admins_permissions_model)->getTable() . "` comment '管理员-权限关系表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::disableForeignKeyConstraints(); // 关闭外键约束
        Schema::dropIfExists(app($this->roles_permissions_model)->getTable()); // 删除角色-权限关系表
        Schema::dropIfExists(app($this->admins_roles_model)->getTable()); // 删除管理员-角色关系表
        Schema::dropIfExists(app($this->admins_permissions_model)->getTable()); // 删除管理员-权限关系表
        Schema::dropIfExists(app($this->permissions_model)->getTable()); // 删除权限表
        Schema::dropIfExists(app($this->roles_model)->getTable()); // 删除角色表
        Schema::dropIfExists(app($this->admins_model)->getTable()); // 删除管理员表
//        Schema::enableForeignKeyConstraints(); // 开启外键约束
    }
}
