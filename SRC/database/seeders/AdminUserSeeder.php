<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                User::firstOrCreate(
            ['email' => 'admin@example.com'], // Điều kiện để tìm kiếm
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // Mật khẩu là 'password', bạn nên đổi thành một mật khẩu mạnh hơn
                'role' => 'admin',
                'email_verified_at' => now(), // Tự động xác thực email cho admin
            ]
        );
    }
}
