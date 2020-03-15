<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Z1px\App\Models\Users\UsersModel;
use Z1px\App\Models\Users\UsersPassportsModel;
use Z1px\App\Models\Users\UsersThirdModel;


class CreateUsersTables extends Migration
{

    private $users_model = UsersModel::class;
    private $users_passports_model = UsersPassportsModel::class;
    private $users_third_model = UsersThirdModel::class;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 创建用户表
         * u_users
         */
        Schema::create(app($this->users_model)->getTable(), function (Blueprint $table) {

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
            $table->tinyInteger('status')->default(1)->comment('账号状态：1-正常，2-禁用');
            $table->tinyInteger('login_failure')->default(0)->comment('连续登录失败次数');
            $table->timestamp('login_at')->nullable()->comment('最后登录时间');
            $table->timestamp('created_at')->useCurrent()->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->useCurrent()->nullable()->comment('更新时间');
            $table->timestamp('deleted_at')->nullable()->comment('软删除时间');
        });
        app('db')->statement("ALTER TABLE `" . app($this->users_model)->getTable() . "` comment '用户表'"); // 表注释

        /**
         * 创建用户认证表
         * u_users_passports
         */
        Schema::create(app($this->users_passports_model)->getTable(), function (Blueprint $table) {

            $table->engine = 'InnoDB'; // 指定表存储引擎 (MySQL).
            $table->charset = 'utf8mb4'; // 指定表的默认字符编码 (MySQL).
            $table->collation = 'utf8mb4_unicode_ci'; // 指定表的默认排序格式 (MySQL).

            // 创建表字段
            $table->bigIncrements('id')->comment('ID');
            $table->string('access_token', 100)->nullable()->comment('访问令牌');
            $table->string('route_name', 50)->nullable()->comment('路由名称');
            $table->string('url', 200)->nullable()->comment('请求地址');
            $table->ipAddress('ip')->nullable()->comment('请求IP');
            $table->string('area', 50)->nullable()->comment('IP区域');
            $table->string('platform', 30)->nullable()->comment('客户端平台');
            $table->string('model', 30)->nullable()->comment('设备型号');
            $table->string('uuid', 36)->index()->comment('通用唯一识别码');
            $table->unsignedBigInteger('user_id')->index()->default(0)->comment('用户ID');
            $table->timestamp('created_at')->useCurrent()->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->useCurrent()->nullable()->comment('更新时间');
            $table->timestamp('deleted_at')->nullable()->comment('软删除时间');

            // 创建索引
            $table->unique(['user_id', 'uuid']);
        });
        app('db')->statement("ALTER TABLE `" . app($this->users_passports_model)->getTable() . "` comment '用户认证表'"); // 表注释

        /**
         * 用户第三方账号关联表
         * u_users_third
         */
        Schema::create(app($this->users_third_model)->getTable(), function (Blueprint $table) {

            $table->engine = 'InnoDB'; // 指定表存储引擎 (MySQL).
            $table->charset = 'utf8mb4'; // 指定表的默认字符编码 (MySQL).
            $table->collation = 'utf8mb4_unicode_ci'; // 指定表的默认排序格式 (MySQL).

            // 创建表字段
            $table->bigIncrements('id')->comment('ID');
            $table->string('unionid', 100)->nullable()->comment('用户在开放平台的唯一标识符');
            $table->string('openid', 100)->comment('用户唯一标识');
            $table->string('session_key', 100)->nullable()->comment('会话密钥');
            $table->tinyInteger('type')->default(1)->comment('账号类型：1-微信');
            $table->unsignedBigInteger('user_id')->index()->default(0)->comment('用户ID');
            $table->timestamp('created_at')->useCurrent()->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->useCurrent()->nullable()->comment('更新时间');
            $table->timestamp('deleted_at')->nullable()->comment('软删除时间');

            // 创建索引
            $table->unique(['user_id', 'openid', 'type']);
        });
        app('db')->statement("ALTER TABLE `" . app($this->users_third_model)->getTable() . "` comment '用户第三方账号关联表'"); // 表注释

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::disableForeignKeyConstraints(); // 关闭外键约束
        Schema::dropIfExists(app($this->users_passports_model)->getTable()); // 删除用户认证表
        Schema::dropIfExists(app($this->users_model)->getTable()); // 删除用户表
        Schema::dropIfExists(app($this->users_third_model)->getTable()); // 删除用户第三方账号关联表
//        Schema::enableForeignKeyConstraints(); // 开启外键约束
    }
}
