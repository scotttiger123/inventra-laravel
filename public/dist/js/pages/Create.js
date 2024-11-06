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



		
		$("#ProductCode_id").change(function () {
			//alert($("#ProductCode_id").val());
		$.ajax({
			type:"POST",
			dataType:'json',
			data:{
			 'ProductCode_id':$("#ProductCode_id").val()
			},
			url:"PHP/GetAllProductDefinitionForCreateProduct.php",
			success:function(dta)
			{	
				//alert(dta);
				//	$('#ProductCode').val("");
				//alert('Product Created');
				//alert(dta[0].rate);
				$('#SalePrice_id').val(dta[0].rate);
				$('#PurchasePrice_id').val(dta[0].purchase_rate);
				$('#unitdiscription').val(dta[0].unitdiscription);
				$('#UOM_id').val(dta[0].UOM_id);
				$('#DesignCode_id').val(dta[0].DesignCode_id);
				$('#Supplier_id').val(dta[0].Supplier_id);
				$('#Category_id').val(dta[0].Category_id);
				$('#sub_category').val(dta[0].sub_category);
				$('#Width_id').val(dta[0].Width_id);
				$('#MinCost_id').val(dta[0].MinCost_id);
				$('#MaxCost_id').val(dta[0].MaxCost_id);
				$('#WeightPerYard_id').val(dta[0].WeightPerYard_id);
				$('#PatternRepeatVerticle').val(dta[0].PatternRepeatVerticle);
				$('#PatternRepeatHorizontal').val(dta[0].PatternRepeatHorizontal);
				$('#PatternRepeat_id').val(dta[0].PatternRepeat_id);
				$('#PatternRepeatUnit_id').val(dta[0].PatternRepeatUnit_id);
				$('#Brand_id').val(dta[0].Brand_id);
				$('#BaseColor').val(dta[0].BaseColor);
				$('#Color1').val(dta[0].Color1);
				$('#Color2').val(dta[0].Color2);
				$('#Color3').val(dta[0].Color3);
				$('#Origin').val(dta[0].Origin);
				 
				 if(dta[0].Image != '') //this is due to surples image view on item profile or garbage issue
					{
						$('#payModalLabel').html(dta[0].Image);
						$(".div_imagetranscrits").html('<img src="images/' + dta[0].Image + '" width = "100%" height = "100%" style = "border:5px solid black;cursor: pointer;"/>');
					}	else { 	
						$('#payModalLabel').html('');
						$(".div_imagetranscrits").html('');
					}
					
				//$('.div_imagetranscrits').html('<img src="images/' + dta[0].Image + 'width ="100%" height ="100%">');
				
			}
 
			});
				
        });	

		$("#Supplier_id").change(function () {
			//alert($("#ProductCode_id").val());
		$.ajax({
			type:"POST",
			dataType:'json',
			data:{
			 'Supplier_id':$("#Supplier_id").val()
			},
			url:"PHP/GetSupplierInfo.php",
			success:function(dta)
				{	
				
					$('#Origin').val(dta[0].origin);
				}
 
			});
				
        });	
	
	
