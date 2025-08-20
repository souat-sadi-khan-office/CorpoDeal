<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cities')->insert([
            // Africa
            ['country_id' => 1, 'name' => 'Cape Town', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 2, 'name' => 'Cairo', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 3, 'name' => 'Algiers', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 4, 'name' => 'Lagos', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 5, 'name' => 'Addis Ababa', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Asia 
            ['country_id' => 6, 'name' => 'Dhaka', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 7, 'name' => 'Karachi', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 8, 'name' => 'Delhi', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 9, 'name' => 'Beijing', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 10, 'name' => 'Tokyo', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Europe
            ['country_id' => 11, 'name' => 'Berlin', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 12, 'name' => 'London', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 13, 'name' => 'Paris', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 14, 'name' => 'Rome', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 15, 'name' => 'Madrid', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // North America
            ['country_id' => 16, 'name' => 'New York', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Oceania
            ['country_id' => 17, 'name' => 'Sydney', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 18, 'name' => 'Auckland', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 19, 'name' => 'Suva', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 20, 'name' => 'Port Moresby', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 21, 'name' => 'Apia', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // South America
            ['country_id' => 22, 'name' => 'Buenos Aires', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 23, 'name' => 'Rio de Janeiro', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 24, 'name' => 'Lima', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['country_id' => 25, 'name' => 'Montevideo', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
