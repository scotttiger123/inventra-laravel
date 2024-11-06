 function printData()
{
  $("#ForPirnt").load("PHP/POSPrint.php");
   document.getElementById('ForPirnt').style.display = 'Block';
 
  $("#PrintReceiptVal").html($("#Paid_Amount_Id").val());
   var divToPrint = document.getElementById("ForPirnt");
   newWin= window.open("");
  
   newWin.document.write(divToPrint.outerHTML);
   newWin.print();
   newWin.close();
   document.getElementById('ForPirnt').style.display = 'none'; 
   
}

		
function GetItems(DSLValueTest,Extra){
			
			var lfckv = document.getElementById("letter_head").checked;
			var DSLValue = $("#order_id").val();
			myWindow = window.open("PHP/GetSaleItems.php?order_id="+DSLValue+"&^%^$@*^^%@1&&%%^*&*2345@="+Extra+"&checkbox="+lfckv, '', 'width=600,height=300,scrollbars=1','location=no','menubar=0');
			myWindow.focus();
		
	}
function view_client_details(DSLValueTest,Extra){
			
			var DSLValue = $("#client_id").val();
			myWindow = window.open("PHP/view_client_details.php?client_id="+DSLValue+"&^%^$@*^^%@1&&%%^*&*2345@="+Extra, '', 'width=600,height=300,scrollbars=1','location=no','menubar=0');
			myWindow.focus();
		
	}	
$("#push_online").click(function () {
	
			var r = confirm(" are You sure to upload  ? ");
				if (r == true) {
					$("#spinner_push_online").show();
					$("#push_online").hide();
							$.ajax({
									type:"POST",
										data:{
										'OrderNo':$("#OrderNo").val()
									},
								dataType:'text',
								url:"./Controller/push_online.php",
								success:function(dta){	
									alert(dta);
									$("#spinner_push_online").hide();
									$("#push_online").show();
								}
							
							});
				}	
	});

 $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

  })
  //get prodcut definition
 

$("#PId").change(function(){
	  
	  
	  // load from invoice.php
	  // get last rate of specific client  for product 
	  // get latest definition of product for specific user 
	  // this module is for client request by mbengineering .customer want to get auto fetch price of which last time he enter for his client 
	  for (var c=0; c < order_items.length; c++){
	    var client_name = $("#Client").val().trim();
	    var order_items_client_name = order_items[c].client_name;
	    var pname = $("#PId").val();    
        var order_items_product_name = order_items[c].product_name;
	           
	            if(order_items_client_name !== null) { //for null client values 
	    
                        if (order_items_client_name.toLowerCase().trim() == client_name.toLowerCase().trim() && order_items_product_name.toLowerCase().trim() == pname.toLowerCase().trim())	{
                                           
                                       $('.last_price').html(order_items[c].rate);
                                       document.getElementById("Rate_id").value = order_items[c].rate;
                                       document.getElementById("uom_id").value 	= order_items[c].uom;
                               		   
                	}
	            }
	      
	    }
	  
	  for (var c=0; c < ProductDefi.length; c++){
        		  
        		  
        		  //console.log(c);
        		   // console.log($("#PId").val());
        		    	
        				
                        var pname = $("#PId").val();    
                        var product_def_product_name = ProductDefi[c].product_name;
        						
        						if (product_def_product_name.toLowerCase() == pname.toLowerCase())	{
        			                
        			                	
        				            //	console.log(pname);
        				            //	console.log(ProductDefi[c].product_name);
        						   	// document.getElementById("Rate_id").value 	 		= ProductDefi[c].Price;  // onclient request  last rate given to client is show in default input feild 
        							document.getElementById("category").value 	 		= ProductDefi[c].Category_id;
        							//document.getElementById("uom_id").value 	 		= ProductDefi[c].uom; // on client request last UOM enter would show not the default one 
        							//document.getElementById("qty_id").value 	 		= '1';
        							
        							$('#avbl_stock').html(ProductDefi[c].stock);
        						}
                                	
					}
				
			
        });

 
  function laod_prod_defi()
	{
			for (var c=0; c < ProductDefi.length; c++){
		  
			//alert(ProductDefi[c].product_name);
						if (ProductDefi[c].product_name == $("#PId").val())	{
							document.getElementById("Rate_id").value 	 		= ProductDefi[c].Price;
							document.getElementById("category").value 	 		= ProductDefi[c].Category_id;
							document.getElementById("uom_id").value 	 		= ProductDefi[c].uom;
							document.getElementById("qty_id").value 	 		= '1';
							
							$('#avbl_stock').html(ProductDefi[c].stock);
						}

					}
		IOData();//after this insert data 			
	}
$("#PostWindows").click(function () {
	
			$.ajax({
			type:"POST",
			data:{'window_detail':$("#window_detail").val()
			,'Height':$("#Height").val()
			,'Width':$("#Width").val()
			,'Description':$("#Description").val()
			,'RoomID':$("#RoomID").val()
			,'OrderNo':$("#order_id").val()
			},
			dataType:'text',
			url:"PHP/window_post.php",
			success:function(dta)
			{	
				alert(dta);
				
				$('#window_detail').val("");
				$('#Height').val("");
				$('#Width').val("");
				$('#Description').val("");
				$("#InvResponse").load("PHP/POSPHP.php");
				$("#ForPirnt").load("PHP/POSPrint.php");
				
				
				//$("#dailySaleId").load("PHP/SaleHistorySevenDays.php");
				//$("#DailyHistoryPupId").click();
			}
 
		});
	
	});


		$("#DailyHistory").click(function () {
	
			$.ajax({
			type:"POST",
			data:{
			},
			dataType:'text',
			url:"PHP/SaleHistorySevenDays.php",
			success:function(dta)
			{	
				//alert(dta);
				$("#dailySaleId").load("PHP/SaleHistorySevenDays.php");
				$("#DailyHistoryPupId").click();
			}
 
		});
	
	});
	
	
		
		$("#ProductCode").change(function () {
			alert();
			  //global array loaded at page loaded ProductDefi
			  for (var c=0; c < ProductDefi.length; c++){
								if (ProductDefi[c].product_id == $("#ProductCode").val())	{
									document.getElementById("NewProductName").value = ProductDefi[c].product_name;
									document.getElementById("NewProductPrice").value = ProductDefi[c].Price;
									document.getElementById("NewProductPurchasePrice").value = ProductDefi[c].purchase_rate;
									document.getElementById("NewProductDiscount").value = ProductDefi[c].discount;
								}

							}
				
        });	

			$("#Cell").change(function () {
	  
	            for (var c=0; c < CustomerData.length; c++){
						if (CustomerData[c].contact == $("#Cell").val())	{
							$("#CustomerName").val(CustomerData[c].clientname);
							$("#Cell").val(CustomerData[c].contact);
							$("#ResidentNo").val(CustomerData[c].ResidentNo);
							$("#Address").val(CustomerData[c].address);
							$("#Country").val(CustomerData[c].Country);
							$("#City").val(CustomerData[c].City);
							$("#Email").val(CustomerData[c].Email);
							
						}

					}
				
                });
                
                function getClientBalanceById(selectedClientId) {
                    selectedClientId = parseInt(selectedClientId)
                      var client = CustomerBalances.find(function(client) {
                        
                        return client.id === selectedClientId;
                      });
                    
                    
                      return client ? client.balance : undefined;
                    }
                
                $("#Client").change(function () {
	            
	            for (var c=0; c < CustomerData.length; c++){
	                
						if (CustomerData[c].clientname == $("#Client").val())	{
						    
						    
							$("#cell_no_for_invoice").val(CustomerData[c].contact);
							$("#address").val(CustomerData[c].address);
							$("#client_id").val(CustomerData[c].client_id);
							console.log(CustomerData[c].client_id);
							document.getElementById("cust_balance").textContent = getClientBalanceById(CustomerData[c].client_id); 	
						    
						}

					}
					
					document.getElementById('browsersD').innerHTML = ''; // unset feild to get latest data on top
						for (var c=0; c < order_items_group_by_products.length; c++){
						    //console.log(order_items[c].product_name);
						    
						    
					         if (order_items_group_by_products[c].client_name.toLowerCase().trim() == ($("#Client").val().toLowerCase().trim() ) )	{
					             
					            $('#browsersD').append("<option value='"+order_items_group_by_products[c].product_name+"'>"+order_items_group_by_products[c].product_name+ "</option>")
					      } 
			    		}
				    $('#browsersD').append("<option value='-------'></option>")
					product_combo_list();//then load again 
				
                });
                
		
		 $("#id_close").click(function () {
			location.reload(); 
			 
		 });
		 /*
		 $("#CustDiaClose").click(function () {
			location.reload(); 
			 
		 });
		 */
   //input data
   
    function getProductId() {
    var productName = $("#PId").val();
    var productInfo = {};

    for (var c = 0; c < ProductDefi.length; c++) {
        if (ProductDefi[c].product_name === productName) {
            productInfo.product_id = ProductDefi[c].product_id;
            productInfo.sr = ProductDefi[c].sr;
            break;
        }
    }

    if (Object.keys(productInfo).length === 0) {
        // No product matched the provided name, trigger alert
        alert("No product found with the provided name.");
    }

    return productInfo;
}

   
   
   
  var GTotal ;
  function IOData()
	{
	    
	    //Get prodcut serial No / Id 
	    var productData = getProductId();
        var productId = productData.product_id;
        var serialNumber = productData.sr; // id of product table 
	    
	//	alert($("#Client").val());
		// var PVal = $('#PId').find(":selected").text();this select the text of combo box 
		//var PVal = $("#PId option:selected").val();
		var PVal = $("#PId").val();
		var Qty  = $('input[name="Qty"]').val();
		
		
		
		    if ( $("#qty_id").val() == "" || $("#qty_id").val() < 1 ) { 
	    
	            alert('please add qty.');
                return false;
	    
	        }
	        
	       if ( $("#Rate_id").val() == "" || $("#Rate_id").val() < 1 ) { 
	    
	            alert('please add price .');
                return false;
	    
	        }
	        
	        if ( $("#uom_id").val() == ""  ) { 
	    
	            alert('please add UOM .');
                return false;
	    
	        }
	        
	     var dateString = document.getElementById('datetimepicker_dark1').value;
		var date = dateString;
        date = date.split('/');
		/*
        var myDate = new Date(dateString);
        
        const Myyyy = myDate.getFullYear();
        let Mmm = myDate.getMonth() + 1; // Months start at 0!
        let Mdd = myDate.getDate();
        
        if (Mdd < 10) Mdd = '0' + Mdd;
        if (Mmm < 10) Mmm = '0' + Mmm;
        
         myDate =  Mmm + '/' + Mdd + '/' + Myyyy; // js pick date from inpute feild Month first then date . so i change here month to day . in formate . 

        //var today = new Date();
        */
        var  today = new Date();
        const yyyy = today.getFullYear();
        let mm = today.getMonth() + 1; // Months start at 0!
        let dd = today.getDate();
        
        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;
        
         // today =  dd + '/' + mm + '/' + yyyy;
         today =  dd ;

        //alert(dd);
        //alert(date[0]);
        // alert(date[1]);
        if ( date[0] > dd  || date[1]  >  mm ) { 
                //alert('You cannot enter a date in the future!');
                //return false;
        }
        
        
		
		
		
		
		if( $("#order_id").val() != "" && $("#order_id").val() > 0 && $("#datetimepicker_dark1").val() != "" && $("#Client").val() != ''  ) 
		{
		$.ajax({
			type:"POST",
			data:{'PVal':PVal,'Qty':$("#qty_id").val()
			,'PriceID':$("#Rate_id").val()
			,'OrderNo':$("#OrderNo").val()
			,'ProductName':$("#PId").val()
			,'ProductDisc':$("#ProductDisc").val()
			,'purchase_rateID':$("#purchase_rateID").val()
			,'ClientName':$("#Client").val()
			,'room_sj':$("#RoomID").val()
			,'net_id':$("#net_id").val()
			,'uom_id':$("#uom_id").val()
			,'order_id_manual':$("#order_id").val()
			,'unitdiscription':$("#unitdiscription").val()
			,'category':$("#category").val()
			
			},
			dataType:'text',
			url:"PHP/InsertInvoice.php",
			success:function(dta)
			{	
				$('#PId').val("");
				$('#qty_id').val("");
				$('#unitdiscription').val("");
				$('#Rate_id').val("");
				$('#net_id').val("");
				$('#uom_id').val("");
				$('#qty_id').val("");
				$('#category').val("");
				console.log(dta);
				$("#InvResponse").load("PHP/InvoiceTempLoad.php");
				$("#ForPirnt").load("PHP/POSPrint.php");
				//$("#ForPirnt").load("PHP/inv_print.php");
			}
 
		});
		document.getElementById("PId").focus();
		
		    
		}else if($("#order_id").val() == ''){
		    
		    alert("please add invoice number.")   
		
		    
		}else if($("#Client").val() == ''){
		    alert("please add client name.")   
		    
		}else if($("#datetimepicker_dark1").val() == ''){
		    
		    alert("please add date .")   
		    
		}
		    
	}
	
	
	window.onload = function() { // load data in temp table on page load 
		$("#InvResponse").load("PHP/InvoiceTempLoad.php");
				$("#ForPirnt").load("PHP/POSPrint.php");
};
	
	// load data onchange
	$("#Client").change(function(){
				$("#InvResponse").load("PHP/InvoiceTempLoad.php");
				$("#ForPirnt").load("PHP/POSPrint.php");
	});
	//
	 $("#CreateNewProduct").click(function () {
		$.ajax({
			type:"POST",
			data:{
			 'NewProductName':$("#NewProductName").val()
			,'NewProductPrice':$("#NewProductPrice").val()
			,'NewProductPurchasePrice':$("#NewProductPurchasePrice").val()
			,'NewProductDiscount':$("#NewProductDiscount").val()
			,'ProductCode':$("#ProductCode").val()
			
			},
			dataType:'text',
			url:"PHP/CreateNewProduct.php",
			success:function(dta)
			{	
				$('#NewProductName').val("");
				$('#NewProductPrice').val("");
				$('#NewProductDiscount').val("");
				$('#ProductCode').val("");
				//alert('Product Created');
				
			}
 
		});
		
	});
	            function cust_data_combo () { 
						for (var a=0; a < CustomerData.length; a++){
								
								$('#client_name').append("<option value='"+CustomerData[a].clientname+"'>"+CustomerData[a].clientname+ " " +CustomerData[a].contact+  "</option>")
								
								//$('#client_name').append("<option value='"+CustomerData[a].clientname+"'>"+CustomerData[a].clientname+ " " +CustomerData[a].contact+  " " +CustomerData[a].address+  "</option>")
								$('#client_cell_list').append("<option value='"+CustomerData[a].contact+"'>"+CustomerData[a].clientname+ " " +CustomerData[a].contact+  " " +CustomerData[a].address+  "</option>")
												
						}
				}
				
				
				function product_combo_list () { 
						for (var a=0; a < ProductDefi.length; a++){
								
								$('#browsersD').append("<option value='"+ProductDefi[a].product_name+"'>"+ProductDefi[a].product_name+ "</option>")
												
						}
				}
				
	$("#CreateNewCustomer").click(function () {
	
		$('#client_name').append("<option value='"+$("#CustomerName").val()+"'>"+$("#CustomerName").val()+" "+$("#Cell").val()+"</option>")
		var cell_no = $("#Cell").val();
		var CustomerName = $("#CustomerName").val();
		$.ajax({
			type:"POST",
			data:{
			 'CustomerName':$("#CustomerName").val()
			,'Cell':$("#Cell").val()
			,'ResidentNo':$("#ResidentNo").val()
			,'Email':$("#Email").val()
			,'City':$("#City").val()
			,'Country':$("#Country").val()
			,'Address':$("#Address").val()
			,'company_name':$("#company_name").val()
			
			},
			dataType:'text',
			url:"PHP/CreateNewCustomer.php",
			success:function(dta)
			{	
			    
			    console.log(dta);
				$('#CustomerName').val("");
				$('#cell_no_for_invoice').val(cell_no);
				$('#Client').val(CustomerName);
				$('#Cell').val("");
				$('#ResidentNo').val("");
				$('#Email').val("");
				$('#City').val("");
				$('#Country').val("");
				$('#Address').val("");
				$('.save_msg').text("action save");
				
			}
 
		});
		
	});
		$(document).keyup(function(event) { // the event variable contains the key pressed
		/*
		if(event.which == 17) { // ctrl code 17
		$('#SavePrint').click();
		}
		*/
	});	
	
$("#SavePrint").click(function () {
	//alert($("#Paid_Amount_Id").val());
	//printData();
	
	
	var rowCount = $('#InvTable tr').length;
	if ( rowCount < 2 ) { 
            alert('please add product.');
            return false;
        }
	

		
		$.ajax({
			type:"POST",
			data:{
			        'manual_date':$("#datetimepicker_dark1").val()
			},
			dataType:'text',
			url:"PHP/post_time_checker.php",
			success:function(dta){	
				
				if(dta  == 1) { 
				    
                	insert_order();
				}else { 
				    
				    alert('You cannot add date in future.');
                    return false;
				    
				}
			}
 
    		});
	
	});	
	
	
		
	function insert_order (){


		if( $("#order_id").val() != "" && $("#order_id").val() > 0 && $("#datetimepicker_dark1").val() != "" && $("#Client").val() != '') 
		{
	
	//alert($("#datetimepicker_dark1").val());
	
		document.getElementById("PId").focus();	
	$("#SavePrint").hide();
	$("#spinner").show();
		$.ajax({
			type:"POST",
			data:{
			 'OrderNo':$("#OrderNo").val()
			,'ClientID':$("#Client option:selected").val()
			,'ClientName':$("#Client option:selected").text()
			,'GTotalView':$("#GTotalView").val()
			,'cell_no_for_invoice':$("#cell_no_for_invoice").val()
			,'PayableAmount':$("#PayableAmount").val()
			,'BiDisc':$("#BiDisc").val()
			,'GrandProfit':$("#GrandProfit").val()
			,'Paid_Amount_Id':$("#Paid_Amount_Id").val()
			,'paidAmount':$("#amount").val()//this is from MAIN WINDOW 
			,'Salesman':$("#Salesman").val()
			,'manual_date':$("#datetimepicker_dark1").val()
			,'order_id_manual':$("#order_id").val()
			,'OtherCharges':$("#OtherCharges").val()
			,'net_amt_static':$("#net_amt_static").val()
			,'Balance_id':$("#Balance_id").val()
			,'Client':$("#Client").val()
			,'OrderQuot_Id':$("#OrderQuot_Id").val()
			,'direct_discount_id':$("#direct_discount_id").val()
			,'order_status':$("#order_status").val()
			,'remarks_id':$("#remarks_id").val()
			,'address':$("#address").val()
			},
			dataType:'text',
			url:"PHP/InsertOrder.php",
			success:function(dta)
			{	
				
				//alert(dta);
				console.log(dta);
				//alert('Data Save');
				$('#Paid_Amount_Id').val('');
				$('#PaidAmountForStaticValue').val('');
				$('#cell_no_for_invoice').val('');
				$('#Client').val('');
				$('#Salesman').val('');
				//$('#datetimepicker_dark1').val('');
				
				$('#NeAmount_id').val('');
				$('#GTotal').val('');
				$('#GTotalView').val('');
				$('#net_amt_static').val('');
				$('#Balance_id').val('');
				$('#order_id').val('');
				$('#OtherCharges').val('');
				$('#qty_id').val('');
				$('#uom_id').val('');
				$('#Rate_id').val('');
				$('#FurtherDisc').val('');
				$('#BiDisc').val('');
				$('#BillDiscForStaticValue').val('');
				$('#RoomID').val('');
				$('#direct_discount_id').val('');
				$('#order_status').val('');
				$('#remarks_id').val('');
				$('#category').val("");
				$('#address').val("");
				
				
				
				$('#amount').val('');
				//$('#Balance_id').html('0');
				$("#paid_amount_history").html("");				
				$("#InvResponse").load("PHP/InvoiceTempLoad.php");
				$("#ForPirnt").load("PHP/POSPrint.php");
				document.getElementById("order_id").focus();
				$("#SavePrint").show();
				$("#spinner").hide();
	            loadCustomerBalances();
				loadInventoryStock();
				
				
		        	}
 
		        });
	    }
	}// end funtion	







	
	/*
	function view_invoice (obj)
			{
					var myWindow = window.open("PHP/AdvanceReceipt.php?sr="+obj.value, '', 'width=1200,height=500,scrollbars=1','location=no','menubar=0');
					myWindow.focus();	
			}
	*/	
			function edit_invoice (){
					var myWindow = window.open("PHP/edit_invoice.php?order_id="+$("#order_id").val(), '', 'width=800,height=400,scrollbars=1','location=no','menubar=0');
					myWindow.focus();	
			} 			
	//save print from dialoge 
	$("#SavePrint_dialog").click(function () {
	//printData();
	//alert($("#paid_date").val());
		$.ajax({
			type:"POST",
			data:{
			 'order_id':$("#order_id").val()
			 ,'Paid_fromDialogBog':$("#Paid_fromDialogBog").val()
			 ,'FurtherDisc':$("#FurtherDisc").val()
			 
			 ,'payment_note':$("#payment_note").val()
			 ,'paid_by':$("#paid_by").val()
			 ,'paid_date':$("#paid_date").val()
			 ,'Client': $("#Client").val()
			},
			dataType:'text',
			url:"PHP/InsertPaidAmountHistory.php",
			success:function(dta)
			{	
				//alert(dta);
				$('#Paid_fromDialogBog').val('');
				$('#FurtherDisc').val('');
				$("#paid_amount_history").html(dta);
			}
 
		});
		
	});	
	function DelRecored(objRow)
	{
		
		$.ajax({
			type:"POST",
			url:"PHP/DelRecored.php",
			data: { 'ID' : objRow.value,'Table' :'temp_order_item' ,'Col' : 'order_item_id' },
			success:function(dta)
			{	
				$("#InvResponse").load("PHP/InvoiceTempLoad.php");
				$("#ForPirnt").load("PHP/POSPrint.php");  
					
				
			}
 
		});
		 
	}
	function DelWindow(objRow)
	{
		
		$.ajax({
			type:"POST",
			url:"PHP/DelRecored.php",
			data: { 'ID' : objRow.value,'Table' :'window_temp' ,'Col' : 'window_item_id' },
			success:function(dta)
			{	
				$("#InvResponse").load("PHP/InvoiceTempLoad.php");
				$("#ForPirnt").load("PHP/POSPrint.php");  
					
				
			}
 
		});
		 
	}
	
	$('#CreateNewProd').on('shown.bs.modal', function () { // for model this command run 
			$('#Cell').focus();
		})
	function del_paid_amount (objRow)
		{
			var r = confirm(" are You sure to delete this recored ? ");
				if (r == true) {
						$.ajax({
							type:"POST",
							url:"PHP/DelRecored.php",
							data: { 'ID' : objRow.value,'Table' :'temp_paid_amount_history' ,'Col' : 'sr' },
							success:function(dta)
							{
									get_paid_amounts ();
							}
				 
						});
						
						
				}//end if
		}
		
		
		function get_paid_amounts ()
			{
				$.ajax({
							type:"POST",
							url:"PHP/get_paid_amounts.php",
							data: { 'order_id':$("#order_id").val() },
							success:function(dta){	
								$("#paid_amount_history").html(dta);
							}
				 
						});
			}
	//clear order dell order
	$("#reset").click(function () {
		
		$.ajax({
			type:"POST",
			data:{
			 'ID':$("#OrderNo").val()
			 ,'Col':'order_id'
			 ,'Table':'temp_order_item'
			},
			dataType:'text',
			url:"PHP/DelRecored.php",
			success:function(dta){	
				
				$("#InvResponse").load("PHP/POSPHP.php");
				$("#ForPirnt").load("PHP/POSPrint.php");
				
			}
 
		});
		
	});	
	/*
	$("#BiDisc").change(function () {
				//$("#InvResponse").load("PHP/POSPHP.php");
				//$("#ForPirnt").load("PHP/POSPrint.php");
		});	
	*/	
	function Balance(event) { 
	
	if (event.which == 13 || event.keyCode == 13) {
        $("#SavePrint").click();
       // return false;
	   
    }
   
	var bla = $('#Paid_Amount_Id').val();
	var PAmount = $('#PayableAmount').val();
	
	$('#Balance_id').html(PAmount-bla);
	}
	
		/*
		$("#amount").keyup(function(){
				
				$('#total_paying').html($(this).val());
				var v =  $('#GTotalView').val() - $(this).val();
				$('#balance').html(v); 
				
				});
		*/	
	
	function DialogBalance() { 	
	
					
            		var Paying 				= $('#Paid_Amount_Id').val();//what is to paying input feild
            		Paying	 	= isNaN(parseInt(Paying)) ? 0 : parseInt(Paying) // 0 for empty value
            		var Paid_fromDialogBog 	= $('#Paid_fromDialogBog').val();//what is to paying input feild
            		Paid_fromDialogBog	 	= isNaN(parseInt(Paid_fromDialogBog)) ? 0 : parseInt(Paid_fromDialogBog) // 0 for empty value
            		
            		var PaidAmountForStaticValue	= $('#PaidAmountForStaticValue').val();//what is to paying input feild
            			PaidAmountForStaticValue	 	= isNaN(parseInt(PaidAmountForStaticValue)) ? 0 : parseInt(PaidAmountForStaticValue) // 0 for empty value
            		
            		Paying =	Paid_fromDialogBog + PaidAmountForStaticValue
            		
            				
            		var NetAmount 		= $('#NeAmount_id').val();
            		
            		var b = NetAmount - Paying;
            			
            		$('#Due').val(b);
            		//$('#NeAmount_id').val(NetAmount);
            		$('#balance').html(b); //view on dialoge
            		$('#Balance_id').val(b);//view on page
            		$('#Paid_Amount_Id').val(Paying);//view on page
	}
	
	function direct_discount() { //direct discount , further discount , other payments
						var fur_dis 	= isNaN(parseInt($('#BiDisc').val())) ? 0 : parseInt($('#BiDisc').val());
						var dd_res		= isNaN(parseInt($('#direct_discount_id').val())) ? 0 : parseInt($('#direct_discount_id').val());
						var other_ch 	= isNaN(parseInt($('#OtherCharges').val())) ? 0 : parseInt($('#OtherCharges').val());
						var PaidAmountForStaticValue 	= isNaN(parseInt($('#PaidAmountForStaticValue').val())) ? 0 : parseInt($('#PaidAmountForStaticValue').val());
						
						/* get total discount for total net amnt*/
						var res =  dd_res  + fur_dis;
						
						/* put net amount to view */
						$('#NeAmount_id').val(parseInt($('#net_amt_static').val()) - res + other_ch);
						
						/*put value to balance */
						var net_amt_result = parseInt($('#net_amt_static').val()) - res + other_ch;
						$('#Balance_id').val(net_amt_result - PaidAmountForStaticValue );
						$('#Due').val(net_amt_result - PaidAmountForStaticValue );
						
						$('#twt').html(net_amt_result);
						
	}
	
	
	window.onkeydown = function(event) {
		if (event.keyCode === 113) {  // f2 for print 
			$('#SavePrint').click();
				
			}
			
		if (event.keyCode === 115) {  // f4 payment dialog 
				$('#payment').click();
				//document.getElementById("Paid_fromDialogBog").focus();
				
				$('#Paid_fromDialogBog').focus();
			}	
};


function loadInventoryStock() { 

        let lv_routValue = document.getElementById("lv_rout").value;
	    let lv_routWithApi = lv_routValue + "/api/get-inventory-stock";
	    

        $.ajax({
            type: "GET",
            dataType: "json",
            url: lv_routWithApi,
            data: {}, // Pass lv_rout as data
            success: function(data) {
                
                ProductStock = data;
                console.log("Inventory:", data);
            },
            error: function(xhr, status, error) {
                var errorMessage = "Error: " + error + "\nStatus: " + status;
                alert(errorMessage);
            console.error("AJAX error:", errorMessage);
        
            }
    });

}


function loadCustomerBalances() { 
	var lv_routValue = document.getElementById("lv_rout").value;
	    var lv_routWithApi = lv_routValue + "/api/customer_balance_rpt";
    	  $.ajax({
            type: "GET",
            dataType: "json",
            url: lv_routWithApi,
            data: { }, // Pass lv_rout as data
            success: function(data) {
                //alert(data);
                CustomerBalances = data;
               console.log("customerInventory:", data);
            },
            error: function(xhr, status, error) {
                 var errorMessage = "Error: " + error + "\nStatus: " + status;
            alert(errorMessage);
            console.error("AJAX error:", errorMessage);
        
            }
        });
    

}
