<script>
 var site_url = '<?php echo base_url(); ?>';
$(function()
{ 
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});
})


function searchAnniversary(dis){
	var dd_month = $('#dd_month').val();
	var dd_year = $('#dd_year').val();
	var monthArr = ["January","February","March","April","May","June","July","August","September","October","November","December"];
	var monthArrs = ["JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];
	$(".anniversary-content-data").html("");
	var str = "";
	$.ajax({
		type: "POST",
		url: site_url+'anniversary/search_anniversary',
		data: {dd_month : dd_month, dd_year : dd_year},
		dataType: 'json',
		success: function(response)
		{
			console.log(response);
			if(response.length > 0){
				
				for(var i =0; i < response.length; i++){
					str +='<div class="news_calender"><div class="news_box_calendar pull-left"><div class="month_year">'+monthArrs[dd_month-1]+' '+dd_year+' </div><div class="date">'+response[i].annv_day+' </div></div></div>';
					str +='<div class="news_box_right">';
					if(response[i].user_photo_name !=""){
						str +='<img src="'+site_url+'assets/upload/profile/'+response[i].user_photo_name+'" alt="" class="profile-picture">';
					}
					else{
						str +='<img src="'+site_url+'assets/images/no-image.jpg" alt="" class="profile-picture">';
					}
					str +='<div class="birthday-content">';
					str +='<h4>'+response[i].name_first+' '+response[i].name_last+'</h4>';
					str +='<p>( '+response[i].desg_name+' )</p>';
					str +='<p>Anniversary on '+response[i].annv_date+'</p>';
					str +='<img src="'+site_url+'assets/dist/images/candel.png" alt="HBD" class="greeting-picture"/>';
					str +='</div>'; 
					str +='</div>';
					
				}
			}
			else{
				//location.reload();
			}
			$(".anniversary-content-data").html(str);
			$(".searchMonth").html(monthArr[dd_month-1]);
		}
	});
}
 
</script>