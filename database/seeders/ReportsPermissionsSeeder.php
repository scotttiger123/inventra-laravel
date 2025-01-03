<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class ReportsPermissionsSeeder extends Seeder
{
    public function run()
    {
        // List of permissions for the "Reports" module
        $permissions = [
            'view_customer_ledger' => 'Customer Ledger',
            'view_supplier_ledger' => 'Supplier Ledger',
            'view_stock_report' => 'Stock Report',
            'view_sales_report' => 'Sales Report',
            'view_product_sold_report' => 'Product Sold Report',
            'view_purchase_report' => 'Purchase Report',
            'view_product_purchased_report' => 'Product Purchased Report',
            'view_warehouse_report' => 'Warehouse Report',
            'view_income_report' => 'Income Report',
            'view_expense_report' => 'Expense Report',
            'view_profit_loss_report' => 'Profit-Loss Report',
            'view_product_quantity_alerts' => 'Product Quantity Alerts',
        ];

        // Create the permissions for the "Reports" module
        foreach ($permissions as $slug => $name) {
            Permission::create([
                'name' => $slug,
                'module_name' => 'reports',  // Module name
                'guard_name' => 'web',
            ]);
        }
    }
}
