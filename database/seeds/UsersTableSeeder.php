<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        $admin = App\User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.org',
            'password' => Hash::make('admin'),
        ]);

        $user = factory('App\User')->create([
            'name' => 'user',
            'email' => 'user@user.org',
            'password' => Hash::make('user'),
        ]);
    }
}