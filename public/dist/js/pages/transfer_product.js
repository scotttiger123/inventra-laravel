		
		$("#ProductCode_id").change(function () {
			
			/*
				$.ajax({
					type:"POST",
					dataType:'json',
					data:{
					 'ProductCode_id':$("#ProductCode_id").val()
					},
					url:"PHP/GetAllProductDefinitionForCreateProduct.php",
					success:function(dta)
						{	
							$('#AvailableQantity_id').val(dta[0].Stock);
							//BalanceQuantity();
						}
		 
					});
				
			*/	
			
				$.ajax({
					type:"POST",
					dataType:'json',
					data:{
					 'ProductCode_id':$("#ProductCode_id").val()
					},
					url:"PHP/inward_data.php",
					success:function(dta)
						{	
							
							$("#Cutt_Stock").html("");
							    for (var i = 0; i < dta.length; i++) {
								$('#BalanceQuantity_id').val(dta[0].accum_qty);
								$('#BalanceQuantity_id_hidden').val(dta[0].accum_qty);
							if(dta[i].quantity>0){
							     $('#Cutt_Stock').append("<option value="+dta[i].Stock_id+'~'+dta[i].quantity+">Qty : "+dta[i].Stock_id+'~qTY'+dta[i].quantity+"</option>");
							    }    
							}
					//	console.log(accumolative_qty);
						}
		 
					});
			
				
				
        });	
	
	$("#Cutting_From_Stock_Id_id").change(function () {
	$.ajax({
					type:"POST",
					dataType:'json',
					data:{
					 'ProductCode_id':$("#ProductCode_id").val()
					},
					url:"PHP/inward_data.php",
					success:function(dta)
						{	
							for (var i = 0; i < dta.length; i++) {
								if($("#Cutting_From_Stock_Id_id").val() == dta[i].Stock_id )
										{
										$('#LocationStore_id').val(dta[i].LocationStoreShop_id);
									}
							}
							
							
						}
		 
					});
	
	   });	
	
	 function BalanceQuantity() {
	     
	    // var qty_from_stock_id = 0;
	    // qty_from_stock_id = $('#Cutting_From_Stock_Id_id').val();
	    // qty_from_stock_id = qty_from_stock_id.split('~')[1];
	     
	         //   var stock_id_bln = 0 ;     
	          //   stock_id_bln   = parseInt(qty_from_stock_id - parseInt($('#QuantityTo_Be_Cut_id').val())); 
	            
	         //   $('#AvailableQantity_id').val(stock_id_bln) ;
	            
	        $('#BalanceQuantity_id').val(parseFloat($('#BalanceQuantity_id_hidden').val()  - $('#QuantityTo_Be_Cut_id').val()).toFixed(2));
	    }	
	
				function display_stor_location() { 	
						
						$("display_location_column").show();
					
				}	
	
	/*
	function cut_from_roll() { 	
	var a = $('#Cutting_From_Stock_Id_id').val()
	var b = $('#QuantityTo_Be_Cut_id').val()
		
			if( a > b)
				{
					
					$('#Msg').html('Sorry Quantity to be cutt not available in this roll');
						$("#Cutting_From_Stock_Id_id").val("");
						$('#default_mod_button').click(); 
						
				}	
	}	
	*/
 $("#SaveOutwardWareHouse").click(function () {
	var stock_value = $("#BalanceQuantity_id_hidden").val();
	var input_value = $("#QuantityTo_Be_Cut_id").val();
	console.log(stock_value);
	console.log(input_value);
		
		IODataCall();
		
		document.getElementById("ProductCode_id").focus();
		
	});
	

	function 	IODataCall() {
		//document.getElementById("SaveOutwardWareHouse").disabled = true;
		
		$.ajax({
			type:"POST",
			data:{
			  'ProductCode_id'					:$("#ProductCode_id").val()
			 ,'Date_id'							:$("#input_date").val()
			 ,'Cutting_Person_id'				:$("#Cutting_Person_id").val()
			 ,'LocationStore_id'				:$("#LocationStore_id").val()
			 
			},
			dataType:'text',
			url:"PHP/update_location_inward.php",
			success:function(dta)
			{	
				
				
				$("#ProductCode_id").val("");
				$("#QuantityTo_Be_Cut_id").val("");
				$("#AvailableQantity_id").val("");
				$("#BalanceQuantity_id").val("");
				$("#Bill_Ref_id").val("");
				$("#SalesMan").val("");
				$("#Cutting_From_Stock_Id_id").val("");
				$("#BalanceQuantity_id_hidden").val("");
				alert(dta);
							
				
				document.getElementById("SaveOutwardWareHouse").disabled = false;
				
			}
 
		});
		
	}
	
