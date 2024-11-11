<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Product
            'view_product',
            'add_product',
            'edit_product',
            'delete_product',

            // Purchase
            'view_purchase',
            'add_purchase',
            'edit_purchase',
            'delete_purchase',

            // Purchase Payment
            'view_purchase_payment',
            'add_purchase_payment',
            'edit_purchase_payment',
            'delete_purchase_payment',

            // Sale
            'view_sale',
            'add_sale',
            'edit_sale',
            'delete_sale',

            // Sale Payment
            'view_sale_payment',
            'add_sale_payment',
            'edit_sale_payment',
            'delete_sale_payment',

            // Expense
            'view_expense',
            'add_expense',
            'edit_expense',
            'delete_expense',

            // Income
            'view_income',
            'add_income',
            'edit_income',
            'delete_income',

            // Quotation
            'view_quotation',
            'add_quotation',
            'edit_quotation',
            'delete_quotation',

            // Transfer
            'view_transfer',
            'add_transfer',
            'edit_transfer',
            'delete_transfer',

            // Sale Return
            'view_sale_return',
            'add_sale_return',
            'edit_sale_return',
            'delete_sale_return',

            // Purchase Return
            'view_purchase_return',
            'add_purchase_return',
            'edit_purchase_return',
            'delete_purchase_return',
        ];

        // Inserting the permissions into the database
        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission,
            ]);
        }
    }
}
