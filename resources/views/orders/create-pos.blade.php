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
          <div class="searches">
            <div class="icon"><i class="bi bi-bounding-box"></i></div>
            
            <div class="inputs">
                <div class="input-group">
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
                      <input type="" name="category_id" id="category-id">
                  </div>
              <div class="search-container">
                <input type="text" id="search" list = "product_name" placeholder="Search Product" />
                <i class="bi bi-search search-icon"></i>
                  <datalist id="product_name">
                      @foreach($products as $product)
                      <option value="{{ $product->product_code }}" data-id="{{ $product->id }}">{{ $product->product_name }}</option>

                      @endforeach
                  </datalist>
                  
                  <input type="" name="product_id" id="prodcut-id">
              </div>
            </div>
            <div style="background-color: gold" class="icon">
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
          <div class="shipping">
            <button><i class="bi bi-bag"></i> Shipping</button>
          </div>
          <div class="data"></div>
          <div class="subtotal">
            <div class="stotal">
              <div class="scon"><h4>SubTotal</h4></div>
              <div class="sprice"><p id="sub-total">0</p></div>
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
          </form> 
          <div class="order">
            <div class="p-btns">
              <button id = 'submitPosOrder' >PLACE ORDER</button>
              <button><i class="bi bi-arrow-up-left"></i></button>
              <button><i class="bi bi-x"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
     
  </div>
</div>
@endsection
