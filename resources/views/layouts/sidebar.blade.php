  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
          
        </div>
        <div class="pull-left info">
            <p>{{ Auth::user()->name ?? 'Guest' }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>

      </div>
      
      
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Sale</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('invoices.index') }}"><i class="fa fa-file-invoice"></i> <span>Invoices</span></a></li>
            <li><a href="{{ route('invoices.create') }}"><i class="fa fa-circle-o"></i> Create Sale Invoice</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Purchase</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> Create Sale Invoice</a></li>
            <li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> POS</a></li>
            
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Products</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="/products/create"><i class="fa fa-circle-o"></i> Create New Product </a></li>
            <li><a href="/products"><i class="fa fa-circle-o"></i> List Products </a></li>
            
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>People</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="/users/create"><i class="fa fa-circle-o"></i> Create User </a></li>
            <li><a href="/users"><i class="fa fa-circle-o"></i> User List </a></li>
            <li><a href="/customers/create"><i class="fa fa-circle-o"></i> Create Customer </a></li>
            <li><a href="/customers"><i class="fa fa-circle-o"></i> Customer List </a></li>
            <li><a href="/suppliers/create"><i class="fa fa-circle-o"></i> Create Supplier </a></li>
            <li><a href="/suppliers"><i class="fa fa-circle-o"></i> Supplier List </a></li>
            
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Payments</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="/payments/create"><i class="fa fa-circle-o"></i> Add Payment </a></li>
            <li><a href="/payments"><i class="fa fa-circle-o"></i> Payment List </a></li>
          </ul>
        </li>
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>