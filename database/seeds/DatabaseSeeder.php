<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
             ConfigTableSeeder::class, // 后台配置默认数据
             AdminsTableSeeder::class, // 后台管理员用户表默认数据
             PermissionsTableSeeder::class, // 后台权限表默认数据
         ]);
    }
}
