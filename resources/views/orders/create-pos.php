<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>POS</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
       
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="https://salepropos.com/demo/vendor/bootstrap/css/bootstrap.min.css" type="text/css">
    <link rel="preload" href="https://salepropos.com/demo/vendor/bootstrap/css/bootstrap-datepicker.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://salepropos.com/demo/vendor/bootstrap/css/bootstrap-datepicker.min.css" rel="stylesheet"></noscript>
    <link rel="preload" href="https://salepropos.com/demo/vendor/bootstrap/css/awesome-bootstrap-checkbox.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://salepropos.com/demo/vendor/bootstrap/css/awesome-bootstrap-checkbox.css" rel="stylesheet"></noscript>
    <link rel="preload" href="https://salepropos.com/demo/vendor/bootstrap/css/bootstrap-select.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://salepropos.com/demo/vendor/bootstrap/css/bootstrap-select.min.css" rel="stylesheet"></noscript>
    <!-- Font Awesome CSS-->
    <link rel="preload" href="https://salepropos.com/demo/vendor/font-awesome/css/font-awesome.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://salepropos.com/demo/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet"></noscript>
    <!-- Drip icon font-->
    <link rel="preload" href="https://salepropos.com/demo/vendor/dripicons/webfont.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://salepropos.com/demo/vendor/dripicons/webfont.css" rel="stylesheet"></noscript>
    <!-- Custom Scrollbar-->
    <link rel="preload" href="https://salepropos.com/demo/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://salepropos.com/demo/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css" rel="stylesheet"></noscript>
    <!-- virtual keybord stylesheet-->
    <link rel="preload" href="https://salepropos.com/demo/vendor/keyboard/css/keyboard.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://salepropos.com/demo/vendor/keyboard/css/keyboard.css" rel="stylesheet"></noscript>
    <link rel="stylesheet" href="https://salepropos.com/demo/css/style.default.css" id="theme-stylesheet" type="text/css">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="https://salepropos.com/demo/css/custom-default.css" type="text/css" id="custom-style">
        <!-- Google fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="POS.css">
    <link rel="stylesheet" href="path/to/dripicons.css">

  </head>
  <body class="pos-page">
      <div id="content">
          <section id="pos-layout" class="forms pos-section hidden-print">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7 pos-form">
                <form method="POST" action="https://salepropos.com/demo/sales" accept-charset="UTF-8" class="payment-form" enctype="multipart/form-data"><input name="_token" type="hidden" value="kU7cULuFTifbUJPGoOFCdhT49ubxRJnAE1EZAGS7">
                                <div class="row">
                    <div class="col-md-11 col-12">
                        <div class="row">
                           <div class="col-md-3 col-6">
    <div class="form-group top-fields">
        <label>Date</label>
        <div class="input-group">
            <input type="text" name="created_at" class="form-control date" value="06-11-2024" onkeyup='saveValue(this);' />
        </div>
    </div>
</div>
                            <div class="col-md-3 col-6">
                                <div class="form-group top-fields">
                                                                        <input type="hidden" name="warehouse_id_hidden" value="1">
                                                                        <label>Warehouse</label>
                                    <select required id="warehouse_id" name="warehouse_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select warehouse...">
                                                                                <option value="1">Shop 1</option>
                                                                                <option value="2">Shop 2</option>
                                                                            </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="form-group top-fields">
                                                                        <input type="hidden" name="biller_id_hidden" value="1">
                                                                        <label>Biller</label>
                                    <select required id="biller_id" name="biller_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select Biller...">
                                                                                <option value="1">John Watson (The solution)</option>
                                                                            </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="form-group top-fields">
                                                                        <input type="hidden" name="customer_id_hidden" value="2">
                                                                        <label>Customer</label>
                                    <div class="input-group pos">
                                        <select required name="customer_id" id="customer_id" class="selectpicker form-control" data-live-search="true" title="Select customer..." style="width: 100px">
                                                                                                                                                                                        <option value="1">James Bond (313131)</option>
                                                                                                                                            <option value="2">Walk in Customer (231313)</option>
                                                                                                                                            <option value="4">bkk (87897)</option>
                                                                                    </select>
                                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addCustomer"><i class="dripicons-plus"></i></button>
                                       
                                                                            </div>
                                </div>
                            </div>
                            <div class="col-12 pl-0 pr-0">
                        <div class="search-box form-group mb-2">
                            <input style="border: 1px solid #7c5cc4;" type="text" name="product_code_name" id="lims_productcodeSearch" placeholder="Scan/Search product by name/code" class="form-control" />
                        </div>
                    </div>
                        </div>
                    </div>
                    
                </div>
                <div>
                    
                             
                    <div class="table-responsive transaction-list">
                        <table id="myTable" class="table table-hover table-striped order-list table-fixed">
                            <thead class="d-none d-md-block">
                                <tr>
                                    <th class="col-sm-5 col-6">Product</th>
                                    <th class="col-sm-2">Price</th>
                                    <th class="col-sm-3">Quantity</th>
                                    <th class="col-sm-2">SubTotal</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-id">
                                                                                                                                                            </tbody>
                        </table>
                    </div>
                   
                    <div class="col-12 totals" style="background-color:#f5f6f7;border-top: 2px solid #ebe9f1;padding-bottom: 7px;padding-top: 7px;">
                        <div class="row">
                            <div class="col-sm-4 col-6">
                                <strong class="totals-title">Items</strong><strong id="item">0 (0)</strong>
                            </div>
                            <div class="col-sm-4 col-6">
                                <strong class="totals-title">Total</strong><strong id="subtotal"> 0.00 </strong>
                            </div>
                            <div class="col-sm-4 col-6">
                                <strong class="totals-title">Discount <button type="button" class="btn btn-link btn-sm" data-toggle="modal" data-target="#order-discount-modal"> <i class="dripicons-document-edit"></i></button></strong><strong id="discount"> 0.00 </strong>
                            </div>
                            <div class="col-sm-4 col-6">
                                <strong class="totals-title">Coupon <button type="button" class="btn btn-link btn-sm" data-toggle="modal" data-target="#coupon-modal"><i class="dripicons-document-edit"></i></button></strong><strong id="coupon-text"> 0.00 </strong>
                            </div>
                            <div class="col-sm-4 col-6">
                                <strong class="totals-title">Tax <button type="button" class="btn btn-link btn-sm" data-toggle="modal" data-target="#order-tax"><i class="dripicons-document-edit"></i></button></strong><strong id="tax"> 0.00 </strong>
                            </div>
                            <div class="col-sm-4 col-6">
                                <strong class="totals-title">Shipping <button type="button" class="btn btn-link btn-sm" data-toggle="modal" data-target="#shipping-cost-modal"><i class="dripicons-document-edit"></i></button></strong><strong id="shipping-cost"> 0.00 </strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="payment-amount d-none d-md-block">
                    <h2>Grand Total <span id="grand-total"> 0.00 </span></h2>
                </div>
                      <div class="payment-options">
                    <div class="column-5 more-payment-options">
                        <div class="btn-group dropup">
                            <button type="button" class="btn btn-primary btn-custom  dropdown-toggle d-md-none" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-cube"></i> Pay <span id="grand-total-m"></span>
                            </button>
                            <div class="">
                                                                <div class="column-5">
                                    <button style="background: #0984e3" type="button" class="btn btn-sm btn-custom payment-btn" data-toggle="modal" data-target="#add-payment" id="credit-card-btn"><i class="fa fa-credit-card"></i> Card</button>
                                </div>
                                                                                                <div class="column-5">
                                    <button style="background: #00cec9" type="button" class="btn btn-sm btn-custom payment-btn" data-toggle="modal" data-target="#add-payment" id="cash-btn"><i class="fa fa-money"></i> Cash</button>
                                </div>
                                                                <div class="column-5">
                                    <button style="background: #010429" type="button" class="btn btn-sm btn-custom payment-btn" data-toggle="modal" data-target="#add-payment" id="multiple-payment-btn"><i class="fa fa-money"></i> Multiple Payment</button>
                                </div>
                                
                                                                <div class="column-5">
                                    <button style="background-color: #fd7272" type="button" class="btn btn-sm btn-block btn-custom payment-btn" data-toggle="modal" data-target="#add-payment" id="cheque-btn"><i class="fa fa-money"></i> Cheque</button>
                                </div>
                                                                                                <div class="column-5">
                                    <button style="background-color: #5f27cd" type="button" class="btn btn-sm btn-block btn-custom payment-btn" data-toggle="modal" data-target="#add-payment" id="gift-card-btn"><i class="fa fa-credit-card-alt"></i> Gift Card</button>
                                </div>
                                                                                                <div class="column-5">
                                    <button style="background-color: #b33771" type="button" class="btn btn-sm btn-block btn-custom payment-btn" id="deposit-btn"><i class="fa fa-university"></i> Deposit</button>
                                </div>
                                                                                                <div class="column-5">
                                    <button style="background-color: #319398" type="button" class="btn btn-sm btn-block btn-custom payment-btn" data-toggle="modal" data-target="#add-payment" id="point-btn"><i class="dripicons-rocket"></i> Points</button>
                                </div>
                                                            </div>
                        </div>
                    </div>
                    <div class="column-5">
                        <button style="background-color: #e28d02" type="button" class="btn btn-sm btn-custom" id="draft-btn"><i class="dripicons-flag"></i> Draft</button>
                    </div>
                    <div class="column-5">
                        <button style="background-color: #d63031;" type="button" class="btn btn-sm btn-custom" id="cancel-btn" onclick="return confirmCancel()"><i class="fa fa-close"></i> Cancel</button>
                    </div>
                    <div class="column-5">
                        <button style="background-color: #ffc107;" type="button" class="btn btn-sm btn-custom" data-toggle="modal" data-target="#recentTransaction"><i class="dripicons-clock"></i> Recent Transaction</button>
                    </div>
                </div> 
                </form>    
            </div>
        </div>
    </div>
</section>
      </div>   
        <script type="text/javascript" src="https://salepropos.com/demo/vendor/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="https://salepropos.com/demo/vendor/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://salepropos.com/demo/vendor/jquery/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="https://salepropos.com/demo/vendor/jquery/jquery.timepicker.min.js"></script>
    <script type="text/javascript" src="https://salepropos.com/demo/vendor/popper.js/umd/popper.min.js">
    </script>
    <script type="text/javascript" src="https://salepropos.com/demo/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://salepropos.com/demo/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
    <script type="text/javascript" src="https://salepropos.com/demo/vendor/bootstrap/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="https://salepropos.com/demo/vendor/keyboard/js/jquery.keyboard.js"></script>
    <script type="text/javascript" src="https://salepropos.com/demo/vendor/keyboard/js/jquery.keyboard.extension-autocomplete.js"></script>
    <script type="text/javascript" src="https://salepropos.com/demo/js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script type="text/javascript" src="https://salepropos.com/demo/vendor/jquery.cookie/jquery.cookie.js">
    </script>
    <script type="text/javascript" src="https://salepropos.com/demo/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script type="text/javascript" src="https://salepropos.com/demo/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
          <script type="text/javascript" src="https://salepropos.com/demo/js/front.js"></script>
        <script type="text/javascript" src="https://salepropos.com/demo/vendor/daterange/js/moment.min.js"></script>
    <script type="text/javascript" src="https://salepropos.com/demo/vendor/daterange/js/knockout-3.4.2.js"></script>
    <script type="text/javascript" src="https://salepropos.com/demo/vendor/daterange/js/daterangepicker.min.js"></script>
 <script>
    $(document).ready(function(){
        $('.date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });
    });
</script>
  </body>
</html>
  </body>
</html>
<style>
    body {
  font-family: "Inter", sans-serif;
}
.bootstrap-select-sm .btn {
  font-size: 13px;
  padding: 3px 25px 3px 10px;
  height: 30px !important;
}
.minus,
.plus {
  padding: 0.35rem 0.75rem;
}
.numkey.qty {
  font-size: 13px;
  padding: 0 0;
  max-width: 50px;
  text-align: center;
}
.sub-total {
  font-weight: 500;
}
.pos-page .container-fluid {
  padding: 0 15px;
}
.pos-page .side-navbar {
  top: 0;
}
section.pos-section {
  padding: 10px 0;
}
.pos-page .table-fixed {
  margin-bottom: 0;
}
.pos-text {
  line-height: 1.8;
}
.pos-page section header {
  padding: 0 0 5px;
}
.pos .bootstrap-select button {
  padding-right: 21px !important;
}
.pos .bootstrap-select.form-control:not([class*="col-"]) {
  width: 100px;
}
.pos-page .order-list .btn {
  padding: 2px 5px;
}
.pos-page [class="row"] {
  margin-left: -10px;
  margin-right: -10px;
}
.pos-page [class*="col-"] {
  padding: 0 10px;
}
.pos-page #myTable [class*="col-"] {
  padding: 0.5rem;
}
.pos-page #myTable tr th {
  background: #f5f6f7;
  color: #5e5873;
}
.product-btns {
  margin: 5px -5px;
}
.edit-product {
  white-space: break-spaces;
  font-size: 13px;
  font-weight: 500;
  text-align: left;
  padding: 0 0 !important;
}
.edit-product i {
  color: #00cec9;
}
.product-title span {
  font-size: 12px;
}
.more-options {
  box-shadow: -5px 0px 10px 0px rgba(44, 44, 44, 0.3);
  font-size: 12px;
  margin: 10px 0;
  padding-left: 3px;
  padding-right: 3px;
}
label {
  font-size: 13px;
}
#tbody-id tr td {
  font-size: 13px;
  padding: 0;
}
table,
tr,
td {
  border-collapse: collapse;
}
.top-fields {
  margin-top: 10px;
  position: relative;
}
.top-fields label {
  background: #fff;
  font-size: 11px;
  margin-left: 10px;
  padding: 0 3px;
  position: absolute;
  top: -8px;
  z-index: 9;
}
.top-fields input,
.top-fields .btn {
  font-size: 13px;
  height: 37px;
}
.product-grid {
  display: flex;
  flex-wrap: wrap;
  padding: 0;
  margin: 0;
  width: 100%;
}
.product-grid > div {
  border: 1px solid #e4e6fc;
  overflow: hidden;
  padding: 0.5rem;
  position: relative;
  max-width: 300px;
  min-width: 100px;
  vertical-align: top;
  width: calc(100% / 4);
}
.product-grid > div p {
  color: #5e5873;
  font-size: 12px;
  font-weight: 500;
  margin: 10px 0 0;
  min-height: 36px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  overflow: hidden;
  text-overflow: ellipsis;
  -webkit-box-orient: vertical;
}
.product-grid > div span {
  font-size: 12px;
}
.more-payment-options.column-5 {
  margin: 0;
  padding: 0;
}
.ui-helper-hidden-accessible {
  display: none;
}
#print-layout {
  padding: 0 0;
  margin: 0 0;
}
.category-img p,
.brand-img p {
  color: #5e5873;
  font-size: 12px;
  font-weight: 500;
}
.brand-img,
.category-img {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.brand-img img {
  max-width: 70%;
}
.load-more {
  margin-top: 15px;
}
.load-more:disabled {
  opacity: 0.5;
}
.ui-helper-hidden-accessible {
  display: none !important;
}
@media (max-width: 500px) {
  .product-grid > div {
    width: calc(100% / 3);
  }
}
@media (max-width: 375px) {
  .product-grid > div {
    width: calc(100% / 2);
  }
}
@media all and (max-width: 767px) {
  section.pos-section {
    padding: 0 5px;
  }
  nav.navbar {
    margin: 0 -10px;
  }
  .pos-form {
    padding: 0 0 !important;
  }
  .payment-options {
    padding: 5px 0;
  }
  .payment-options .column-5 {
    margin: 5px 0;
  }
  .payment-options .btn-sm {
    font-size: 12px;
  }
  .more-payment-options,
  .more-payment-options .btn-group {
    width: 100%;
  }
  .more-payment-options.column-5 {
    padding: 0 5px;
  }
  .product-btns {
    margin: 0 -15px 10px -15px;
  }
  .product-btns .btn {
    font-size: 12px;
  }
  .more-options {
    margin-top: 0;
  }
  .transaction-list {
    height: 35vh;
  }
  .filter-window {
    position: fixed;
  }
}

@media print {
  .hidden-print {
    display: none !important;
  }
}

#print-layout * {
  font-size: 10px;
  line-height: 20px;
  font-family: "Ubuntu", sans-serif;
  text-transform: capitalize;
}
#print-layout .btn {
  padding: 7px 10px;
  text-decoration: none;
  border: none;
  display: block;
  text-align: center;
  margin: 7px;
  cursor: pointer;
}

#print-layout .btn-info {
  background-color: #999;
  color: #fff;
}

#print-layout .btn-primary {
  background-color: #6449e7;
  color: #fff;
  width: 100%;
}
#print-layout td,
#print-layout th,
#print-layout tr,
#print-layout table {
  border-collapse: collapse;
}
#print-layout tr {
  border-bottom: 1px dotted #ddd;
}
#print-layout td,
#print-layout th {
  padding: 7px 0;
  width: 50%;
}

#print-layout table {
  width: 100%;
}

#print-layout .centered {
  display: block;
  text-align: center;
  align-content: center;
}
#print-layout small {
  font-size: 10px;
}
@media print {
  #print-layout * {
    font-size: 10px !important;
    line-height: 20px;
  }
  #print-layout table {
    width: 100%;
    margin: 0 0;
  }
  #print-layout td,
  #print-layout th {
    padding: 5px 0;
  }
}
</style>