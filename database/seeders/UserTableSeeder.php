<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'currency_id' => '1',
                'name' => 'Test User',
                'email' => 'user1@example.com',
                'avatar' => 'user.png',
                'last_seen' => Carbon::now(),
                'password' => Hash::make('password123'),
                'status' => true,
                'code' => null,
                'latitude' => '34.052235',
                'longitude' => '-118.243683',
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
