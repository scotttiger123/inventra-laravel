<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentHead;

class PaymentHeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentHead::create([
            'name' => 'Customer',
            'created_by' => 1, // Assuming user with ID 1
            'updated_by' => 1, // Assuming user with ID 1
        ]);

        PaymentHead::create([
            'name' => 'Supplier',
            'created_by' => 1, // Assuming user with ID 1
            'updated_by' => 1, // Assuming user with ID 1
        ]);
    }
}
