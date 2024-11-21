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
        <a href="{{ route('welcome') }}">
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
          <li style="padding-left: 20px;"><a href="{{ route('orders.index') }}"><i class="fa fa-list"></i> Sale View</a></li>
          <li style="padding-left: 20px;"><a href="{{ route('orders.create') }}"><i class="fa fa-plus-circle"></i> Add Sale Order</a></li>
          <li style="padding-left: 20px;"><a href="{{ route('orders.create-pos') }}"><i class="fa fa-credit-card"></i> POS</a></li>
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
            <li style="padding-left: 20px;"><a href="{{ route('purchases.index') }}"><i class="fa fa-list"></i> Purchase Listings </a></li>
            <li style="padding-left: 20px;"><a href="{{ route('purchases.create') }}"><i class="fa fa-plus-circle"></i> Add Purchase </a></li>
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
          <li style="padding-left: 20px;"><a href="/reports"><i class="fa fa-eye"></i> View Reports</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa  fa-signal"></i>
          <span>Products</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li style="padding-left: 20px;"><a href="/products/create"><i class="fa fa-plus"></i> Create New Product</a></li>
          <li style="padding-left: 20px;"><a href="/products"><i class="fa fa-list"></i> List Products</a></li>
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
          <li style="padding-left: 20px;"><a href="/users/create"><i class="fa fa-user-plus"></i> Create User</a></li>
          <li style="padding-left: 20px;"><a href="/users"><i class="fa fa-users"></i> User List</a></li>
          <li style="padding-left: 20px;"><a href="/customers/create"><i class="fa fa-user-plus"></i> Create Customer</a></li>
          <li style="padding-left: 20px;"><a href="/customers"><i class="fa fa-users"></i> Customer List</a></li>
          <li style="padding-left: 20px;"><a href="/suppliers/create"><i class="fa fa-user-plus"></i> Create Vendors</a></li>
          <li style="padding-left: 20px;"><a href="/suppliers"><i class="fa fa-users"></i> Vendor List</a></li>
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
          <li style="padding-left: 20px;"><a href="/payments/create"><i class="fa fa-dollar-sign"></i> Add Payment</a></li>
          <li style="padding-left: 20px;"><a href="/payments"><i class="fa fa-list"></i> Payment List</a></li>
          <li style="padding-left: 20px;"><a href="/payments/transactions"><i class="fa fa-exchange-alt"></i> Payment Transactions</a></li>
        </ul>
      </li>

    </ul>

    <ul class="sidebar-menu" data-widget="tree">
      <li class="treeview">
        <a href="#">
          <i class="fa fa-exchange"></i>
          <span>Transfer Warehouse</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li style="padding-left: 20px;"><a href="/transfers"><i class="fa fa-arrow-right"></i> Transfer Lists </a></li>
          <li style="padding-left: 20px;"><a href="/transfers/create"><i class="fa fa-arrow-right"></i> AddTransfer</a></li>
        </ul>
      </li>
      
    <li class="treeview">
        <a href="#">
          <i class="fa fa-money"></i>
          <span>Currencies</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li style="padding-left: 20px;"><a href="/currencies"><i class="fa fa-list"></i> View Currencies</a></li>
          <li style="padding-left: 20px;"><a href="/currencies/create"><i class="fa fa-plus-circle"></i> Add Currency</a></li>
        </ul>
        
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
          <li style="padding-left: 20px;"><a href="/roles/create"><i class="fa fa-wrench"></i> Assign Role/Permission </a></li>
          <li style="padding-left: 20px;"><a href="/settings"><i class="fa fa-wrench"></i> General Settings</a></li>
          <li style="padding-left: 20px;"><a href="/settings/user"><i class="fa fa-user-cog"></i> User Settings</a></li>
          <li style="padding-left: 20px;"><a href="/warehouses"><i class="fa fa-arrow-right"></i> Warehouse</a></li>
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
          <li style="padding-left: 20px;"><a href="/documentation"><i class="fa fa-file-alt"></i> User Guide</a></li>
        </ul>
      </li>

    </ul>

  </section>
</aside>
