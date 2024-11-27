<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left image">
        <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>{{ Auth::user()->name ?? 'Guest' }}</p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <ul class="sidebar-menu" data-widget="tree">
      <li>
        <a href="/dashboard">
          <i class="fa fa-pie-chart"></i> <span>Dashboard</span>
        </a>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-shopping-cart"></i>
          <span>Sale</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li style="padding-left: 20px;"><a href="{{ route('orders.create') }}"><i class="fa fa-dot-circle-o"></i> Add Sale Order</a></li>
          <li style="padding-left: 20px;"><a href="{{ route('orders.create-pos') }}"><i class="fa fa-dot-circle-o"></i> POS</a></li>
          <li style="padding-left: 20px;"><a href="{{ route('orders.index') }}"><i class="fa fa-dot-circle-o"></i> Sales order</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-cart-plus"></i>
          <span>Purchase</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        
          <ul class="treeview-menu">
            <li style="padding-left: 20px;"><a href="{{ route('purchases.create') }}"><i class="fa fa-dot-circle-o"></i> Add Purchase </a></li>
            <li style="padding-left: 20px;"><a href="{{ route('purchases.index') }}"><i class="fa fa-dot-circle-o"></i> Purchase Listings </a></li>
          </ul>
        
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-signal"></i>
          <span>Reports</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li style="padding-left: 20px;"><a href="/reports"><i class="fa fa-dot-circle-o"></i> View Reports</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-cubes"></i>
          <span>Products</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li style="padding-left: 20px;"><a href="/products/create"><i class="fa fa-dot-circle-o"></i> Create New Product</a></li>
          <li style="padding-left: 20px;"><a href="/products"><i class="fa fa-dot-circle-o"></i> List Products</a></li>
          <li style="padding-left: 20px;"><a href="/brand"><i class="fa fa-dot-circle-o"></i> Brands</a></li>
          <li style="padding-left: 20px;"><a href="/category"><i class="fa fa-dot-circle-o"></i> Category</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-users"></i>
          <span>People</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li style="padding-left: 20px;"><a href="/users/create"><i class="fa fa-dot-circle-o"></i> Create User</a></li>
          <li style="padding-left: 20px;"><a href="/users"><i class="fa fa-dot-circle-o"></i> User List</a></li>
          <li style="padding-left: 20px;"><a href="/customers/create"><i class="fa fa-dot-circle-o"></i> Create Customer</a></li>
          <li style="padding-left: 20px;"><a href="/customers"><i class="fa fa-dot-circle-o"></i> Customer List</a></li>
          <li style="padding-left: 20px;"><a href="/suppliers/create"><i class="fa fa-dot-circle-o"></i> Create Vendor</a></li>
          <li style="padding-left: 20px;"><a href="/suppliers"><i class="fa fa-dot-circle-o"></i> Vendor List</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-credit-card"></i>
          <span>Payments</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li style="padding-left: 20px;"><a href="/payments/create"><i class="fa fa-dot-circle-o"></i> Add Payment</a></li>
          <li style="padding-left: 20px;"><a href="/payments"><i class="fa fa-dot-circle-o"></i> Payment Listings</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-gg"></i>
          <span>Incomes</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li style="padding-left: 20px;"><a href="/income/create"><i class="fa fa-dot-circle-o"></i> Add Income</a></li>
          <li style="padding-left: 20px;"><a href="/income-head"><i class="fa fa-dot-circle-o"></i> Income Categories</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-rocket"></i>
          <span>Expenses</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li style="padding-left: 20px;"><a href="/expenses/create"><i class="fa fa-dot-circle-o"></i> Add Expense</a></li>
          <li style="padding-left: 20px;"><a href="/expenses-head"><i class="fa fa-dot-circle-o"></i> Expense Categories</a></li>
        </ul>
      </li>
    
      <li class="treeview">
        <a href="#">
          <i class="fa fa-exchange"></i>
          <span>Transfer Warehouse</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li style="padding-left: 20px;"><a href="/transfers"><i class="fa fa-dot-circle-o"></i> Transfer Lists </a></li>
          <li style="padding-left: 20px;"><a href="/transfers/create"><i class="fa fa-dot-circle-o"></i> AddTransfer</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-dollar"></i>
          <span>Currencies</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li style="padding-left: 20px;"><a href="/currencies"><i class="fa fa-dot-circle-o"></i> View Currencies</a></li>
          <li style="padding-left: 20px;"><a href="/currencies/create"><i class="fa fa-dot-circle-o"></i> Add Currency</a></li>
        </ul>
        
      </li>

      <li>
          <a href="/roles">
              <i class="fa fa-wrench"></i> <span>Assign Role / Permission</span>
          </a>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-cogs"></i>
          <span>Settings</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          
          <li style="padding-left: 20px;"><a href="/settings"><i class="fa fa-wrench"></i> General Settings</a></li>
          <li style="padding-left: 20px;"><a href="/settings/user"><i class="fa fa-dot-circle-o"></i> User Settings</a></li>
          <li style="padding-left: 20px;"><a href="/warehouses"><i class="fa fa-dot-circle-o"></i> Warehouse</a></li>
          <li style="padding-left: 20px;"><a href="/tax"><i class="fa fa-dot-circle-o"></i> Tax </a></li>

     
        </ul>
        
      </li>

      <li class="treeview">
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
      </li>

    </ul>

  </section>
</aside>
