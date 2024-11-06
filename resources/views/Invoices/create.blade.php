@extends('layouts.app')

@section('content')
<style>
    .flex {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  justify-content: center;
  width: 100%;
  margin-left: -8rem;
}
.align {
  display: flex;
  gap: 10rem;
}
.hidden-xs {
  color: blue !important;
}
.gay {
  color: blue !important;
}
.main-sidebar {
  background-color: white !important;
}
.dash {
  background-color: white !important;
  color: black !important;
}
.skin-blue .sidebar a {
  color: black !important;
}
.skin-blue .sidebar a:hover {
  background-color: rgb(237, 230, 230) !important;
}
.skin-blue .sidebar-menu > li > .treeview-menu {
  margin: 0 1px;
  background: white !important;
}
.skin-blue .sidebar-menu .treeview-menu > li > a:hover {
  background-color: rgb(237, 230, 230) !important;
}
.skin-blue .sidebar-menu > li:hover > a,
.skin-blue .sidebar-menu > li.active > a,
.skin-blue .sidebar-menu > li.menu-open > a {
  background-color: white !important;
  color: black !important;
}

.skin-blue .content-header {
  display: none;
  background: transparent;
}
.content-wrapper {
  background-color: white !important;
}
.main-footer {
  background: #fff;
  padding: 15px;
  color: #444;
  border-top: 1px solid white !important;
}
.form-border {
  padding: 2rem !important;
  border: 0.2px solid #d5d6e2;
  width: 99% !important;
  border-radius: 6px;
}
.box.box-primary {
  border-top-color: white !important;
}
.form-control:not(select) {
  -webkit-appearance: none;
  border-radius: 6px !important;
  -moz-appearance: none;
  appearance: none;
}
#myInput {
  background-color: rgb(243, 243, 247) !important;
  border-color: rgb(215, 215, 226) !important;
}

/* Focused input style */
#myInput:focus {
  background-color: white !important;
  border-color: black !important;
}
.input-group-append {
  display: flex;
}
.btn-default {
  margin-left: -4px;
  background-color: #f4f4f4;
  height: 33px;
  border-color: #ddd;
  border-radius: 6px;
}
.input-group {
  width: 280px;
}
.form-control {
  border-radius: 6px !important;
  margin-left: -3px !important;
}
.search-box {
  display: flex;
}
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
body {
  margin-left: auto;
  margin-right: auto;
  padding-inline: 20px;
  margin-top: 2rem;
}
label {
  display: block; /* add this */
  padding-top: 5px;
}
/* Modal Popup Styles */
#editModal .modal-content {
  background-color: #fefefe;
  margin: auto; /* Center horizontally */
  position: fixed; /* Position fixed to center vertically */
  top: 20%; /* Place at 50% from the top */
  left: 50%; /* Place at 50% from the left */
  transform: translate(
    -50%,
    -50%
  ); /* Translate back by half of its own width and height */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Adjust width as needed */
  max-width: 600px; /* Optional: Set maximum width */
  border-radius: 5px;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
}

#editModal .modal-content {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 50%; /* Could be more or less, depending on screen size */
  border-radius: 5px;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
}

#editModal .close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

#editModal .close:hover,
#editModal .close:focus {
  color: black;
  text-decoration: none;
}

#editModal label {
  font-weight: bold;
}

#editModal input[type="text"] {
  width: calc(100% - 12px); /* Adjust based on padding */
  padding: 6px;
  margin: 8px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

#editModal button {
  background-color: #4caf50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 10px;
}

#editModal button:hover {
  background-color: #45a049;
}

#editModal .cancelBtn {
  background-color: #f44336;
}

#editModal .cancelBtn:hover {
  background-color: #da190b;
}

.rounded-border {
  margin-top: 10px;
  display: inline-block;
  padding: 5px 10px;
  border: 1px solid #ccc;
  border-radius: 20px;
  margin-right: 10px;
  background-color: #f0f0f0; /* Adjust the color as needed */
  color: #333; /* Adjust text color */
  font-weight: bold; /* Make text bold */
}
/* Style for the back button */
#backButton {
  background-color: transparent;
  border: none;
  color: #333; /* Change color as needed */
  cursor: pointer;
  font-size: 16px; /* Adjust font size as needed */
  padding: 5px 10px; /* Adjust padding as needed */
  transition: all 0.3s ease;
}

#backButton:hover {
  color: #666; /* Change hover color as needed */
}

/* Adjust the alignment of the elements */
.logo-lg {
  display: inline-flex;
  align-items: center;
}

.logo-lg button {
  margin-right: 10px; /* Adjust margin as needed */
}

.logo-lg b {
  margin-left: 10px; /* Adjust margin as needed */
}

.myInput {
  background-color: rgb(243, 243, 247) !important;
  border-color: rgb(215, 215, 226) !important;
  border-radius: 6px;
}

.myInput:focus {
  background-color: white !important;
  border-color: black !important;
}
.col-md-1 input {
  width: 170px;
}
.row {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.col-md-2c {
  margin-top: 5px;
  margin-left: 1.5rem;
}
.gap {
  display: flex;
  flex-direction: column;
  gap: 2px;
}
.col-sm-3 button {
  border-radius: 6px !important;
}
.col-sm-3 {
  margin-left: 0rem;
}
.col-sm-3 button {
  width: 6vw;
}
.col-xs-1 input {
  width: 105px;
}

.col-md-1 {
  margin-top: 6px;
}
.tab {
  border-radius: 6px !important;
  margin-top: 4rem;
  width: 100%;
  height: 400px;
  overflow-y: scroll;
  background-color: rgb(243, 243, 247) !important;
}
.top {
  margin-top: 4rem;
  margin-left: -0.7rem;
}
.bg-black {
  background-color: #111 !important;
  border-radius: 6px !important;
}
.col-md-12 {
  border-radius: 6px !important;
}
.input-group .input-group-addon {
  border-radius: 0;
  border-color: #d2d6de;
  background-color: #fff;
  height: 35px;
}
.row3 {
  display: flex;
  flex-direction: column;
  gap: 6px;
  flex-wrap: wrap;
  margin-top: 6px;
  margin-left: -12rem;
}
.col-md-3 i {
  color: blue;
  background-color: white;
  font-weight: 400;
}

.col-md-3c {
  text-align: center;
  display: flex;
  margin-left: -6rem;
  align-items: center;
  margin-top: 15px;
}
.input-group .input-group-addon {
  border-radius: 0;
  border-color: #d2d6de;
  background-color: #fff;
  height: 0rem;
}
.row1 {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-top: 1.5rem;
}
@media screen and (max-width: 1290px) {
  .wrap {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    place-items: center;
  }
  .col-xs-2 {
    width: 20vw;
  }
  .row1 {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    margin-left: -1.2rem;
  }
  .row3 {
    margin-left: -1.5rem;
  }
  .col-sm-3 button {
    width: 10vw;
  }
  .row {
    margin-top: 1rem;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    flex-direction: column;
    margin-left: 8rem;
  }
}
@media screen and (max-width: 1130px) {
  .wrap {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    place-items: center;
  }
  .col-xs-2 {
    width: 30vw;
  }
  .gap {
    display: flex;
    flex-wrap: wrap;
    gap: 7rem;
  }
}
@media screen and (max-width: 768px) {
  .flex {
    display: flex;
    margin-left: auto;
    margin-right: auto;
  }
  .col-sm-3 button {
    width: 15vw;
  }
  .col-md-2c {
    margin-top: -6rem;
  }
  .tab {
    border-radius: 6px !important;
    margin-top: 4rem;
    width: 100%;
    height: 300px;
    overflow-y: scroll;
    background-color: rgb(243, 243, 247) !important;
  }
}
@media screen and (max-width: 575px) {
  .wrap {
    display: flex;
    flex-direction: column;
  }
  .col-xs-2 {
    width: 50vw;
  }
  .col-sm-3 button {
    width: 18vw;
  }
  .col-xs-1 input {
    width: 90px;
  }
  .tab {
    border-radius: 6px !important;
    margin-top: 4rem;
    width: 100%;
    height: 200px;
    overflow-y: scroll;
    background-color: rgb(243, 243, 247) !important;
  }
}
</style>

    <div class="flex">
                 <div class="row1">
					  
                  <div class="col-md-2">
								<input style="height: 32.5px; width: 285px;" type = "text"  list="order_status_list" name="room" id="order_status" class="form-control myInput"  value = '' placeholder = 'CLIENT-NAME' >
									<datalist id="order_status_list">
										<option value="UBAID"   > </option>
									
																						
									</datalist>	
						</div>
						<div class="col-md-2">
							<input style="width: 285px;" type="text"  id ='cell_no_for_invoice'  class="form-control myInput" placeholder = 'CELL #' >				
						</div>
						<div class="col-md-3">
							<input type="text"  id ='address'  class="form-control myInput" placeholder = 'Address' style="width: 285px;" >				
						</div>
						<div class="col-md-2">
								<script src="build/build/jquery.datetimepicker.full.js"></script>
	<script src="build/build/DateTime.js"></script>
	<link rel="stylesheet" type="text/css" href="./jquery.datetimepicker.css"/>								<div class="input-group">
									<div class="input-group-addon"  onclick ='GetItems(this.value)' data-toggle="modal" data-target="#" ><i class="fa fa-search" ></i>  </div>
										<input type = "text"  list="orderList" name=""  class="form-control myInput"  placeholder = 'Invoice NO'   tabindex = '1'  id ='order_id' onKeydown="Javascript: if (event.keyCode==13) GetItems(this.value);" style="width: 208px;"> 
											<datalist id="orderList" >
										
																							</datalist>		
												<div onclick ='GetOrderDetails()' class="input-group-addon"  data-toggle="modal" data-target="#" ><i class="fa fa-edit" ></i>  </div>				
											
								</div>
						</div>
						<br>
						<br>
						
						<div class="col-md-3" hidden>
						    
							<div class="input-group" style="margin-top:5px;">
								<div class="input-group-addon"  data-toggle="modal" data-target="#RoomWindowModel" ><i style="width:13px;" onKeydown = "Javascript: if (event.keyCode==13) IOData();" class="fa fa-plus"  ></i>  </div>
								<input type = "text"  list="roomlist" name="room" id="RoomID" class="form-control myInput" style="margin-top:px;" placeholder = 'ROOM' tabindex = '1' > 
									<datalist id="roomlist" style="background-color:red;">
											<option value="" ></option>
												<option value="DRAWING ROOM" >	DRAWING ROOM</option>
												<option value="DINNING ROOM" >	DINNING ROOM</option>
												<option value="TV LOUNGE" >		TV LOUNGE</option>
												<option value="GUEST ROOM" >	GUEST ROOM</option>
												<option value="KITCHEN ROOM" >	KITCHEN ROOM</option>
												<option value="ROOM#1" >		ROOM#1  </option>
												<option value="ROOM#2" >		ROOM#2  </option>
												<option value="ROOM#3" >		ROOM#3  </option>	
												<option value="BED ROOM" >	BED ROOM	</option>
									</datalist>	
							</div>
							
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
						<div class="input-group" style="margin-top:5px;">
								<div class="input-group-addon"  data-toggle="modal" data-target="#AddProductDefi" > <i class="fa fa-barcode"></i> </div>
								<!-- onKeydown = "Javascript: if (event.keyCode==13) IOData(); " --> <!-- solved from on change state first get product defintion then IOdata();-->
			  
								 <input type = "text"  list = 'browsersD' class="form-control myInput" value ='' placeholder ='PRODUCT CODE/NAME'  style="width:94%; height : 32px;color:black; text-align:left;"   onKeydown="Javascript: if (event.keyCode==13) laod_prod_defi(); " name = 'Product' id ='PId' tabindex="1" onFocus = "this.style.background = '';"   />
									<datalist id="browsersD" >
										
									</datalist>									
						</div>
						</div>					
					
						<div class="gap">
              <div class="align">
						<div class="col-xs-1" >
						   <input type="text" class="form-control myInput" id ='discount_percentage' onKeydown = "Javascript: if (event.keyCode==13) IOData();" placeholder = "DISCOUNT %" tabindex = '2' style="margin-top:5px; width: 130px;" >
							
						</div>
						<div class="col-xs-1" >
						   <input type="text" id ='net_id' class="form-control myInput" name =''  onKeydown = "Javascript: if (event.keyCode==13) IOData();" placeholder="NET RATE" tabindex = '2' style="margin-top:5px; width: 130px;" >
							
						</div>
						</div>
					
					<div class="col-md-3" style="margin-top:5px; width: 296px;">
					  <input type="text" id ='remarks_id' class="form-control myInput" name =''  placeholder="REMARKS"  tabindex ='9' ><!--pending-->
					</div>	
						
						</div>
						
					</div>
					
					<div class="row3">
					<div class="col-md-2c">
	
							<input style="width: 170px;" type="datetime-local" id="datetimepicker_dark1" class="form-control myInput" tabindex="1">

										
						</div>
						<div class="col-md-2" style="margin-top:5px; width: 200px;">
								<input type = "text"  list="order_status_list" name="room" id="order_status" class="form-control myInput"  value = '' placeholder = 'BILL STATUS' >
									<datalist id="order_status_list" style="background-color:red;">
										<option value="Return"   >	Return </option>
										<option value="Quotation" >	Quotation</option>
										<option value="Hold" >	Hold</option>
																						
									</datalist>	
						</div>
            	<div class="col-md-1">
							<input type = "text"  list="salestitlelist" name=""  id="Salesman" class="form-control myInput"  placeholder = 'SALES MANAGER' style="margin-top:0px;" tabindex = '1' > 
										<datalist id="salestitlelist" style="background-color:red;">
											<option value="" ></option>
																						<option value="UBAID" >UBAID</option>
																				
											
										</datalist>	
										
						</div>
						
                         <div class="col-md-3c" style="margin-top: 15px; display: inline-block;" id="exitWarehouseDiv">
                            <input type="checkbox" id="directExitFromWareHouse" name="directExitFromWareHouse" checked> &nbsp;&nbsp;&nbsp;Exit Warehouse
                        </div>
                        <div class="col-md-3" style="margin-top: 15px; display: none;" id="inwardWarehouseDiv">
                            <input type="checkbox" id="directInwardWareHouse" name="inwardWareHouse"> &nbsp;&nbsp;&nbsp;Inward Warehouse
                        </div>

	
						
						<div class="col-md-3" style="margin-top:5px;">
								 
								<div class="col-sm-3" style="padding: 0;">
									   <i style = 'display:none' id = 'spinner' class="fa fa-refresh fa-spin"></i>
									   <button type="button" class="btn btn-success btn-block btn-flat"  id="SavePrint">Save &nbsp;<i class="fa fa-save"></i></button> 
								</div>
								
								
							
								
								
								
							
						</div>
						
					</div>
          </div>
          <div class="tab">
            
          </div>
          <div class="top">
            <div class="wrap">
            <div class=" col-xs-2">
          <!-- small box -->
          <div class="small-box bg-black">
            <div class="inner">
              <h3  >
				<input class="" id ='GTotal' type="text" value ='0' style='margin-top:px;border:0;background-color: transparent; cursor:pointer;color:white;font-size:24px'  class="form-control" name =''  onchange ='' placeholder=""  style="" >
			
			  </h3>
				
              <p>GROSS AMOUNT</p>
            </div>
            <div class="icon">
             
            </div>
           
          </div>
        </div>
		<div class=" col-xs-2">
          <!-- small box -->
          <div class="small-box bg-black">
            <div class="inner">
            <h3 > 
			<input name="" type="text" id="direct_discount_id"  value ='' style='margin-top:px;border:1;background-color: transparent; cursor:pointer;color:white;font-size:20px' onkeyup ='direct_discount()' class="pa form-control kb-pad amount"/>
			<input name="" id="direct_discount_static_value"  style ='color : red'  hidden > <!-- pending for next -->
			</h3>
					<style>
							#block_container	{
								/text-align:center;/
							}
							#bloc1, #bloc2		{
								display:inline;
							}
					</style>
              <p  id = 'block_container'> <p id = 'bloc1'> DISCOUNT </p> <div id="bloc2"> </div>    </p>
            </div>
            <div class="icon">
            <!--  <i class="ion ion-person-add"></i>-->
            </div>
            
          </div>
        </div>
		 <div class=" col-xs-2" hidden>
          <!-- small box -->
          <div class="small-box bg-black" hidden>
            <div class="inner">
            <h3 > 
			<input name="" type="text" id="BiDisc"  value ='' style='margin-top:px;border:1;background-color: transparent; cursor:pointer;color:white;font-size:20px' onkeyup ='direct_discount()' class="pa form-control kb-pad amount"/>
			<input name="" id="BillDiscForStaticValue"  style ='color : red'   hidden > <!-- further disc  -->
			</h3>

              <p>FURTHER DISCOUNT</p>
            </div>
            <div class="icon">
            <!--  <i class="ion ion-person-add"></i>-->
            </div>
            
          </div>
        </div>

		<div class=" col-xs-2">
          <!-- small box -->
          <div class="small-box bg-black">
            <div class="inner">
              <h3 id =''><input name=""  value ='' style='margin-top:0px;border:1;background-color: transparent; cursor:pointer;color:white;font-size:20px' type="text" id="OtherCharges" onkeyup ='direct_discount()' class="pa form-control kb-pad amount"/>
              </h3>
				<p>OTHER CHARGES</p>
            </div>
           
            
          </div>
        </div>
		 <div class=" col-xs-2">
          <!-- small box -->
          <div class="small-box bg-black">
            <div class="inner">
              <h3>
				<input id ='NeAmount_id' type="text" value ='' style='margin-top:0px;border:0;background-color: transparent; cursor:pointer;color:white;font-size:20px'  class="form-control" name =''  onkeyup ='' placeholder="" style="" >
			  </h3>
			  <p>NET AMOUNT</p>
			  
            </div>
            <div class="icon">
            </div>
           </div>
		    <input id = 'net_amt_static' type="text"  hidden>
        </div>
		
		 <div class=" col-xs-2">
          <!-- small box -->
          <div class="small-box bg-black" >
            <div class="inner">
              <h3 id ='PaidAmountDiv'> 
			  <div class="input-groupp">
				<!--<div class="input-group-addon"  data-toggle="modal" data-target="#PaidAmount" id="payment" > <i class="fa fa-plus" ></i>  </div> -->
					<input name="amount" id="Paid_Amount_Id"  style='margin-top:0px;border:1;background-color: transparent; cursor:pointer;color:white;font-size:20px' type="text" onkeyup ='direct_discount()' class="pa form-control kb-pad amount"/>
				    <input name="" id="PaidAmountForStaticValue"  style ='color : red' hidden> <!-- this is for later paid amount to have a static value-->
				</div>
			  </h3>
			<!-- <button type="button" class="btn btn-success btn-block btn-flat" data-toggle="modal" data-target="#PaidAmount" id="payment" style="height:67px;">Payment</button>-->
                                            
              <p>PAID AMT</p>
            </div>
            <div class="icon">
             <!--<i class="ion ion-pie-graph"></i>-->
            </div>
          
          </div>
        </div>
		<div class=" col-xs-2">
          <!-- small box -->
          <div class="small-box bg-black">
            <div class="inner">
              <h3 >
				<input name="" id ='Balance_id' style='margin-top:0px;border:0;background-color: transparent; cursor:pointer;color:white;font-size:20px' type="text"  class="pa form-control kb-pad amount"/>
              </h3>
			  <p>BALANCE</p>
			  
            </div>
            <div class="icon">
             <!-- <i class="ion ion-pie-graph"></i>-->
            </div>
            
          </div>
        </div>
        </div>
		
</div>

    <!-- end main  -->
    </div>
</div>
@endsection
