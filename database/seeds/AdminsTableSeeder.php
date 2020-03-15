<?php

use Illuminate\Database\Seeder;
use Z1px\App\Models\Admins\AdminsModel;

class AdminsTableSeeder extends Seeder
{

    private $admins_model = AdminsModel::class;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        request()->offsetSet('command',  "console: php artisan {$this->command->getName()}");
        # 添加默认管理员账号
        $check = app($this->admins_model)
            ->where("username", "sky001")
            ->count();
        if(0 === $check){
            app($this->admins_model)->reguard();
            app($this->admins_model)->fill([
                'username' => 'sky001',
                'password' => 'sky123'
            ])->save();
        }
    }
}
