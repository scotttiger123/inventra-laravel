@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <div class="form-border">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="container">
        <div class="items">
        <form id="orderFormPos" action="{{ route('orders.store-pos') }}" method="POST">
          <meta name="csrf-token" content="{{ csrf_token() }}">
          <div class="searches">
            
            <div class="icon-pos" data-toggle="modal" data-target="#shortcutModal">   <i class="bi bi-keyboard"></i></div>

            <div class="inputs">
                <div class="col-md-3">
                      <input type="text" 
                            list="category-names" 
                            style="width: 100%;" 
                            name="category-name" 
                            class="form-control myInput" 
                            placeholder="Select Category" 
                            tabindex="1" 
                            id="category-name-input">

                      <datalist id="category-names">
                          @foreach($categories as $category)
                              <option value="{{ $category->name }}" data-id="{{ $category->id }}">{{ $category->name }}</option>
                          @endforeach
                      </datalist>

                      <!-- Hidden field for storing category ID -->
                      <input type="hidden" name="category_id" id="category-id">
                  </div>
                  <div class="col-md-3">       
                        <input type="text" list="warehouse-names" style="width: 100%;" name="warehouse_name" class="form-control myInput" placeholder="Select Warehouse" tabindex="1" id="warehouse-name-input">
                            <datalist id="warehouse-names">
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->name }}" data-id="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </datalist>
                        <input type="hidden" name="warehouse_id" id="warehouse-id">
                    </div>      
              <div class="search-container">
                <input type="text" id="search" list = "product_name" placeholder="Search Product" />
                  <i class="bi bi-search search-icon"></i>
                  <datalist id="product_name">

                      @foreach($products as $product)
                      <option value="{{ $product->product_code }}" data-id="{{ $product->id }}">{{ $product->product_name }}</option>

                      @endforeach
                  </datalist>
                  
                  <input type="hidden" name="product_id" id="prodcut-id">
              </div>
            </div>
            <div class="icon-pos" data-toggle="modal" data-target="#othersModalLabel">
              <i class="bi bi-bounding-box"></i>
            </div>
            
          </div>
          <div class="products">
            <div class="boxes"></div>
          </div>
        </div>  
        <div class="total">
          <div class="btns">
                  <div class="input-group" style = "width:100%"> 
                      <input type="text" 
                          list="customer-names" 
                          name="customer-name" 
                          class="form-control myInput" 
                          placeholder="Select Customer" 
                          tabindex="1" 
                          id="customer-name-input-pos">

                      <datalist id="customer-names">
                          
                          @foreach($customers as $customer)
                              <option value="{{ $customer->name }}" data-id="{{ $customer->id }}">{{ $customer->name }}</option>
                          @endforeach
                      </datalist>

                      <!-- Hidden field for storing customer ID -->
                      <input type="hidden" name="customer_id" id="customer-id-pos">
                  </div>
            <button data-toggle="modal" data-target="#CreateNewCustomerModal"> <i class="bi bi-plus"></i> New Customer</button>
          </div>
         
          <div class="data"></div>
          <div class="center">
            <div class="su-container">
              <div class="t-container" style="top: 0">
                <div class="t-btn">
                  <button id="toggle-btn">
                    <i
                      class="bi bi-chevron-up"
                      id="chevron-up"
                      style="display: block; opacity: 1"
                    ></i>
                    <i
                      class="bi bi-chevron-down"
                      id="chevron-down"
                      style="display: none; opacity: 0"
                    ></i>
                  </button>
                </div>
              </div>
              <div
                class="stotal-content"
                style="overflow: hidden; height: auto; margin-top:-1rem; width:109%"
              >
                <div id="content" class="hidden"></div>
                <div class="subtotal">
                  <div class="stotal">
                    <div class="scon"><h4>SubTotal</h4></div>
                    <div class="sprice"><p id="sub-total">0</p></div>
                  </div>
                  <div class="tax">
                    <div class="tcon"><h4>Tax</h4></div>
                    <div class="tprice"><p>0</p></div>
                  </div>
                  <div class="charge">
                    <div class="tcon"><h4>Shipping Charge</h4></div>
                    <div class="tprice"><p>0</p></div>
                  </div>
                 
                </div>
              </div>
            </div>
          </div>
          <div class="grandtotal">
            <div class="gtotal">
              <div class="gcon"><h4>Total</h4></div>
              <div class="total-quantity">
                <div id="total-quantity">0</div>
                <h4>Products</h4>
              </div>
              <div class="gprice" id="grand-total">0</div>
            </div>
          </div>
                                                          

              <!-- Modal for Invoice View -->
              <div id="othersModalLabel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="othersModalLabel" aria-hidden="true">
                  <div class="modal-dialog custom-modal-width" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="otherModalLabel">Financials Section (Gross Amount, Discount, etc.)</h5>
                              <button type="button" id = 'pos-mdl-close' class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <div class="modal-body" id="">
                              <section class="">

                                  <div class="table-container">
                                      <div class="row">
                                          <div class="col-md-3 col-sm-6 col-xs-12">
                                              <div class="info-box custom">
                                                  <span class="info-box-icon" style="border-radius: 5px; background-color: gold"><i class="fa fa-percent"></i></span>
                                                  <div class="info-box-content">
                                                      <select name="tax_rate" id="tax_rate" class="form-control" tabindex="1">
                                                          <option value="" selected>Order Tax</option>
                                                          @foreach($taxes as $tax)
                                                              <option value="{{ $tax->rate }}">{{ $tax->name }} ({{ $tax->rate }}%)</option>
                                                          @endforeach
                                                      </select>
                                                      <span class="info-box-number"> TAX(%)</span>
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="col-md-3 col-sm-6 col-xs-12">
                                              <div class="info-box custom">
                                                  <span class="info-box-icon" style="border-radius: 5px; background-color: gold"><i class="fa fa-tag"></i></span>
                                                  <div class="info-box-content">
                                                      <input type="number" name="order_discount" id="order_discount_id" value="" class="form-control" tabindex="2" placeholder="Order Discount ">
                                                      <span class="info-box-number">
                                                          <label>
                                                              <input type="radio" name="order_discount_type" id="flat_discount_radio" class="flat-red" value='flat' checked> Flat 
                                                          </label> &nbsp;&nbsp;&nbsp;&nbsp;
                                                          <label>
                                                              <input type="radio" name="order_discount_type" id="percentage_discount_radio" class="flat-red" value="percentage"> Percentage 
                                                          </label>
                                                      </span>
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="col-md-3 col-sm-6 col-xs-12">
                                              <div class="info-box compact-info-box">
                                                  <span class="info-box-icon bg-grey" style="border-radius: 5px;"><i class="ion ion-pricetag"></i></span>
                                                  <div class="info-box-content">
                                                      <span class="info-box-text">Gross Amount</span>
                                                      <span class="info-box-number" id="gross_amount_label">0.00</span>
                                                      <input type="hidden" name="gross_amount" id="gross_amount_id" value="0" class="form-control" readonly>
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="col-md-3 col-sm-6 col-xs-12">
                                              <div class="info-box compact-info-box">
                                                  <span class="info-box-icon bg-grey" style="border-radius: 5px;"><i class="fa fa-credit-card"></i></span>
                                                  <div class="info-box-content">
                                                      <span class="info-box-text">Net Amount</span>
                                                      <span class="info-box-number" id="net_amount_label">0.00</span>
                                                      <input type="hidden" name="net_amount" id="net_amount_id" class="form-control" readonly>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="row">
                                          <div class="col-md-3 col-sm-6 col-xs-12">
                                              <div class="info-box custom">
                                                  <span class="info-box-icon" style="border-radius: 5px; background-color: gold"><i class="fa fa-money"></i></span>
                                                  <div class="info-box-content">
                                                      <input type="number" name="other_charges" id="other_charges_id" class="form-control" tabindex="3">
                                                      <span class="info-box-number"> OTHER CHARGES</span>
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="col-md-3 col-sm-6 col-xs-12">
                                              <div class="info-box custom">
                                                  <span class="info-box-icon" style="border-radius: 5px; background-color: gold"><i class="fa fa-dollar"></i></span>
                                                  <div class="info-box-content">
                                                      <input name="paid_amount" id="paid_amount_id" type="number" class="form-control" tabindex="4">
                                                      <span class="info-box-number"> PAID AMOUNT</span>
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="col-md-3 col-sm-6 col-xs-12">
                                              <div class="info-box compact-info-box">
                                                  <span class="info-box-icon" style="border-radius: 5px;"><i class="fa fa-usd"></i></span>
                                                  <div class="info-box-content">
                                                      <span class="info-box-text">Paid Amount</span>
                                                      <span class="info-box-number" id="paid_amount_label">0.00</span>
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="col-md-3 col-sm-6 col-xs-12">
                                              <div class="info-box compact-info-box">
                                                  <span class="info-box-icon bg-grey" style="border-radius: 5px;"><i class="fa fa-money"></i></span>
                                                  <div class="info-box-content">
                                                      <span class="info-box-text">Amount Due</span>
                                                      <span class="info-box-number" id="balance_label">0.00</span>
                                                      <input type="hidden" name="balance" id="balance_id" class="form-control" readonly>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="row">
                                          <!-- Sale Note Field -->
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                  <label for="sale_note">Sale Note</label>
                                                  <textarea name="sale_note" id="sale_note" class="form-control" rows="3" placeholder="Enter sale note" tabindex="5"></textarea>
                                              </div>
                                          </div>

                                          <!-- Staff Note Field -->
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                  <label for="staff_note">Staff Note</label>
                                                  <textarea name="staff_note" id="staff_note" class="form-control" rows="3" placeholder="Enter staff note" tabindex="6"></textarea>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                              </section>
                          </div>

                          <div class="modal-footer">
                            <div class="order">
                                <div class="p-btns">
                                  <button onclick = 'submitPosOrder()' >PLACE ORDER</button>
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal" tabindex="7">Save</button>
                                  <button onclick = 'clearFinancialFields()'></i>Clear</button>
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <!-- end modal  -->
          </form> 
          <div class="order">
            <div class="p-btns">
              <button onclick = 'submitPosOrder()' >PLACE ORDER</button>
              <button><i class="bi bi-arrow-up-left"></i></button>
              <button id = 'clearItems'><i class="bi bi-x"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
     
  </div>
  
</div>


<div class="modal fade" id="shortcutModal" tabindex="-1" aria-labelledby="shortcutModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Keyboard Shortcuts</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Action</th>
              <th>Shortcut</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Submit Order</td>
              <td><kbd>Ctrl</kbd> + <kbd>O</kbd></td>
            </tr>
            <tr>
              <td>Open Financial Modal</td>
              <td><kbd>Ctrl</kbd> + <kbd>F</kbd></td>
            </tr>
            <tr>
              <td>Reset Form</td>
              <td><kbd>Ctrl</kbd> + <kbd>R</kbd></td>
            </tr>
            <tr>
              <td>Close Modal</td>
              <td><kbd>Esc</kbd></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<style>
      .custom-modal-width {
        max-width: 90%; 
        width: auto;
    }
    .icon-pos {
        background-color: gold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .icon-pos:hover {
        background-color: black;
        color: white; 
    }
</style>
@endsection
