
<script>
 var site_url = '<?php echo base_url(); ?>';
$(document).ready(function(){
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});
});

function resumeType(){
	$("#searchJobCode").val("");
	if($("#searchResumeType").val() =='applicants'){
		$("#searchJobCode").removeAttr('disabled'); 
	}else{
		$("#searchJobCode").attr('disabled', true);
	}
}

/* function advanceFilter(dis){
	var searchResumeType = $('#searchResumeType').val();
	var searchJobCode = $('#searchJobCode').val();
	var searchStartDate = $('#searchStartDate').val();
	var searchEndDate = $('#searchEndDate').val();
	var str="";
	$.ajax({
		type: "POST",
		url: site_url+'en/hr/interview_candidate_search',
		dataType: "json",
		data: {searchResumeType : searchResumeType, searchJobCode : searchJobCode, searchStartDate : searchStartDate, searchEndDate : searchEndDate},
		success: function(data)
		{
			if(data.length > 0){
				for(var i=-0; i<data.length; i++){
					str += "<tr>";
					str += "<td><a data-id='"+data[i].id+"' id='description'  data-toggle='modal' data-target='#myModal'>"+data[i].first_name+" "+data[i].last_name+"</a></td>";
					str += "<td>"+data[i].email+"</td>";
					str += "<td>"+data[i].tel+"</td>";
					if(data[i].post_title != undefined){
						str += "<td>"+data[i].post_title+"</td>";
					}
					else{
						str += "<td></td>";
					}
					str += "<td>"+data[i].highest_qualification+"</td>";
					str += '<td align="center">';
					if(data[i].cv != ""){
						str += '<a href="'+data[i].cv+'" download><img alt="Delete" src="'+site_url+'assets/images/icon/move.png" /></a>';
					}
					str += '</td>';
					str += "</tr>";
				}
			}
			else{
				str += "<tr><td colspan='6' align='center'>No records found</td></tr>";
			}
			$('#filterData').html(str);
	   }
	});
} */

</script>