<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Team;

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
                'name' => 'Petar Copy',
                'email' => 'petar.zivkovic.copy@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Ana Copy',
                'email' => 'ana.kovac.copy@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        $userIds = DB::table('users')->pluck('id')->toArray();
        $teamIds = DB::table('teams')->pluck('id')->toArray();

        DB::table('user_team')->insert([
            ['user_id' => $userIds[1], 'team_id' => $teamIds[0]], //teamlead 1
            ['user_id' => $userIds[1], 'team_id' => $teamIds[1]], //teamlead 1
            ['user_id' => $userIds[2], 'team_id' => $teamIds[1]], //project manager 1
            ['user_id' => $userIds[3], 'team_id' => $teamIds[0]], //project manager 2
            ['user_id' => $userIds[4], 'team_id' => $teamIds[1]], //employee 1
            ['user_id' => $userIds[5], 'team_id' => $teamIds[0]], //employee 2
        ]);

        $faker = Faker::create();
        $teamIds = Team::pluck('id')->toArray();

        $roles = array_merge(
            array_fill(0, 75, 4),
            array_fill(0, 5, 2),
            array_fill(0, 5, 3),
            array_fill(0, 15, 5)
        );

        for ($i = 0; $i < 100; $i++) {
            $role_id = $faker->randomElement($roles);

            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'role_id' => $role_id,

            ]);

            if ($role_id != 5) {
                $randomTeamId = $faker->randomElement($teamIds);

                DB::table('user_team')->insert([
                    'user_id' => $user->id,
                    'team_id' => $randomTeamId,
                ]);

            }

        }

    }

}