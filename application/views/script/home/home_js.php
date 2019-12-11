<script>
 var site_url = '<?php echo base_url(); ?>';
$(function()
{ 
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});
})


function searchStaffDirectory(dis){
	var search_key = $('#search_directory_key').val();
	$("#staffDirectory").html("");
	var str = "";
	$.ajax({
		type: "POST",
		url: site_url+'home/search_staff_directory',
		data: {search_key : search_key},
		dataType: 'json',
		success: function(response)
		{
			//console.log(response);
			if(response.length > 0){
				
				for(var i =0; i < response.length; i++){
					str +='<tr>';
					str +='<td>'+response[i].name+'</td>';
					str +='<td>'+response[i].phone+'</td>';
					str +='</tr>';
				}
			}
			$("#staffDirectory").html(str);
		}
	});
}
 
</script>