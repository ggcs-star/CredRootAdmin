<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $admin = User::firstOrCreate([
            'email' => 'admin@gmail.com'
        ],[
            'name' => 'Admin',
            'password' => Hash::make('12345678')
        ]);

        $admin->assignRole('admin');
    }
}