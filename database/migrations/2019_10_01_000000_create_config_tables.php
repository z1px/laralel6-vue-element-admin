<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Z1px\App\Models\ConfigModel;

class CreateConfigTables extends Migration
{

    private $config_model = ConfigModel::class;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /**
         * 创建数据库表操作日志表
         * s_config
         */
        Schema::create(app($this->config_model)->getTable(), function (Blueprint $table) {

            $table->engine = 'InnoDB'; // 指定表存储引擎 (MySQL).
            $table->charset = 'utf8mb4'; // 指定表的默认字符编码 (MySQL).
            $table->collation = 'utf8mb4_unicode_ci'; // 指定表的默认排序格式 (MySQL).

            $table->bigIncrements('id')->comment('ID');
            $table->string('title', 30)->unique()->comment('标题');
            $table->string('key', 30)->unique()->comment('配置健');
            $table->string('value', 120)->nullable()->comment('配置值');
            $table->text('brief')->nullable()->comment('配置简介');
            $table->string('input', 30)->default('text')->comment('表单操作类型，text：文本，select：下拉框，radio：单选框，checkbox：复选框');
            $table->json('values')->nullable()->comment('默认可选值');
            $table->tinyInteger('type')->default(0)->comment('配置类型：1-系统配置，2-小程序配置，3-产品配置');
            $table->tinyInteger('status')->default(1)->comment('状态：1-正常，2-禁用');
            $table->timestamp('created_at')->useCurrent()->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->useCurrent()->nullable()->comment('更新时间');
        });
        app('db')->statement("ALTER TABLE `" . app($this->config_model)->getTable() . "` comment '通用配置表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(app($this->config_model)->getTable()); // 删除通用配置表
    }
}
