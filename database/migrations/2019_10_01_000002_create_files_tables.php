<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Z1px\App\Models\FilesModel;

class CreateFilesTables extends Migration
{

    private $files_model = FilesModel::class;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /**
         * 创建文件资源管理表
         * s_files
         */
        Schema::create(app($this->files_model)->getTable(), function (Blueprint $table) {

            $table->engine = 'InnoDB'; // 指定表存储引擎 (MySQL).
            $table->charset = 'utf8mb4'; // 指定表的默认字符编码 (MySQL).
            $table->collation = 'utf8mb4_unicode_ci'; // 指定表的默认排序格式 (MySQL).

            $table->bigIncrements('id')->comment('ID');
            $table->string('original_name', 50)->comment('文件原始名称');
            $table->string('disk', 20)->comment('文件存储磁盘名称');
            $table->string('visibility', 20)->comment('文件可见性，public可见，private不可见');
            $table->string('path_name', 200)->comment('文件路径');
            $table->bigInteger('size')->comment('文件大小，单位字节b');
            $table->tinyInteger('file_type')->index()->default(0)->comment('文件类型：1-图片文件类型，2-音频文件类型，3-视频文件类型，4-文本文件类型，5-应用文件类型，6-归档文件类型');
            $table->string('extension', 20)->comment('文件扩展名');
            $table->string('mime', 20)->comment('文件MIME类型');
            $table->string('md5', 32)->comment('文件MD5校验');
            $table->string('sha1', 40)->comment('文件SHA-1校验');
            $table->unsignedBigInteger('user_type')->default(0)->comment('用户类型：1-管理员，2-平台用户');
            $table->unsignedBigInteger('user_id')->default(0)->comment('文件创建者用户ID');
            $table->unsignedBigInteger('admin_id')->index()->default(0)->comment('后台操作管理员ID');
            $table->timestamp('created_at')->useCurrent()->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->useCurrent()->nullable()->comment('更新时间');
            $table->timestamp('deleted_at')->nullable()->comment('软删除时间');

            // 创建索引
            $table->index(['user_type', 'user_id']);
        });
        app('db')->statement("ALTER TABLE `" . app($this->files_model)->getTable() . "` comment '文件资源管理表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(app($this->files_model)->getTable()); // 删除文件资源管理表
    }
}
