
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Inventra</title>
  <!-- Responsive meta tag -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <!-- CSS dependencies -->
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/css/customeStyles.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css') }}">
  <link rel="stylesheet" href="{{ asset('bower_components/morris.js/morris.css') }}">
  <link rel="stylesheet" href="{{ asset('bower_components/jvectormap/jquery-jvectormap.css') }}">
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
  
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
  <link rel="stylesheet" href="../../plugins/iCheck/all.css">
  <link rel="stylesheet" href="../../dist/css/pos.css">
  <link rel="stylesheet" href="../../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"      rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  


  <!-- Custom Styles -->
  <style>
    .modal-header .modal-title { 
      color: #FF5733; 
      font-size: 20px;
      font-weight: bold;
    }
    .bold-heading {
      font-weight: bold;
    }
    .content { 
      padding-top: 20px !important;
    }
    #loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.7); 
        display: flex;
        
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

      .spinner {
        border: 16px solid #000000; /* Light grey */
        border-top: 16px solid #00a65a; /* Blue */
        border-radius: 50%;
        width: 70px;
        height: 70px;
        animation: spin 2s linear infinite;
      }

      @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
      }

  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
  <div id="loader" class="loader">
    <div class="spinner"></div>
  </div>
    
    @include('layouts.header')
    @include('layouts.sidebar')
    @yield('content')
    @include('layouts.footer')
  
</body>
</html>
