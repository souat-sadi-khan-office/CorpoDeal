<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('currencies')->insert([
            [
                'country_id' => 6,
                'name' => 'Bangladeshi Taka',
                'symbol' => '৳',
                'exchange_rate' => 0.0094,
                'status' => true,
                'code' => 'BDT',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'country_id' => 16,
                'name' => 'US Dollar',
                'symbol' => '$',
                'exchange_rate' => 1.00,
                'status' => true,
                'code' => 'USD',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);

    }
}
