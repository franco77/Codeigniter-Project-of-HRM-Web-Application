<script>
 var site_url = '<?php echo base_url(); ?>';
$(document).ready(function(){
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});
});

function getDesgnation(dis){
	var department = $('#department').val();
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
			$('#designation').html(str);
	   }
	});
}


function advanceFilter(dis){
	var searchDepartment = $('#department').val();
	var searchName = $('#searchName').val();
	var searchDesignation = $('#designation').val();
	var searchEmpCode = $('#searchEmpCode').val();
	var str="";
	$.ajax({
		type: "POST",
		url: site_url+'hr_help_desk/online_mrf_detail_all_search',
		dataType: "json",
		data: {searchDepartment : searchDepartment, searchName : searchName, searchDesignation : searchDesignation, searchEmpCode : searchEmpCode},
		success: function(data)
		{
			if(data.length > 0){
				for(var i=-0; i<data.length; i++){
					str += "<tr>";
					str += "<td>"+data[i].full_name+"</td>";
					str += "<td>"+data[i].loginhandle+"</td>";
					str += "<td>"+data[i].desg_name+"</td>";
					str += "<td>"+data[i].mrf_apply_dates+"</td>";
					if(data[i].dh_status == 0){
						str += "<td>Pending</td>";
					}
					else if(data[i].dh_status == 1){
						str += "<td>Approved</td>";
					}
					else{
						str += "<td>Reject</td>";
					}
					if(data[i].mrf_status == 0){
						str += "<td>Closed</td>";
					}
					else if(data[i].mrf_status == 2){
						str += "<td>Rejected</td>";
					}
					else{
						str += "<td>Open</td>";
					}
					str += '<td align="center">';
					if(data[i].dept_login_id!=data[i].login_id && data[i].dh_status==0){ 
						str += '<a class="link" href="'+site_url+'hr_help_desk/online_mrf?id='+data[i].login_id+'&mid='+data[i].mid+'"><img src="'+site_url+'assets/images/icon/edit.png" alt="Edit" /></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="'+site_url+'hr_help_desk/online_mrf_detail_all?id='+data[i].login_id+'&mid='+data[i].mid+'&action=delete" onclick="return checkDelete()"><img alt="Delete" src="'+site_url+'assets/images/icon/del.png" /></a>';
					}
					str += '</td>';
					str += "</tr>";
				}
			}
			else{
				str += "<tr><td colspan='7' align='center'>No records found</td></tr>";
			}
			$('#filterData').html(str);
	   }
	});
}

</script>