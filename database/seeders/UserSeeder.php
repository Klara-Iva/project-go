<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {

        DB::table('users')->insert([
            [
                'name' => 'Marija Horvat',
                'email' => 'marija.horvat@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Ivan Novak',
                'email' => 'ivan.novak@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Ana Kovač',
                'email' => 'ana.kovac@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Petar Živković',
                'email' => 'petar.zivkovic@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Luka Babić',
                'email' => 'luka.babic@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tanja Radoš',
                'email' => 'tanja.rados@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

    }
}