<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Sale Module Permissions
        Permission::create(['name' => 'view_sales']);
        Permission::create(['name' => 'create_sales']);
        Permission::create(['name' => 'edit_sales']);
        Permission::create(['name' => 'delete_sales']);

        // Purchase Module Permissions
        Permission::create(['name' => 'view_purchases']);
        Permission::create(['name' => 'create_purchases']);
        Permission::create(['name' => 'edit_purchases']);
        Permission::create(['name' => 'delete_purchases']);

        // Income Module Permissions
        Permission::create(['name' => 'view_incomes']);
        Permission::create(['name' => 'create_incomes']);
        Permission::create(['name' => 'edit_incomes']);
        Permission::create(['name' => 'delete_incomes']);

        // Expense Module Permissions
        Permission::create(['name' => 'view_expenses']);
        Permission::create(['name' => 'create_expenses']);
        Permission::create(['name' => 'edit_expenses']);
        Permission::create(['name' => 'delete_expenses']);

        // Payments Module Permissions
        Permission::create(['name' => 'view_payments']);
        Permission::create(['name' => 'create_payments']);
        Permission::create(['name' => 'edit_payments']);
        Permission::create(['name' => 'delete_payments']);

        // Accounts Module Permissions
        Permission::create(['name' => 'view_accounts']);
        Permission::create(['name' => 'create_accounts']);
        Permission::create(['name' => 'edit_accounts']);
        Permission::create(['name' => 'delete_accounts']);

        // Warehouse Module Permissions
        Permission::create(['name' => 'view_warehouse']);
        Permission::create(['name' => 'create_warehouse']);
        Permission::create(['name' => 'edit_warehouse']);
        Permission::create(['name' => 'delete_warehouse']);
    }
}
