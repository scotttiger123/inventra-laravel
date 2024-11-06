$("#addEmployee").click(function () {
		
		$.ajax({
			type:"POST",
			data:{
			  'email_address':$("#email_address").val()
			 ,'name':$("#name").val()
			 ,'phone':$("#phone").val()
			 ,'group':$("#group option:selected").val()
			
			 ,'gender':$("#gender").val()
			
			},
			dataType:'text',
			url:"PHP/InsertEmployee.php",
			success:function(dta)
			{	
				alert(dta);
				$('#email_address').val("");
				$('#name').val("");
				$('#phone').val("");
				$('#group').val("");
				
				$('#gender').val("");
			}
 
		});
		
	});