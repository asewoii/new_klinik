<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usernames = ['jors', 'nyoman', 'daffa', 'rina', 'yanti'];
        $users = [];

        foreach ($usernames as $username) {
            $users[] = [
                'id' => Str::uuid(),
                'username' => $username,
                'password' => Hash::make('12345678'), // password default untuk semua
                'role' => 'admin', // bisa diganti sesuai kebutuhan
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}
