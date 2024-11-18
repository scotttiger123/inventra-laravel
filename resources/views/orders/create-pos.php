
<!DOCTYPE html>
<html lang="en">
  <head>
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
.flex {
  display: flex;
}
.tab {
  height: 750px;
  width: 490px;
}
.scroll {
  overflow-y: auto;
  max-height: 470px;
  float: right;
  margin-top: -38rem;
}
.col-sm-4 {
  background-color: rgb(189, 228, 189);
  padding: 1rem;
  text-align: center;
  color: black;
  font-size: 3rem;
}
img {
  width: 65px;
  height: 65px;
  margin-left: auto;
  margin-right: auto;
  display: flex;
  flex-direction: column;
}
td img {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 5px 10px;
  margin-top: -2rem;
}
.home {
  display: flex;
  align-items: center;
  position: absolute;
  right: 0;
  gap: 1rem;
  color: green;
  padding-inline: 3.6rem;
  margin-top: -10.2rem;
}

.home i {
  font-size: 20px;
}
.home button {
  padding: 4px 10px;
  border: 1px solid green;
  background-color: white;
  border-radius: 6px;
  transition: all 0.2s;
}

.home button:hover {
  background-color: green;
  color: white;
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
  border-radius: 6px !important;
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
.btns {
  display: flex;
  align-items: center;
  position: absolute;
  margin-top: -5.5rem;
  gap: 10px;
}
.btns button:nth-child(1) {
  background-color: #5a2d82;
  color: #ffffff;
}

.btns button:nth-child(2) {
  background-color: #ff7f50;
  color: #ffffff;
}

.btns button:nth-child(3) {
  background-color: #ff4757;
  color: #ffffff;
}
.btns button {
  padding: 8px 52px;
  border-radius: 6px;
  color: white;
  border: none;
  font-weight: 500;
  font-size: 16px;
  transition: filter 0.3s ease;
}

.btns button:hover {
  filter: brightness(90%);
}

.fix {
  position: absolute;
  width: 60%;
  bottom: 5;
}
.delete-btn {
  background-color: crimson;
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  transition: all 0.2s;
}
.delete-btn:hover {
  background-color: rgb(186, 5, 41);
}
.table-responsive {
  overflow-y: auto;
  width: 60%;
  height: 390px;
}
.paybtns {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-left: -1.5rem;
  padding-block: 1rem;
  width: 100%;
  z-index: 10;
  margin-top: 7rem;
}
.paybtns button {
  padding: 6px 13px;
  border-radius: 6px;
  border: none;
  display: flex;
  gap: 5px;
  font-weight: 400;
  font-size: 1.7rem;
  color: white;
}
.paybtns button:nth-child(1) {
  background-color: #99998a;
}
.paybtns button:nth-child(2) {
  background-color: #2a88b3;
}
.paybtns button:nth-child(3) {
  background-color: #b38a00;
}
.paybtns button:nth-child(4) {
  background-color: #2a8052;
}
.paybtns button:nth-child(5) {
  background-color: #b3589e;
}
.paybtns button:nth-child(6) {
  background-color: #7f7f52;
}
.paybtns button:nth-child(7) {
  background-color: #7f29b3;
}
.paybtns button:nth-child(8) {
  background-color: #b35200;
}
.paybtns button:nth-child(9) {
  background-color: #b32a2a;
}
.paybtns button:nth-child(10) {
  background-color: #5277b3;
}
.summary {
  width: 60%;
  background-color: rgb(233, 232, 240);
  padding: 2px 10px;
}
.scontent {
  display: flex;
  justify-content: space-between;
}
.scontent p {
  font-size: 1.8rem;
}
.scontent span {
  margin-left: 2rem;
}
.left p {
  padding-right: 12rem;
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
    padding: 5px 0;
  }
}
@media (min-width: 992px) {
  .col-md-7 {
    width: 100%;
  }
  #tbody-id tr td {
    font-size: 16px;
    padding: 0;
  }
}
@media (max-width: 1200px) {
  .responsive {
    display: flex;
    flex-direction: column-reverse;
  }
  .scroll {
    overflow-y: auto;
    max-height: 500px;
    float: right;
    margin-top: 6rem;
  }
  .table-responsive {
    overflow-y: auto;
    width: 100%;
    margin-top: 4rem;
    height: 400px;
  }
  .paybtns {
    display: flex;
    flex-wrap: wrap;
  }
}
@media (max-width: 540px) {
  .tab {
    width: 350px;
    height: 850px;
  }
  .table-responsive {
    overflow-y: auto;
    width: 100%;
    margin-top: 4rem;
    height: 400px;
  }
  .totals {
    width: 95vw;
  }
  .btns {
    justify-content: center;
    display: flex;
    align-items: center;
    position: absolute;
    gap: 10px;
  }
  .btns button {
    padding: 10px 34px;
    border-radius: 6px;
    color: white;
    border: none;
    font-weight: 300;
    font-size: 14px;
  }
  #tbody-id tr td {
    font-size: 10px;
    padding: 0;
  }
  .col-sm-4 {
    font-size: 2rem;
  }
}
@media (max-width: 1000px) {
  .home {
    margin-top: -28.2rem;
  }
}

    </style>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>POS</title>
    <meta
      content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
      name="viewport"
    />
    <link rel="stylesheet" href="in.css" />
    <link
      rel="stylesheet"
      href="../../bower_components/bootstrap/dist/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

   <link rel="stylesheet" href="in.css">
    <link
      rel="stylesheet"
      href="../../bower_components/font-awesome/css/font-awesome.min.css"
    />

    <link
      rel="stylesheet"
      href="../../bower_components/Ionicons/css/ionicons.min.css"
    />
    <link
      rel="stylesheet"
      href="../../bower_components/bootstrap-daterangepicker/daterangepicker.css"
    />
    <link
      rel="stylesheet"
      href="../../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css"
    />
    <link rel="stylesheet" href="../../plugins/iCheck/all.css" />
    <link
      rel="stylesheet"
      href="../../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css"
    />
    <link
      rel="stylesheet"
      href="../../plugins/timepicker/bootstrap-timepicker.min.css"
    />

    <link
      rel="stylesheet"
      href="../../bower_components/select2/dist/css/select2.min.css"
    />
    <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css" />
    <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css" />

    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"
    />
  </head>
  <body>
    <div id="content">
      <section id="pos-layout" class="forms pos-section hidden-print">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-7 pos-form">
              <form
                method="POST"
                action="https://salepropos.com/demo/sales"
                accept-charset="UTF-8"
                class="payment-form"
                enctype="multipart/form-data"
              >
                <input
                  name="_token"
                  type="hidden"
                  value="kU7cULuFTifbUJPGoOFCdhT49ubxRJnAE1EZAGS7"
                />
                <div class="row">
                  <div class="col-md-11 col-12">
                    <div class="row" style="width: 60%;">
                      <div class="col-md-3 col-6">
                        <div class="form-group top-fields">
                          <label>Date</label>
                          <div class="input-group">
                            <input
                              type="date"
                              name="created_at"
                              class="form-control date"
                            />
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3 col-6">
                        <div class="form-group top-fields">
                          <input
                            type="hidden"
                            name="warehouse_id_hidden"
                            value="1"
                          />
                          <label>Warehouse</label>
                          <select
                            required
                            id="warehouse_id"
                            name="warehouse_id"
                            class="selectpicker form-control"
                            data-live-search="true"
                            data-live-search-style="begins"
                            title="Select warehouse..."
                            style="border-radius: 6px;"
                          >
                            <option value="1">Shop 1</option>
                            <option value="2">Shop 2</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3 col-6">
                        <div class="form-group top-fields">
                          <input
                            type="hidden"
                            name="biller_id_hidden"
                            value="1"
                          />
                          <label>Biller</label>
                          <select
                            style="width: 125px; border-radius: 6px;"
                            required
                            id="biller_id"
                            name="biller_id"
                            class="selectpicker form-control"
                            data-live-search="true"
                            data-live-search-style="begins"
                            title="Select Biller..."
                          >
                            <option value="1">
                              John Watson (The solution)
                            </option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3 col-6">
                        <div class="flex">
                        <div class="form-group top-fields">
                          <input
                            type="hidden"
                            name="customer_id_hidden"
                            value="2"
                          />
                          <label>Customer</label>
                          <div class="input-group pos">
                            <select
                              required
                              name="customer_id"
                              id="customer_id"
                              class="selectpicker form-control"
                              data-live-search="true"
                              title="Select customer..."
                              style="width: 120px; border-radius: 6px;"
                            >
                              <option value="1">James Bond (313131)</option>
                              <option value="2">
                                Walk in Customer (231313)
                              </option>
                              <option value="4">bkk (87897)</option>
                            </select>
                          </div>
                        </div>
            
                        </div>
                      </div>
                      <div class="col-12 pl-0 pr-0">
                        <div class="form-group" style="margin-left: 1.5rem">
                          <label></label>
                          <select
                            class="form-control select2"
                            style="width: 100%; border-radius: 6px;"
                          >
                            <option selected="selected">Alabama</option>
                            <option>Alaska</option>
                            <option disabled="disabled">
                              California (disabled)
                            </option>
                            <option>Delaware</option>
                            <option>Tennessee</option>
                            <option>Texas</option>
                            <option>Washington</option>
                          </select>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                </div>
                <div>
 <div class="home">
                          <button><i class="bi bi-house"></i></button>
                          <button style="display: flex; align-items: center; gap: 4px;"><i class="bi bi-person-circle"></i>Admin</button>
                        </div>
                        
                  <div class="responsive">
         <div class="table-responsive transaction-list">
    <table id="myTable" class="table table-hover table-striped order-list table-fixed"
        style="color: rgb(33, 65, 65); background-color: rgb(235, 235, 243); width: 98%; border-collapse: collapse;">
        <thead class="d-none d-md-block">
            <tr>
                <th class="col-sm-5 col-6">Product</th>
                <th class="col-sm-2" style="text-align: center;">Price</th>
                <th class="col-sm-3" style="text-align: center;">Quantity</th>
                <th class="col-sm-2" style="text-align: center;">SubTotal</th>
            </tr>
        </thead>
        <tbody style="background-color: rgb(242, 245, 248);" id="tbody-id"></tbody>
    </table>
</div>

<div class="scroll">
  
  <div class="btns">
        <button>Category</button>
        <button>Brand</button>
        <button>Featured</button>
    </div>
    <table border="1" style=" cursor: pointer; border-color: rgb(223, 211, 211); border-collapse: collapse" class="tab">
        <tr style="font-size: 14px; text-align: center; ">
            <td class="product-cell" data-product="Laptop 2024"><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.20</p></td>
            
                 <td class="product-cell" data-product="Laptop 2024"><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.300</p></td>
                 <td class="product-cell" data-product="Laptop 2024"><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.2000</p></td>
                 <td class="product-cell" data-product="Laptop 2024" d><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.5</p></td>
        </tr>
        
         <tr style="font-size: 14px; text-align: center; ">
            <td class="product-cell" data-product="Laptop 2024"><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.20</p></td>
            
                 <td class="product-cell" data-product="Laptop 2024"><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.300</p></td>
                 <td class="product-cell" data-product="Laptop 2024"><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.2000</p></td>
                 <td class="product-cell" data-product="Laptop 2024" d><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.5000</p></td>
        </tr>
         <tr style="font-size: 14px; text-align: center; ">
            <td class="product-cell" data-product="Laptop 2024"><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.20</p></td>
            
                 <td class="product-cell" data-product="Laptop 2024"><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.300</p></td>
                 <td class="product-cell" data-product="Laptop 2024"><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.2000</p></td>
                 <td class="product-cell" data-product="Laptop 2024" d><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.5000</p></td>
        </tr>
         <tr style="font-size: 14px; text-align: center; ">
            <td class="product-cell" data-product="Laptop 2024"><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.20</p></td>
            
                 <td class="product-cell" data-product="Laptop 2024"><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.300</p></td>
                 <td class="product-cell" data-product="Laptop 2024"><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.200</p></td>
                 <td class="product-cell" data-product="Laptop 2024" d><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.4000</p></td>
        </tr>
         <tr style="font-size: 14px; text-align: center; ">
            <td class="product-cell" data-product="Laptop 2024"><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.120</p></td>
            
                 <td class="product-cell" data-product="Laptop 2024"><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.340</p></td>
                 <td class="product-cell" data-product="Laptop 2024"><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.2100</p></td>
                 <td class="product-cell" data-product="Laptop 2024" d><img src="lapt.jpg" alt="" />Laptop
                2024<p>Rs.500</p></td>
        </tr>
    </table>
</div>
</div>
<div class="summary">
  <div class="scontent">
    <p>Items <span>0</span></p>
    <p style="margin-left: 2.5rem;">Total <span>0.00</span></p>
    <div class="left">
     <p>Discount <span>0</span></p>
     </div>
  </div>
   <div class="scontent">
    <p>Coupon <span>0</span></p>
    <p style="margin-left: -2rem;" >Tax <span>0</span></p>
     <div class="left">
     <p>Shipping <span>0</span></p>
     </div>
  </div>
</div>

 <div style="width: 60%;" class="col-sm-4 col-6"><strong class="totals-title">Grand Total </strong><strong id="grand-total">0.00</strong>
            </div>
<div class="fix" style="padding-inline: 1.3rem">
       <div class="paybtns">
      <button><i class="bi bi-credit-card"></i>Card</button>
      <button><i class="bi bi-cash"></i>Cash</button>
      <button><i class="bi bi-wallet"></i>Multiple&nbsp;Payment</button>
      <button><i class="bi bi-cash"></i>Cheque</button>
      <button><i class="bi bi-credit-card-fill"></i>Gift&nbsp;Card</button>
      <button><i class="bi bi-bank2"></i>Deposit</button>
      <button><i class="bi bi-rocket-takeoff"></i>Points</button>
      <button><i class="bi bi-flag"></i>Draft</button>
      <button><i class="bi bi-x-circle"></i>Cancel</button>
      <button><i class="bi bi-clock-history"></i>Recent&nbsp;Transaction</button>
    </div>
    
</div>
          </div>
        </div>
      </section>
    </div>

<script>
    document.querySelectorAll('.product-cell').forEach(cell => {
        const priceText = cell.querySelector('p').innerText;
        const priceValue = priceText.replace('Rs.', '').trim(); // Remove 'Rs.' and any spaces
        cell.setAttribute('data-price', priceValue); // Set data-price to the cleaned-up price value
    });
  document.querySelectorAll('.product-cell').forEach(cell => {
    cell.addEventListener('click', function() {
        // Get the product details from the clicked cell
        const productName = this.getAttribute('data-product');
        const productPrice = parseFloat(this.getAttribute('data-price'));
        const quantity = 1;
        const subtotal = productPrice * quantity;
        
        // Create a new row to add to the table
        const newRow = document.createElement('tr');
        
       newRow.innerHTML = `
    <td style="text-align: left; vertical-align: middle; padding: 8px;">${productName}</td>

    <td style="text-align: center; vertical-align: middle; padding: 8px;">${productPrice.toFixed(2)}</td>
    <td style="text-align: center; vertical-align: middle; padding: 8px;">
        <div style="display: flex; align-items: center; justify-content: center;">
            <button type="button" class="quantity-btn" data-action="decrease" style="padding: 0px 10px; font-size: 24px; border:none; background-color: rgb(212, 230, 227); border-radius:6px">-</button>
         <input type="text" class="quantity-input" value="${quantity}" min="1" style="width: 50px; text-align: center; padding: 5px; margin: 0 5px; font-size: 16px; border: 1px solid lightblue;">

            <button type="button" class="quantity-btn" data-action="increase" style="padding: 0px 10px; font-size: 24px; border:none; background-color: rgb(212, 230, 227);border-radius:6px" >+</button>
        </div>
    </td>
    <td class="subtotal" style="text-align: center; vertical-align: middle; padding: 8px;">${subtotal.toFixed(2)}</td>
    <td style="text-align: center; vertical-align: middle; padding: 8px;">
        <button class="delete-btn" style="padding: 4px 6px; font-size: 16px;border-radius:6px"><i class="bi bi-trash3"></i></button>
    </td>
`;
   
        // Append the new row to the table body
        const tbody = document.getElementById('tbody-id');
        tbody.appendChild(newRow);
        
        // Add event listener to the delete button
        const deleteButton = newRow.querySelector('.delete-btn');
        deleteButton.addEventListener('click', function() {
            // Remove the row when the delete button is clicked
            tbody.removeChild(newRow);
            // Update the grand total after deleting the row
            updateGrandTotal();
            updateTotals();
        });
        
        // Add event listeners to the quantity buttons
        const quantityInput = newRow.querySelector('.quantity-input');
        const increaseBtn = newRow.querySelector('[data-action="increase"]');
        const decreaseBtn = newRow.querySelector('[data-action="decrease"]');
        
        increaseBtn.addEventListener('click', function(event) {
            event.preventDefault();  // Prevent the default action of the button
            let currentQuantity = parseInt(quantityInput.value);
            quantityInput.value = currentQuantity + 1;
            updateSubtotalAndTotals();
        });

        decreaseBtn.addEventListener('click', function(event) {
            event.preventDefault();  // Prevent the default action of the button
            let currentQuantity = parseInt(quantityInput.value);
            if (currentQuantity > 1) {
                quantityInput.value = currentQuantity - 1;
                updateSubtotalAndTotals();
            }
        });

        // Add event listener to quantity input to update subtotal
        quantityInput.addEventListener('input', function() {
            updateSubtotalAndTotals();
        });

        // Update the grand total and totals after adding a new product
        updateGrandTotal();
        updateTotals();
    });
});

function updateSubtotalAndTotals() {
    const rows = document.querySelectorAll('#tbody-id tr');
    rows.forEach(row => {
        const price = parseFloat(row.cells[1].textContent);
        const quantity = parseInt(row.querySelector('.quantity-input').value);
        const subtotal = price * quantity;
        row.querySelector('.subtotal').textContent = subtotal.toFixed(2);
    });

    updateGrandTotal();
    updateTotals();
}

function updateGrandTotal() {
    const rows = document.querySelectorAll('#tbody-id tr');
    let grandTotal = 0;

    rows.forEach(row => {
        const subtotal = parseFloat(row.querySelector('.subtotal').textContent);
        grandTotal += subtotal;
    });

    // Update the grand total
    document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
}

function updateTotals() {
    const rows = document.querySelectorAll('#tbody-id tr');
    const itemCount = rows.length;
    let total = 0;

    rows.forEach(row => {
        const price = parseFloat(row.cells[1].textContent);
        const quantity = parseInt(row.querySelector('.quantity-input').value);
        total += price * quantity;
    });

    document.getElementById('item').textContent = `${itemCount} (${total.toFixed(2)})`;
    document.getElementById('subtotal').textContent = total.toFixed(2);
}

function updateGrandTotal() {
    const rows = document.querySelectorAll('#tbody-id tr');
    let grandTotal = 0;

    rows.forEach(row => {
        const subtotal = parseFloat(row.querySelector('.subtotal').textContent);
        grandTotal += subtotal;
    });

    // Update the grand total
    document.querySelector('.summary .scontent p span').textContent = grandTotal.toFixed(2);
}

function updateTotals() {
    const rows = document.querySelectorAll('#tbody-id tr');
    const itemCount = rows.length;
    let total = 0;

    rows.forEach(row => {
        const price = parseFloat(row.cells[1].textContent);
        const quantity = parseInt(row.querySelector('.quantity-input').value);
        total += price * quantity;
    });

    // Update the Items and Total in summary
    document.querySelector('.summary .scontent p span').textContent = itemCount;
    document.querySelector('.summary .scontent p:nth-child(2) span').textContent = total.toFixed(2);
}

document.querySelectorAll('.product-cell').forEach(cell => {
    cell.addEventListener('click', function() {
        // Get product details and add the product to the table as already implemented
        // (code for adding the row here...)

        // Call update functions to refresh summary
        updateGrandTotal();
        updateTotals();
    });
});

// When the delete button is clicked
const deleteButton = newRow.querySelector('.delete-btn');
deleteButton.addEventListener('click', function() {
    // Remove the row when the delete button is clicked
    tbody.removeChild(newRow);

    // Update totals after deleting the row
    updateGrandTotal();
    updateTotals();
});

increaseBtn.addEventListener('click', function(event) {
    event.preventDefault();  // Prevent the default action
    let currentQuantity = parseInt(quantityInput.value);
    quantityInput.value = currentQuantity + 1;

    // Update totals after quantity change
    updateSubtotalAndTotals();
    updateGrandTotal();
    updateTotals();
});

decreaseBtn.addEventListener('click', function(event) {
    event.preventDefault();  // Prevent the default action
    let currentQuantity = parseInt(quantityInput.value);
    if (currentQuantity > 1) {
        quantityInput.value = currentQuantity - 1;

        // Update totals after quantity change
        updateSubtotalAndTotals();
        updateGrandTotal();
        updateTotals();
    }
});
function updateGrandTotal() {
    const rows = document.querySelectorAll('#tbody-id tr');
    let grandTotal = 0;

    rows.forEach(row => {
        const subtotal = parseFloat(row.querySelector('.subtotal').textContent);
        grandTotal += subtotal;
    });

    // Update the grand total
    document.getElementById('grand-total').textContent = grandTotal.toFixed(2); // Ensure the grand total is updated
}


</script>
  </body>
</html>
