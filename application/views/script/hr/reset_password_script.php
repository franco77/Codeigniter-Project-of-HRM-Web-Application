<script type="text/javascript">
var base_url = '<?php echo base_url(); ?>';
$( document ).ready(function(){
	/* $("#btnEmpSearch").live('click', function(){
    	searchEmpName();
    }); */
   /*  $("#txtEmpSearch").keypress(function(e) { // start enter click
	    searchEmpName();
	}); */
});

function searchEmpName()
{
	var key = $("#txtEmpSearch").val();
    if(key != '' && key != 'Search Employee'){
    	//$("#empName").html('<div style="padding:20px 300px;"><img src="../images/loader.gif" /><div>');
        $.ajax({
			type: "POST",
			url: base_url+'en/hr/show_emp_name', 
			data: 'keyword='+key,
			success: function(data) {
				console.log(data);
        		$("#empName").html(data);
        		$("#resetArea").slideDown();
			},
			error: function(e) {
				alert("There is somme error in the network. Please try later.");
			}
		});
    }
}

function resetEmpCheck(dis){
	var txtEmpSearch = $('#txtEmpSearch').val();
	$("#empNameShow").html("");
	var str = "";
	$('#txtEmpSearch').removeAttr('style');
	$('#txtEmpSearch').attr('style', 'width: 195px;');
	if(txtEmpSearch !=""){
		$.ajax({
			type: "POST",
			url: site_url+'en/hr/reset_emp_pwd_check',
			data: {txtEmpSearch : txtEmpSearch},
			dataType: 'json',
			success: function(response)
			{
				//console.log(response);
				if(response.length > 0){
					$("#empNameShow").html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Employee ID : '+txtEmpSearch+'<br/> Employee Name: '+response[0].emp_name+' </div></div>');
					//setTimeout(function(){ $("#empNameShow").html(""); }, 15000);
				}
				else{
					$("#empNameShow").html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Invalid Employee ID </div></div>');
					setTimeout(function(){ $("#empNameShow").html(""); }, 5000);
				}
			}
		});
	}
	else{
		$('#txtEmpSearch').attr('style', 'border-color: #f00000; width: 195px;');
	}
}
function resetEmpPassword(dis){
	var txtEmpSearch = $('#txtEmpSearch').val();
	$("#empNameShow").html("");
	var str = "";
	$('#txtEmpSearch').removeAttr('style');
	$('#txtEmpSearch').attr('style', 'width: 195px;');
	if(txtEmpSearch !=""){
		$.ajax({
			type: "POST",
			url: site_url+'en/hr/reset_emp_pwd_submit',
			data: {txtEmpSearch : txtEmpSearch},
			dataType: 'json',
			success: function(response)
			{
				//console.log(response);
				if(response.length > 0){
					$("#empNameShow").html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Password of employee <strong> Employee ID : '+txtEmpSearch+' and Employee Name: '+response[0].emp_name+' </strong> reset to default password successfully. </div></div>');
					//setTimeout(function(){ $("#empNameShow").html(""); }, 15000);
				}
				else{
					$("#empNameShow").html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Invalid Employee ID </div></div>');
					setTimeout(function(){ $("#empNameShow").html(""); }, 5000);
				}
			}
		});
	}
	else{
		$('#txtEmpSearch').attr('style', 'border-color: #f00000; width: 195px;');
	}
}
function removeErrorEmp(dis){
	$(dis).removeAttr('style');
	$('#txtEmpSearch').attr('style', 'width: 195px;');
}
</script>