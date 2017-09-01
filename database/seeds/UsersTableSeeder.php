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
            'name' => '王麒富',
            'email' => 'hd96@hdes.chc.edu.tw',
            'username' => 'hd96',
            'password' => bcrypt('demo1234'),
            'job_title' => '資訊組長',
            'group_id' => '323',
            'admin' => 1,
        ]);
    }
}
