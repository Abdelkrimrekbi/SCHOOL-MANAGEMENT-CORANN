<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Roles;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => '12345678',
                'role' => 'admin',
            ],
            [
                'name' => 'Supervisor',
                'email' => 'supervisor@gmail.com',
                'password' => '1234567890',
                'role' => 'admin',
            ]
        ];

        foreach ($users as $user) {
            $created_user = User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
            ]);
            if (Roles::where('name', $user['role'])->exists()) {
                $created_user->assignRole($user['role']);
            }
            else {
                Log::debug("Not role users");
            }
        }
    }
}
