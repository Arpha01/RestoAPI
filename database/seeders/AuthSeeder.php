<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create user account

        $user = new User();

        $user->name = 'user';
        $user->email = 'user@mail.com';
        $user->password = bcrypt('user123');
        $user->save();

        //Create admin account

        $admin = new Admin();

        $admin->name = 'admin';
        $admin->email = 'admin@mail.com';
        $admin->password = bcrypt('admin123');
        $admin->save();

    }
}
