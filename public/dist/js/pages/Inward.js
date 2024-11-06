 function printData()
{
   document.getElementById('ForPirnt').style.display = 'Block'; 
  
   var divToPrint = document.getElementById("ForPirnt");
   newWin= window.open("");
  
   newWin.document.write(divToPrint.outerHTML);
   newWin.print();
   newWin.close();
   document.getElementById('ForPirnt').style.display = 'none'; 
}

  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

  })
  //get prodcut definition
		$("#PId").change(function () {
			//alert($("#ProductCode_id").val());
		$.ajax({
			type:"POST",
			dataType:'json',
			data:{
			 'ProductCode_id':$("#PId").val()
			},
			url:"PHP/GetAllProductDefinitionForCreateProduct.php",
			success:function(dta)
			{	
				//alert(dta);
				$('#SalePrice_id').val(dta[0].rate);
				$('#PurchasePrice_id').val(dta[0].purchase_rate);
				$('#UOM_id').val(dta[0].UOM_id);
				$('#SupplierBrand_id').val(dta[0].Supplier_id);
				
				 
			}
 
			});
				
        });	
  $("#ProductCode").change(function () {
      console.log("prod");
      alert();
	  //global array loaded at page loaded ProductDefi
	  for (var c=0; c < ProductDefi.length; c++){
						if (ProductDefi[c].product_id == $("#ProductCode").val())	{
						    console.log("product data ");
							document.getElementById("NewProductName").value = ProductDefi[c].product_name;
							document.getElementById("NewProductPrice").value = ProductDefi[c].Price;
							document.getElementById("NewProductPurchasePrice").value = ProductDefi[c].purchase_rate;
							document.getElementById("NewProductDiscount").value = ProductDefi[c].discount;
						}

					}
				
        });		
		
	$("#SupplierBrand_id").change(function () {
			//alert($("#ProductCode_id").val());
		$.ajax({
			type:"POST",
			dataType:'json',
			data:{
			 'Supplier_id':$("#SupplierBrand_id").val()
			},
			url:"PHP/GetSupplierInfo.php",
			success:function(dta)
				{	
				
					$('#OriginList_id').val(dta[0].origin);
				}
 
			});
				
        });	
		
		
   //input data
  var GTotal ;
  function IOData()
	{
	    /*
		if($("#PId").val() == '')
		{
			$('#Msg').html('Please add product');
			$('#default_mod_button').click(); 
			document.getElementById("PId").focus();
			return
		}else if($("#QTY_id").val() == '' && $("#Estimated_QTY_id").val() == '' ) {
			$('#Msg').html('Please add Quantity');
			$('#default_mod_button').click(); 
			document.getElementById("QTY_id").focus();
			return
		}else if($("#PurchasePrice_id").val() == ''){
			$('#Msg').html('Please add Cost Price');
			$('#default_mod_button').click(); 
			document.getElementById("PurchasePrice_id").focus();			
			return
		}else if($("#SalePrice_id").val() == '') {
			$('#Msg').html('Please add Sale Price');
			$('#default_mod_button').click(); 
			document.getElementById("SalePrice_id").focus();
			return
		}else if($("#LocationStoreShop_id").val() == '') {
			$('#Msg').html('Please add Location Store / Shop');
			$('#default_mod_button').click(); 
			document.getElementById("LocationStoreShop_id").focus();
			return
		}*/
		
		IODataCall();
		document.getElementById("PId").focus();
	}
	
	 function IODataCall()
	{
			$.ajax({
			type:"POST",
			data:{
				 'SupplierBrand_id':$("#SupplierBrand_id").val()
				,'OriginList_id':$("#OriginList_id").val()
				,'PurchaseType_id':$("#PurchaseType_id").val()
				,'DesignCode_id':$("#DesignCode_id").val()
				,'PVal':$("#PId").val()
				,'ProductName_id'	:$("#ProductName_id").val()
			
			,'QTY_id'			:$("#QTY_id").val()
			,'Estimated_QTY_id'	:$("#Estimated_QTY_id").val()
			,'PurchasePrice_id'	:$("#PurchasePrice_id").val()
			,'SalePrice_id'		:$("#SalePrice_id").val()
			,'UOM_id'			:$("#UOM_id").val()
			,'Stock_id'			:$("#Stock_id").val()
			,'StockType_id'		:$("#StockType_id").val()
			,'MinCost_id'		:$("#MinCost_id").val()
			,'MaxCost_id'		:$("#MaxCost_id").val()
			,'Design_id'		:$("#Design_id").val()
			,'LocationStoreShop_id'		:$("#LocationStoreShop_id").val()
			,'rack_in_store'		:$("#rack_in_store").val()
			,'Picture_id'				:$("#Picture_id").val()
			,'WeightPerMeter_id'		:$("#WeightPerMeter_id").val()
			,'WeightPerRoll_id'			:$("#WeightPerRoll_id").val()
			,'batch_no'					:$("#batch_no").val()
			,'datetimepicker_dark'		:$("#purchase_date").val()
			,'inward_remarks'		    :$("#inward_remarks").val()
			
			
			
			},
			dataType:'text',
			url:"PHP/InwardInsert.php",
			success:function(dta)
			{	
				console.log(dta);
				//alert(dta);
				$('#PId').val("");
				$('#ProductName_id').val("");
				$('#PurchasePrice_id').val("");
				$('#SalePrice_id').val("");
				$('#MinCost_id').val("");
				$('#MaxCost_id').val("");
				$('#QTY_id').val("");
				
				$('#Estimated_QTY_id').val("");
				$("#InvResponse").load("PHP/InwardView.php");
				
			}
 
		});
		
	}
	
	$("#SavePrint").click(function () {
		document.getElementById("PId").focus();	
		$.ajax({
			type:"POST",
			data:{ 	},
			dataType:'text',
			url:"PHP/InwardFinalSave.php",
			success:function(dta)
			{	
				
				$("#InvResponse").load("PHP/InwardView.php");
				$("#ForPirnt").load("PHP/InwardPrint.php");
				alert("Data Saved");
			}
 
		});
		
	});	
	
	$("#reset").click(function () {
		
		$.ajax({
			type:"POST",
			data:{
			 'ID':$("#OrderNo").val()
			 ,'Col':'order_id'
			 ,'Table':'inward_temp_order_item'
			},
			dataType:'text',
			url:"PHP/DelRecored.php",
			success:function(dta)
			{	
				
				$("#InvResponse").load("PHP/InwardView.php");
				$("#ForPirnt").load("PHP/InwardPrint.php");
				
			}
 
		});
		
	});	
	
	
		 $("#id_close").click(function () {
			location.reload(); 
			 
		 });
	
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
				$('#NewProductPurchasePrice').val("");
				$('#NewProductDiscount').val("");
				$('#ProductCode').val("");
				//alert('Product Created');
				alert(dta);
				
			}
 
		});
		
	});
	