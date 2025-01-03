<!-- /.content-wrapper -->
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.4.0
    </div>
    <!-- <strong>Copyright &copy;  <a href="">Inventra Tech</a>.</strong> All rights
    reserved. -->
</footer>
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Morris.js charts -->
<script src="{{ asset('bower_components/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('bower_components/morris.js/morris.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap -->
<script src="{{ asset('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- Slimscroll -->
<script src="{{ asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- Menu Bar -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- DataTables -->
<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- Link to your custom JavaScript file -->
<script src="{{ asset('dist/js/product.js') }}"></script>
<script src="{{ asset('dist/js/customer.js') }}"></script>
<script src="{{ asset('dist/js/supplier.js') }}"></script>
<script src="{{ asset('dist/js/user.js') }}"></script>
<script src="{{ asset('dist/js/payment.js') }}"></script>
<script src="{{ asset('dist/js/warehouse.js') }}"></script>
<script src="{{ asset('dist/js/transfer.js') }}"></script>
<script src="{{ asset('dist/js/order.js') }}"></script>
<script src="{{ asset('dist/js/invoice.js') }}"></script>
<script src="{{ asset('dist/js/purchase.js') }}"></script>
<script src="{{ asset('dist/js/purchaseInvoice.js') }}"></script>
<script src="{{ asset('dist/js/pos.js') }}"></script>

<!-- icheck Plugins -->
<script src="../../plugins/iCheck/icheck.min.js"></script>
<!-- Select2 -->
<script src="../../bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../../plugins/input-mask/jquery.inputmask.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>


<script>
    
     //iCheck for checkbox and radio inputs
     $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    $(document).ready(function() {
    $('#loader').show(); // Show the loader
    
    $(window).on('load', function() {
        $('#loader').fadeOut(); // Hide the loader after the page is fully loaded
    });
});

      setTimeout(function() {

          var successMessage = document.getElementById('successMessage');
          var errorMessage = document.getElementById('errorMessage');
          
          if (successMessage) {
              successMessage.style.display = 'none';
          }
          
          if (errorMessage) {
              errorMessage.style.display = 'none';
          }


          
      }, 5000);


      $(document).ready(function() {
        $('#loader').show(); // Show the loader
        
        $(window).on('load', function() {
            $('#loader').fadeOut(); 
        });
        
        let totalAlertCount = 0;
        function updateLowStock() {
            

            $.ajax({
                url: '/product-quantity-alerts-json',  
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    if(response.lowStockCount>0){
                        totalAlertCount = 1;
                        $('#total_alert').text(totalAlertCount);
                        
                        $('#lowStockAlertCountHeader').text('Low Stock Items: ' + response.lowStockCount);
    
                    }else { 
                        $('#lowStockAlert').text('Low Stock products: 0');
                    }

                    
                },
                error: function(xhr, status, error) {
                    console.error('There was an error fetching low stock products:', error);
                }
            });
        }
 
        updateLowStock();


        setInterval(updateLowStock, 30000);
    });

    function toggleFullScreen() {
        if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
            // Enter fullscreen
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullscreen) {
                document.documentElement.webkitRequestFullscreen();
            } else if (document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen();
            }
        } else {
            // Exit fullscreen
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    }

    document.getElementById("fullscreenBtn").addEventListener("click", toggleFullScreen);


    
</script>
</body>
</html>
