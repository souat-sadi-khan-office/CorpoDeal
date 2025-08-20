<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->insert([
            [
                'name' => 'Rahas Istiyak',
                'email' => 'admin@example.com',
                'phone' => '1234567890',
                'designation' => 'Super Admin',
                'password' => Hash::make('123456789'),
                'avatar' => 'default.png',
                'allow_changes' => true,
                'last_seen' => null,
                'last_login' => null,
                'address' => '123 Street',
                'area' => 'Area 1',
                'city' => 'City 1',
                'country' => 'Country 1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
