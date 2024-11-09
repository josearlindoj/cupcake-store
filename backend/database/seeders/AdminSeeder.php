<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::where('email', 'admin@admin.com')->first();

        if(empty($admin)) {
            Admin::create([
                'name' => 'Admin User',
                'email' => 'admin@mail.com',
                'password' => Hash::make('password'),
            ]);
        }
    }
}
