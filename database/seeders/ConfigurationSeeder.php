<?php

namespace Database\Seeders;

use App\Models\ConfigurationSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ConfigurationSetting::create([
            'type'      =>  'system_name',
            'value'     =>  'Project Alpha',
        ]);

        ConfigurationSetting::create([
            'type'      =>  'system_timezone',
            'value'     =>  'Asia/Dhaka',
        ]);

        //  Create default Date Format
        ConfigurationSetting::create([
            'type'      =>  'system_date_format',
            'value'     =>  'd-m-Y',
        ]);

        //  Create default Time Format
        ConfigurationSetting::create([
            'type'      =>  'system_time_format',
            'value'     =>  'h:i A',
        ]);

        //  Create default notification_format
        ConfigurationSetting::create([
            'type'      =>  'system_notification_format',
            'value'     =>  'toast-top-right',
        ]);
    }
}
