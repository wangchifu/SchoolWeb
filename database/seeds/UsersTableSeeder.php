<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        User::create([
            'name' => '王老師',
            'email' => 'hd96@hdes.chc.edu.tw',
            'username' => env('ADMIN_USERNAME'),
            'password' => bcrypt(env('DEFAULT_USER_PWD')),
            'job_title' => '資訊組長',
            'group_id' =>'1',
            'order_by' => '323',
            'admin' => 1,
        ]);
    }
}
