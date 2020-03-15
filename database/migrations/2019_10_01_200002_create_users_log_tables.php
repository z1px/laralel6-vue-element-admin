<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Z1px\App\Models\Users\UsersBehaviorModel;
use Z1px\App\Models\Users\UsersLoginModel;


class CreateUsersLogTables extends Migration
{

    private $users_login_model = UsersLoginModel::class;
    private $users_behavior_model = UsersBehaviorModel::class;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 创建用户登录日志表
         * l_users_login
         */
        Schema::create(app($this->users_login_model)->getTable(), function (Blueprint $table) {

            $table->engine = 'InnoDB'; // 指定表存储引擎 (MySQL).
            $table->charset = 'utf8mb4'; // 指定表的默认字符编码 (MySQL).
            $table->collation = 'utf8mb4_unicode_ci'; // 指定表的默认排序格式 (MySQL).

            $table->bigIncrements('id')->comment('ID');
            $table->string('nickname', 30)->nullable()->comment('昵称');
            $table->string('username', 20)->nullable()->comment('账号');
            $table->string('mobile', 20)->nullable()->comment('手机号');
            $table->string('email', 30)->nullable()->comment('邮箱号');
            $table->string('route_name', 50)->nullable()->comment('路由名称');
            $table->string('url', 200)->nullable()->comment('请求地址');
            $table->json('params')->nullable()->comment('请求参数');
            $table->ipAddress('ip')->nullable()->comment('请求IP');
            $table->string('area', 50)->nullable()->comment('IP区域');
            $table->string('platform', 30)->nullable()->comment('客户端平台');
            $table->string('model', 30)->nullable()->comment('设备型号');
            $table->string('uuid', 36)->index()->comment('通用唯一识别码');
            $table->unsignedBigInteger('user_id')->index()->default(0)->comment('用户ID');
            $table->timestamp('created_at')->useCurrent()->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->useCurrent()->nullable()->comment('更新时间');
        });
        app('db')->statement("ALTER TABLE `" . app($this->users_login_model)->getTable() . "` comment '用户登录日志表'"); // 表注释

        /**
         * 创建用户行为日志表
         * l_users_behavior
         */
        Schema::create(app($this->users_behavior_model)->getTable(), function (Blueprint $table) {

            $table->engine = 'InnoDB'; // 指定表存储引擎 (MySQL).
            $table->charset = 'utf8mb4'; // 指定表的默认字符编码 (MySQL).
            $table->collation = 'utf8mb4_unicode_ci'; // 指定表的默认排序格式 (MySQL).

            $table->bigIncrements('id')->comment('ID');
            $table->string('title', 30)->nullable()->comment('行为名称');
            $table->string('route_name', 50)->nullable()->comment('路由名称');
            $table->string('url', 200)->nullable()->comment('请求地址');
            $table->json('params')->nullable()->comment('请求参数');
            $table->ipAddress('ip')->nullable()->comment('请求IP');
            $table->string('area', 50)->nullable()->comment('IP区域');
            $table->string('platform', 30)->nullable()->comment('客户端平台');
            $table->string('model', 30)->nullable()->comment('设备型号');
            $table->decimal('runtime', 10, 6)->default(0)->comment('运行时间，单位秒');
            $table->unsignedBigInteger('user_id')->index()->default(0)->comment('用户ID');
            $table->timestamp('created_at')->useCurrent()->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->useCurrent()->nullable()->comment('更新时间');
        });
        app('db')->statement("ALTER TABLE `" . app($this->users_behavior_model)->getTable() . "` comment '用户行为日志表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(app($this->users_login_model)->getTable()); // 删除用户登录日志表
        Schema::dropIfExists(app($this->users_behavior_model)->getTable()); // 删除用户行为日志表
    }
}
