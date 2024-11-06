$("#CreateNewCustomer").click(function () {
	
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
			url:"PHP/CreateNewSupplier.php",
			success:function(dta)
			{	
			    
			    alert('action Saved');
			    console.log(dta);
				$('#CustomerName').val("");
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