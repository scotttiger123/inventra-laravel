<header class="main-header">
    <a href="{{ url('/dashboard') }}" class="logo">
      <img src="{{ asset('dist/img/logo.png') }}" alt="Inventra Logo" style="height: 70px; width: auto;"> 
    </a>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span> 
        </a>
        @if (Request::is('orders/create'))
            <div class="navbar-left" style="display: flex; align-items: center; padding-left: 20px;">
                <a href="#" style="padding-top:14px;font-weight: bold; font-size: 18px; color: #000000; display: inline-block;">
                    Add Sales
                </a>
            </div>
        @endif  
        @if (Request::is('purchases/create'))
            <div class="navbar-left" style="display: flex; align-items: center; padding-left: 20px;">
                <a href="#" style="padding-top:14px;font-weight: bold; font-size: 18px; color: #000000; display: inline-block;">
                    Add Purchases
                </a>
            </div>
        @endif
        @if (Request::is('orders/create-pos'))
            <div class="navbar-left" style="display: flex; align-items: center; padding-left: 20px;">
                <a href="#" style="padding-top:14px;font-weight: bold; font-size: 18px; color: #000000; display: inline-block;">
                    POS
                </a>
            </div>
        @endif
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                    <li class="dropdown notifications-menu" style = 'margin-top:13px'>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o text-black" style="color:rgb(14, 14, 13);"></i>
                                <span class="label label-danger" id ='total_alert'>0</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header"  ></li>
                            <li>
                            <ul class="menu">
                                <li>
                                    <a href="{{ route('product.quantity.alerts.index') }}">
                                        <i class="fa fa-bell text-red"></i> 
                                        <span id="lowStockAlertCountHeader" style="display: inline-block; vertical-align: middle;"></span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#"></a></li>
                        </ul>
                    </li>
                    <li class="dropdown notifications-menu" style = 'margin-top:13px' id = 'fullscreenBtn'>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa  fa-arrows-alt text-black" style="color:rgb(14, 14, 13);"></i>
                                
                            </a>
                        </li>
                        
                    </li>
                <!-- User Menu -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="user-icon-circle">
                            <i class="fa fa-user user-icon" aria-hidden="true"></i>
                        </span>
                        <!-- Display user name before the welcome message -->
                        <span class="hidden-xs" style="color: #00a65a;">{{ ucfirst(Auth::user()->name) }} - Welcome</span>
                    </a>
                    <ul class="dropdown-menu custom-dropdown-menu">
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="/settings" class="btn btn-default btn-flat">Settings</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

