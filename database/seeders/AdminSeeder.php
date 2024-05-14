<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Admin Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'phone' => '07*000000',
            'profile_picture' => 'https://ik.imagekit.io/lztd93pns/Igralishte/user.png?updatedAt=1710007372065'
        ]);
    }
}
