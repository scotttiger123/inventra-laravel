<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        // Define settings
        $settings = [
            
            ['name' => 'company-name', 'value' => 'Inventra'],
            ['name' => 'phone', 'value' => '1234567890'],
            ['name' => 'email', 'value' => 'info@inventrapro.com'],
            ['name' => 'address', 'value' => '123 Main Street, City, Country'],
            ['name' => 'invoice_footer', 'value' => 'Thank you for your business!'],
            ['name' => 'enable_invoice_footer', 'value' => '1'],
        ];

        // Insert settings into the database
        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['name' => $setting['name']],
                ['value' => $setting['value']]
            );
        }
    }    
}
