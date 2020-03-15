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


class AlterAdminsTables extends Migration
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
         * 更新角色-权限关系表
         */
        if(Schema::hasTable(app($this->roles_permissions_model)->getTable())
            && Schema::hasTable(app($this->roles_model)->getTable())
            && Schema::hasTable(app($this->permissions_model)->getTable())) {
            Schema::table(app($this->roles_permissions_model)->getTable(), function (Blueprint $table) {
                # 创建外键约束
                $table->foreign('role_id')
                    ->references('id')
                    ->on(app($this->roles_model)->getTable())
                    ->onDelete('cascade');
                $table->foreign('permission_id')
                    ->references('id')
                    ->on(app($this->permissions_model)->getTable())
                    ->onDelete('cascade');
            });
        }

        /**
         * 更新管理员-角色关系表
         */
        if(Schema::hasTable(app($this->admins_roles_model)->getTable())
            && Schema::hasTable(app($this->admins_model)->getTable())
            && Schema::hasTable(app($this->roles_model)->getTable())) {
            Schema::table(app($this->admins_roles_model)->getTable(), function (Blueprint $table) {
                # 创建外键约束
                $table->foreign('admin_id')
                    ->references('id')
                    ->on(app($this->admins_model)->getTable())
                    ->onDelete('cascade');
                $table->foreign('role_id')
                    ->references('id')
                    ->on(app($this->roles_model)->getTable())
                    ->onDelete('cascade');
            });
        }

        /**
         * 更新管理员-权限关系表
         */
        if(Schema::hasTable(app($this->admins_permissions_model)->getTable())
            && Schema::hasTable(app($this->admins_model)->getTable())
            && Schema::hasTable(app($this->permissions_model)->getTable())) {
            Schema::table(app($this->admins_permissions_model)->getTable(), function (Blueprint $table) {
                # 创建外键约束
                $table->foreign('admin_id')
                    ->references('id')
                    ->on(app($this->admins_model)->getTable())
                    ->onDelete('cascade');
                $table->foreign('permission_id')
                    ->references('id')
                    ->on(app($this->permissions_model)->getTable())
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
