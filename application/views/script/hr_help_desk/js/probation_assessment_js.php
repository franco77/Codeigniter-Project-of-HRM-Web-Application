<script>
 var site_url = '<?php echo base_url(); ?>';
$(document).ready(function(){
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});
});

function getDesgnation(dis){
	var department = $('#searchDepartment').val();
	var str="";
	$.ajax({
		type: "POST",
		url: site_url+'my_account/get_designation',
		data: {department : department},
		success: function(response)
		{
			response = JSON.parse(response);
			str += '<option value="">Select</option>';
			for(var i=0; i< response.length; i++){
				str += '<option value="'+response[i].desg_id+'">'+response[i].desg_name+'</option>';
			}
			$('#searchDesignation').html(str);
	   }
	});
	
}


function advanceFilter(dis){
	var searchDepartment = $('#searchDepartment').val();
	var searchName = $('#searchName').val();
	var searchDesignation = $('#searchDesignation').val();
	var searchEmpCode = $('#searchEmpCode').val();
	var str="";
	$.ajax({
		type: "POST",
		url: site_url+'hr_help_desk/probation_assessment_filter',
		dataType: "json",
		data: {searchDepartment : searchDepartment, searchName : searchName, searchDesignation : searchDesignation, searchEmpCode : searchEmpCode},
		success: function(data)
		{
			if(data.length > 0){
				for(var i=-0; i<data.length; i++){
					str += "<tr>";
					str += "<td>"+data[i].name+"</td>";
					str += "<td>"+data[i].loginhandle+"</td>";
					str += "<td>"+data[i].email+"</td>";
					str += "<td>"+data[i].apply_dates+"</td>";
					if(data[i].dh_status == 0){
						str += "<td>pending</td>";
					}
					else{
						str += "<td>approved</td>";
					}
					str += '<td align="center"><a onclick="openNewWindow('+data[i].mid+','+data[i].login_id+')" ><img alt="Print" style="cursor: pointer;" src="'+site_url+'assets/images/printer_icon.png" /></a></td>';
					str += "</tr>";
				}
			}
			else{
				str += "<tr><td colspan='6' align='center'>No records found</td></tr>";
			}
			$('#filterData').html(str);
	   }
	});
}

function openNewWindow(mid,login_id){
	window.open(site_url+'hr_help_desk/probation_assessment_print?id='+login_id+'&mid='+mid, '_blank', 'location=yes,width=980,height=600,left=0,top=0,scrollbars=1');
}


function updateDHStatus(mid,login_id,dh_status){
	var str="";
	$.ajax({
		type: "POST",
		url: site_url+'hr_help_desk/probation_assessment_update_dh_status',
		dataType: "json",
		data: {mid : mid, login_id : login_id, dh_status : dh_status},
		success: function(data)
		{
			location.reload();
	   }
	});
}

</script>