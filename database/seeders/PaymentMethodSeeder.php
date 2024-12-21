<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod; 

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Dummy data
        PaymentMethod::create([
            'name' => 'Credit Card',
            'description' => 'Payments made via credit card.',
        ]);

        PaymentMethod::create([
            'name' => 'PayPal',
            'description' => 'Online payments via PayPal.',
        ]);

        PaymentMethod::create([
            'name' => 'Bank Transfer',
            'description' => 'Payments made through direct bank transfers.',
        ]);

        PaymentMethod::create([
            'name' => 'Cash',
            'description' => 'Payments made in cash.',
        ]);

        PaymentMethod::create([
            'name' => 'Bitcoin',
            'description' => 'Cryptocurrency payments via Bitcoin.',
        ]);
    }
}


