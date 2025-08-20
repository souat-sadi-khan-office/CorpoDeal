<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [

            // African Countries
            ['zone_id' => 1, 'name' => 'South Africa', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 1, 'name' => 'Egypt', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 1, 'name' => 'Algeria', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 1, 'name' => 'Nigeria', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 1, 'name' => 'Ethiopia', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Asia
            ['zone_id' => 2, 'name' => 'Bangladesh', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 2, 'name' => 'Pakistan', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 2, 'name' => 'India', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 2, 'name' => 'China', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 2, 'name' => 'Japan', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Europe
            ['zone_id' => 2, 'name' => 'Germany', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 3, 'name' => 'UK', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 3, 'name' => 'France', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 3, 'name' => 'Italy', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 3, 'name' => 'Spain', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            
            // North America
            ['zone_id' => 4, 'name' => 'USA', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // Oceania
            ['zone_id' => 5, 'name' => 'Australia', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 5, 'name' => 'New Zealand', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 5, 'name' => 'Fiji', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 5, 'name' => 'Papua New Guinea', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 5, 'name' => 'Samoa', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            // South America
            ['zone_id' => 5, 'name' => 'Argentina', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 5, 'name' => 'Brazil', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 5, 'name' => 'Peru', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 5, 'name' => 'Uruguay', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['zone_id' => 5, 'name' => 'Chili', 'status' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('countries')->insert($countries);
    }
}
