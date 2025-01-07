<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu" data-widget="tree">
      <li>
        <a href="/dashboard">
          <i class="fa fa-pie-chart"></i> <span>Dashboard</span>
        </a>
      </li>

        @php
          $hasPermission = false;
          if (Auth::user()->can('create_sale_orders') || Auth::user()->can('create_pos_orders') || Auth::user()->can('view_sale_orders')) {
            $hasPermission = true;
          }
        @endphp

        @if($hasPermission)
          <li class="treeview">
            <a href="#">
              <i class="fa fa-shopping-cart"></i>
              <span>Sale</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @can('create_sale_orders')
                <li style="padding-left: 20px;"><a href="{{ route('orders.create') }}"><i class="fa fa-dot-circle-o"></i> Add Sale Order</a></li>
              @endcan  
              @can('create_pos_orders')
                <li style="padding-left: 20px;"><a href="{{ route('orders.create-pos') }}"><i class="fa fa-dot-circle-o"></i> POS</a></li>
              @endcan  
              @can('view_sale_orders')
                <li style="padding-left: 20px;"><a href="{{ route('orders.index') }}"><i class="fa fa-dot-circle-o"></i> Sales order</a></li>
              @endcan  
            </ul>
          </li>
        @endif


        @php
          $hasPermission = false;
          if (Auth::user()->can('create_purchases') || Auth::user()->can('view_purchases')) {
            $hasPermission = true;
          }
        @endphp

        @if($hasPermission)
          <li class="treeview">
            <a href="#">
              <i class="fa fa-cart-plus"></i>
              <span>Purchase</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @can('create_purchases')
                <li style="padding-left: 20px;"><a href="{{ route('purchases.create') }}"><i class="fa fa-dot-circle-o"></i> Add Purchase</a></li>
              @endcan
              @can('view_purchases')
                <li style="padding-left: 20px;"><a href="{{ route('purchases.index') }}"><i class="fa fa-dot-circle-o"></i> Purchase Listings</a></li>
              @endcan
            </ul>
          </li>
        @endif


        @php
            $hasPermission = false;
            $permissions = [
              'view_customer_ledger',
              'view_supplier_ledger',
              'view_stock_report',
              'view_sales_report',
              'view_product_sold_report',
              'view_purchase_report',
              'view_product_purchased_report',
              'view_warehouse_report',
              'view_income_report',
              'view_expense_report',
              'view_profit_loss_report',
              'view_product_quantity_alerts'
            ];
            
            foreach ($permissions as $permission) {
              if (Auth::user()->can($permission)) {
                $hasPermission = true;
                break;
              }
            }
          @endphp

          @if($hasPermission)
            <li class="treeview">
              <a href="#">
                <i class="fa fa-signal"></i>
                <span>Reports</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                @can('view_customer_ledger')
                  <li style="padding-left: 20px;"><a href="{{ route('customer-ledger.index') }}"><i class="fa fa-dot-circle-o"></i> Customer Ledger</a></li>
                @endcan
                @can('view_supplier_ledger')
                  <li style="padding-left: 20px;"><a href="{{ route('supplier-ledger.index') }}"><i class="fa fa-dot-circle-o"></i> Supplier Ledger</a></li>
                @endcan
                @can('view_stock_report')
                  <li style="padding-left: 20px;"><a href="{{ route('stock-report-view') }}"><i class="fa fa-dot-circle-o"></i> Stock Report</a></li>
                @endcan
                @can('view_sales_report')
                  <li style="padding-left: 20px;"><a href="{{ route('orders.index') }}"><i class="fa fa-dot-circle-o"></i> Sales Report</a></li>
                @endcan
                @can('view_product_sold_report')
                  <li style="padding-left: 20px;"><a href="{{ route('reports.product-sold-report') }}"><i class="fa fa-dot-circle-o"></i> Product Sold Report</a></li>
                @endcan
                @can('view_purchase_report')
                  <li style="padding-left: 20px;"><a href="{{ route('purchases.index') }}"><i class="fa fa-dot-circle-o"></i> Purchase Report</a></li>
                @endcan
                @can('view_product_purchased_report')
                  <li style="padding-left: 20px;"><a href="{{ route('reports.product-purchased-report') }}"><i class="fa fa-dot-circle-o"></i> Product Purchased</a></li>
                @endcan
                @can('view_warehouse_report')
                  <li style="padding-left: 20px;"><a href="{{ route('reports.warehouse-report') }}"><i class="fa fa-dot-circle-o"></i> Warehouse Report</a></li>
                @endcan
                @can('view_income_report')
                  <li style="padding-left: 20px;"><a href="{{ route('income.index') }}"><i class="fa fa-dot-circle-o"></i> Income Report</a></li>
                @endcan
                @can('view_expense_report')
                  <li style="padding-left: 20px;"><a href="{{ route('expenses.index') }}"><i class="fa fa-dot-circle-o"></i> Expense Report</a></li>
                @endcan
                @can('view_profit_loss_report')
                  <li style="padding-left: 20px;"><a href="{{ route('profit.loss') }}"><i class="fa fa-dot-circle-o"></i> Profit-Loss Report</a></li>
                @endcan
                @can('view_product_quantity_alerts')
                  <li style="padding-left: 20px;"><a href="{{ route('product.quantity.alerts.index') }}"><i class="fa fa-dot-circle-o"></i> Product Quantity Alerts</a></li>
                @endcan
              </ul>
            </li>
          @endif


      <li class="treeview">
        <a href="#">
          <i class="fa fa-cubes"></i>
          <span>Products</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          @can('create_products')
              <li style="padding-left: 20px;">
                  <a href="{{ route('products.create') }}" class="{{ request()->routeIs('products.create') ? 'active' : '' }}">
                      <i class="fa fa-dot-circle-o"></i> Create New Product
                  </a>
              </li>
          @endcan
          @can('view_products')
            <li style="padding-left: 20px;"><a href="/products"><i class="fa fa-dot-circle-o"></i> List Products</a></li>
          @endcan  
          <li style="padding-left: 20px;"><a href="/brand"><i class="fa fa-dot-circle-o"></i> Brands</a></li>
          <li style="padding-left: 20px;"><a href="/category"><i class="fa fa-dot-circle-o"></i> Category</a></li>
        </ul>
      </li>

      @php
              $hasPeoplePermission = false;
              $peoplePermissions = [
                'create_users',
                'view_users',
                'create_customers',
                'view_customers',
                'create_vendors',
                'view_vendors',
                'create_sales_man',
                'view_sales_man'
              ];
              
              foreach ($peoplePermissions as $permission) {
                if (Auth::user()->can($permission)) {
                  $hasPeoplePermission = true;
                  break;
                }
              }
            @endphp

            @if($hasPeoplePermission)
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-users"></i>
                  <span>People</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  @can('create_users')
                    <li style="padding-left: 20px;"><a href="/users/create"><i class="fa fa-dot-circle-o"></i> Create User</a></li>
                  @endcan  
                  @can('view_users')
                    <li style="padding-left: 20px;"><a href="/users"><i class="fa fa-dot-circle-o"></i> User List</a></li>
                  @endcan  
                  @can('create_customers')
                    <li style="padding-left: 20px;"><a href="/customers/create"><i class="fa fa-dot-circle-o"></i> Create Customer</a></li>
                  @endcan    
                  @can('view_customers')
                    <li style="padding-left: 20px;"><a href="/customers"><i class="fa fa-dot-circle-o"></i> Customer List</a></li>
                  @endcan
                  @can('create_vendors')
                    <li style="padding-left: 20px;"><a href="/suppliers/create"><i class="fa fa-dot-circle-o"></i> Create Vendor</a></li>
                  @endcan  
                  @can('view_vendors')
                    <li style="padding-left: 20px;"><a href="/suppliers"><i class="fa fa-dot-circle-o"></i> Vendor List</a></li>
                  @endcan
                  @can('create_sales_man')
                    <li style="padding-left: 20px;"><a href="/suppliers/create"><i class="fa fa-dot-circle-o"></i> Create Sale Persons</a></li>
                  @endcan  
                  @can('view_sales_man')
                    <li style="padding-left: 20px;"><a href="/suppliers"><i class="fa fa-dot-circle-o"></i> View Sales Persons</a></li>
                  @endcan
                </ul>
              </li>
            @endif


            @php
                $hasPaymentsPermission = false;
                $paymentsPermissions = [
                  'create_payments',
                  'view_payments'
                ];

                foreach ($paymentsPermissions as $permission) {
                  if (Auth::user()->can($permission)) {
                    $hasPaymentsPermission = true;
                    break;
                  }
                }
              @endphp

              @if($hasPaymentsPermission)
                <li class="treeview">
                  <a href="#">
                    <i class="fa fa-credit-card"></i>
                    <span>Payments</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    @can('create_payments')
                      <li style="padding-left: 20px;">
                        <a href="/payments/create">
                          <i class="fa fa-dot-circle-o"></i> Add Payment
                        </a>
                      </li>
                    @endcan

                    @can('view_payments')
                      <li style="padding-left: 20px;">
                        <a href="/payments">
                          <i class="fa fa-dot-circle-o"></i> Payment Listings
                        </a>
                      </li>
                    @endcan
                  </ul>
                </li>
              @endif

              @php
            $hasIncomePermission = false;
            $incomePermissions = [
              'create_incomes',
              'view_income_category'
            ];

            foreach ($incomePermissions as $permission) {
              if (Auth::user()->can($permission)) {
                $hasIncomePermission = true;
                break;
              }
            }
          @endphp

          @if($hasIncomePermission)
            <li class="treeview">
              <a href="#">
                <i class="fa fa-gg"></i>
                <span>Incomes</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                @can('create_incomes')
                  <li style="padding-left: 20px;">
                    <a href="/income/create">
                      <i class="fa fa-dot-circle-o"></i> Add Income
                    </a>
                  </li>
                @endcan

                @can('view_income_category')
                  <li style="padding-left: 20px;">
                    <a href="/income-head">
                      <i class="fa fa-dot-circle-o"></i> Income Categories
                    </a>
                  </li>
                @endcan
              </ul>
            </li>
          @endif

          @php
                $hasExpensePermission = false;
                $expensePermissions = [
                  'create_expenses',
                  'view_expense_category'
                ];

                foreach ($expensePermissions as $permission) {
                  if (Auth::user()->can($permission)) {
                    $hasExpensePermission = true;
                    break;
                  }
                }
              @endphp

              @if($hasExpensePermission)
                <li class="treeview">
                  <a href="#">
                    <i class="fa fa-rocket"></i>
                    <span>Expenses</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    @can('create_expenses')
                      <li style="padding-left: 20px;">
                        <a href="/expenses/create">
                          <i class="fa fa-dot-circle-o"></i> Add Expense
                        </a>
                      </li>
                    @endcan

                    @can('view_expense_category')
                      <li style="padding-left: 20px;">
                        <a href="/expenses-head">
                          <i class="fa fa-dot-circle-o"></i> Expense Categories
                        </a>
                      </li>
                    @endcan
                  </ul>
                </li>
              @endif
             


              @php
                $hasAccountPermission = false;
                $accountPermissions = [
                  'view_accounts',
                  'create_account'
                ];

                foreach ($accountPermissions as $permission) {
                  if (Auth::user()->can($permission)) {
                    $hasAccountPermission = true;
                    break;
                  }
                }
              @endphp

        @if($hasAccountPermission)
          <li class="treeview">
            <a href="#">
              <i class="fa fa-book"></i>
              <span>Accounts</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @can('view_accounts')
                <li style="padding-left: 20px;">
                  <a href="/accounts">
                    <i class="fa fa-dot-circle-o"></i> Account Lists
                  </a>
                </li>
              @endcan

              @can('create_accounts')
                <li style="padding-left: 20px;">
                  <a href="/accounts/create">
                    <i class="fa fa-dot-circle-o"></i> Add Account
                  </a>
                </li>
              @endcan

              @can('view_accounts')
                <li style="padding-left: 20px;">
                  <a href="{{ route('balanceSheet.index') }}">
                    <i class="fa fa-dot-circle-o"></i> Balance Sheet
                  </a>
                </li>
              @endcan

              @can('view_accounts')
                <li style="padding-left: 20px;">
                  <a href="{{ route('accountStatement.index') }}">
                    <i class="fa fa-dot-circle-o"></i> Account Statement
                  </a>
                </li>
              @endcan
            </ul>
          </li>
        @endif


        @php
          $hasCurrenciesPermission = false;
          $currenciesPermissions = [
            'currencies',
            'currencies'
          ];

          foreach ($currenciesPermissions as $permission) {
            if (Auth::user()->can($permission)) {
              $hasCurrenciesPermission = true;
              break;
            }
          }
        @endphp

        @if($hasCurrenciesPermission)
          <li class="treeview">
            <a href="#">
              <i class="fa fa-dollar"></i>
              <span>Currencies</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @can('currencies')
                <li style="padding-left: 20px;">
                  <a href="/currencies">
                    <i class="fa fa-dot-circle-o"></i> View Currencies
                  </a>
                </li>
              @endcan

              @can('currencies')
                <li style="padding-left: 20px;">
                  <a href="/currencies/create">
                    <i class="fa fa-dot-circle-o"></i> Add Currency
                  </a>
                </li>
              @endcan
            </ul>
          </li>
        @endif

           @php
              $hasSettingsPermission = false;
              $settingsPermissions = [
                'general_settings',
                'taxes',
                'assign_roles_permissions',
                'backup_database'
              ];

              foreach ($settingsPermissions as $permission) {
                if (Auth::user()->can($permission)) {
                  $hasSettingsPermission = true;
                  break;
                }
              }
            @endphp

            @if($hasSettingsPermission)
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-cogs"></i>
                  <span>Settings</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  @can('general_settings')
                    <li style="padding-left: 20px;">
                      <a href="/settings">
                        <i class="fa fa-dot-circle-o"></i>System Settings
                      </a>
                    </li>
                  @endcan

                  @can('taxes')
                    <li style="padding-left: 20px;">
                      <a href="/tax">
                        <i class="fa fa-dot-circle-o"></i> Tax
                      </a>
                    </li>
                  @endcan

                  @can('assign_roles_permissions')
                    <li style="padding-left: 20px;">
                      <a href="/roles">
                        <i class="fa fa-wrench"></i> Assign Role / Permission
                      </a>
                    </li>
                  @endcan

                  @can('backup_database')
                    <li style="padding-left: 20px;">
                      <a href="/">
                        <i class="fa fa-dot-circle-o"></i> Backup Database
                      </a>
                    </li>
                  @endcan
                </ul>
              </li>
            @endif


      <!-- <li class="treeview">
        <a href="#">
          <i class="fa fa-book"></i>
          <span>Documentation</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li style="padding-left: 20px;"><a href="/documentation"><i class="fa fa-dot-circle-o"></i> User Guide</a></li>
        </ul>
      </li> -->

    </ul>

  </section>
</aside>
