 function printData()
{
  
   document.getElementById('ForPirnt').style.display = 'Block';
 
  $("#PrintReceiptVal").html($("#Paid_Amount_Id").val());
   var divToPrint = document.getElementById("ForPirnt");
   newWin= window.open("");
  
   newWin.document.write(divToPrint.outerHTML);
   newWin.print();
   newWin.close();
   document.getElementById('ForPirnt').style.display = 'none'; 
   
}


function laod_prod_defi() //this is for scanning device auto load product definition 
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

function GetItems(DSLValueTest,Extra){
		
		var lfckv = document.getElementById("letter_head").checked;
		var DSLValue = $("#order_id").val();
		myWindow = window.open("PHP/ViewPOSOrder.php?order_id="+DSLValue +"&^%^$@*^^%@1&&%%^*&*2345@="+Extra+"&checkbox="+lfckv, '', 'width=600,height=300,scrollbars=1','location=no','menubar=0');
		myWindow.focus();
	} 
 
 $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

  })
   function cust_data_combo () { 
						for (var a=0; a < CustomerData.length; a++){
								$('#client_name').append("<option value='"+CustomerData[a].clientname+"'>"+CustomerData[a].clientname+ " " +CustomerData[a].contact+  " " +CustomerData[a].address+  "</option>")
								$('#client_cell_list').append("<option value='"+CustomerData[a].contact+"'>"+CustomerData[a].clientname+ " " +CustomerData[a].contact+  " " +CustomerData[a].address+  "</option>")
					
						}
					}					
			
$("#PId").keydown(function(){
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
			
        });
		
		  
/*
function net_rate_to_percentage () {
	
			var Rate_id 	= $("#Rate_id").val();
			Rate_id	 	= isNaN(parseInt(Rate_id)) ? 0 : parseInt(Rate_id) // putting 0 for empty value
			
			var net_id 	= $("#net_id").val();
			net_id	 	= isNaN(parseInt(net_id)) ? 0 : parseInt(net_id) // putting 0 for empty value
			
			var perce = ( net_id / 100 ) * Rate_id; 
			$("#disc_percentage").val(perce)
		
}

function percentage_to_net_rate () {
	
		var disc_value 	= $("#disc_percentage").val();
		disc_value	 	= isNaN(parseInt(disc_value)) ? 0 : parseInt(disc_value) // 0 for empty value
		
		var Rate_id 	= $("#Rate_id").val();
		Rate_id	 	= isNaN(parseInt(Rate_id)) ? 0 : parseInt(Rate_id) // putting 0 for empty value
		
			var net_rate = ( disc_value / 100 ) * Rate_id; 
			net_rate	 =  $("#Rate_id").val() - net_rate ; 
			$("#net_id").val(net_rate);
		
}
*/
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
				//alert(dta);
				
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
	
	
	function edit_order(){
		var myWindow = window.open("PHP/edit_order.php?order_id="+$("#order_id").val(), '', 'width=600,height=300,scrollbars=1','location=no','menubar=0');
		myWindow.focus();
	}
	
	
		
		$("#ProductCode").change(function () {
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
		
		 $("#id_close").click(function () {
			location.reload(); 
			 
		 });
		 /*
		 $("#CustDiaClose").click(function () {
			//location.reload(); 
			$("#LoadCustomerInfoPatch").load("PHP/LoadCustomerInfoPatch.php"); 
			
		 });
		 */
   //input data
  var GTotal ;
  function IOData()
	{
		
		// var PVal = $('#PId').find(":selected").text();this select the text of combo box 
		//var PVal = $("#PId option:selected").val();
		var PVal = $("#PId").val();
		var Qty  = $('input[name="Qty"]').val();
		$.ajax({
			type:"POST",
			data:{'PVal':PVal,'Qty':$("#qty_id").val()
			,'PriceID':$("#Rate_id").val()
			,'OrderNo':$("#OrderNo").val()
			,'ProductName':$("#PId").val()
			,'ProductDisc':$("#ProductDisc").val()
			,'purchase_rateID':$("#purchase_rateID").val()
			,'ClientName':$("#Client option:selected").val()
			,'room_sj':$("#RoomID").val()
			,'net_id':$("#net_id").val()
			,'uom_id':$("#uom_id").val()
			,'order_id_manual':$("#order_id").val()
			,'unitdiscription':$("#unitdiscription").val()
			,'category':$("#category").val()
			
			},
			dataType:'text',
			url:"PHP/InsertPOS.php",
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
				//alert(dta);
				$("#InvResponse").load("PHP/POSPHP.php");
				$("#ForPirnt").load("PHP/POSPrint.php");
				//$("#ForPirnt").load("PHP/inv_print.php");
				document.getElementById("PId").focus();
				
			}
 
		});
		
		
		
	}
	// load data onchange
	/*
		$('#RoomWindowModel').on('shown.bs.modal', function () { // for model this command run 
			$('#window_detail').focus();
		})  
		
		$('#CreateNewProd').on('shown.bs.modal', function () { // for model this command run 
			$('#Cell').focus();
		})
	*/	
	$("#Client").change(function(){
				$("#InvResponse").load("PHP/POSPHP.php");
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
				alert(dta);
			}
 
		});
		
	});
	
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
			
			
			},
			dataType:'text',
			url:"PHP/CreateNewCustomer.php",
			success:function(dta)
			{	
				$('#CustomerName').val("");
				$('#cell_no_for_invoice').val(cell_no);
				$('#Client').val(CustomerName);
				$('#Cell').val("");
				$('#ResidentNo').val("");
				$('#Email').val("");
				$('#City').val("");
				$('#Country').val("");
				$('#Address').val("");
				alert('Action Completed');
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
									$("#spinner_push_online").hide();
									$("#push_online").show();
								}
							
							});
				}	
	});	
$("#SavePrint").click(function () {
	//alert($("#remarks_id").val());
	//printData();
	$("#SavePrint").hide();
	$("#spinner").show();
	document.getElementById("PId").focus();	
		$.ajax({
			type:"POST",
			data:{
			 'OrderNo':$("#OrderNo").val()
			,'ClientID':$("#Client option:selected").val()
			,'ClientName':$("#Client option:selected").text()
			,'cell_no_for_invoice':$("#cell_no_for_invoice").val()
			,'GTotalView':$("#GTotalView").val()
			,'PayableAmount':$("#PayableAmount").val()
			,'BiDisc':$("#BiDisc").val()
			,'GrandProfit':$("#GrandProfit").val()
			,'Paid_Amount_Id':$("#Paid_Amount_Id").val()
			,'paidAmount':$("#amount").val()//this is from MAIN WINDOW 
			,'Salesman':$("#Salesman").val()
			,'manual_date':$("#datetimepicker_dark").val()
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
				//alert('Data Save');
				$('#Paid_Amount_Id').val('');
				$('#PaidAmountForStaticValue').val('');
				$('#Client').val('');
				$('#Salesman').val('');
				$('#datetimepicker_dark').val('');
				$('#cell_no_for_invoice').val('');
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
				$('#direct_discount_static_value').val('');
				$('#order_status').val('');
				$('#remarks_id').val('');
				$('#category').val("");
				$('#amount').val('');
				$('#address').val('');
				//$('#Balance_id').html('0');	
				$("#paid_amount_history").html("");
				$("#InvResponse").load("PHP/POSPHP.php");
				$("#ForPirnt").load("PHP/POSPrint.php");
				document.getElementById("order_id").focus();
				$("#SavePrint").show();
				$("#spinner").hide();
				
				
				
			}
 
		});
		
	});	
	/*
	$('#RoomID').on('input',function(e){ // when input feild select auto pop up data 
   
	$("#pop_window").click();
});
*/
	function view_invoice (obj)
			{
		
				
					var myWindow = window.open("PHP/AdvanceReceipt.php?sr="+obj.value, '', 'width=1200,height=500,scrollbars=1','location=no','menubar=0');
					myWindow.focus();	
				
			
			}  
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
		
		function get_paid_amounts (){
						
						$.ajax({
							type:"POST",
							url:"PHP/get_paid_amounts.php",
							data: { 'order_id':$("#order_id").val() },
							success:function(dta)
							{	
								$("#paid_amount_history").html(dta);
								
							}
				 
						});
					}
	//save print from dialoge 
		
	$("#SavePrint_dialog").click(function () {
	//printData();
			//alert($("#order_id").val());
		$.ajax({
			type:"POST",
			data:{
			 'order_id':$("#order_id").val()
			 ,'Paid_fromDialogBog':$("#Paid_fromDialogBog").val()
			 ,'FurtherDisc':$("#FurtherDisc").val()
			 
			 ,'payment_note':$("#payment_note").val()
			 ,'paid_by':$("#paid_by").val()
			 ,'paid_date':$("#paid_date").val()
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
				$("#InvResponse").load("PHP/POSPHP.php");
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
				$("#InvResponse").load("PHP/POSPHP.php");
				$("#ForPirnt").load("PHP/POSPrint.php");  
					
				
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
			success:function(dta)
			{	
				
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
						//alert(res);
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
			
		if (event.keyCode === 115) {  // f4 for payment dialogbox 
			$('#payment').click();
			
			}	
};
	
	
	
	
	
	
	
	
	
	
	
	
	