<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ZoneTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(CurrencyTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(LaptopFinderSeeder::class);
    }
}
