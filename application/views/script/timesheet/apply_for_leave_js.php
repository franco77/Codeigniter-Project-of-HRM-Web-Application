<script>
 var site_url = '<?php echo base_url(); ?>';
$(document).ready(function(){
	var currentTime = new Date();
	// First Date Of the month 
	var startDateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth(),1);
	// Last Date Of the Month 
	var startDateTo = new Date(currentTime.getFullYear(),currentTime.getMonth() +1,0);

	$('.datepickerShowStartDate').datepicker({
        dateFormat: 'dd-mm-yy',
		minDate: startDateFrom,
		onSelect: function(selected) {
			$(".datepickerShowEndDate").datepicker("option","minDate", selected);
			checkLeaveCount();
        }
	});
	$('.datepickerShowEndDate').datepicker({
        dateFormat: 'dd-mm-yy',
		minDate: startDateFrom,
		onSelect: function(selected) {
           $(".datepickerShowStartDate").datepicker("option","maxDate", selected);
		   checkLeaveCount();
        }
	});
});

function checkLeaveCount(){
	var avlSL = $('#avlSL').val();
	var avlPL = $('#avlPL').val();
	var leave_type = $('#leave_type').val();
	var from_date = $('#from_date').val();
	var to_date = $('#to_date').val();
	var halfday1 = $('#halfday1').val();
	var halfday2 = $('#halfday2').val();
	var str=""; 
	
	if(leave_type == "M"){
		$('#leaveApply').removeAttr('disabled');
		$('.reason').css('display', 'none');
	}
	else if(from_date != "" && to_date != "" && leave_type != ""){
		if(leave_type == "M")
		{
			$('.reason').css('display', 'none');
		}
		
		 
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/chek_leave_availability_count',
			dataType: "json",
			data: {leave_type : leave_type, avlSL : avlSL, avlPL : avlPL, from_date : from_date, to_date : to_date, halfday1 : halfday1, halfday2 : halfday2},
			success: function(data)
			{
				if(data == 1){
					
					$('#leaveApply').removeAttr('disabled');
				}
				else{
					$('#leaveApply').attr('disabled', 'disabled');
				}
		    }
		}); 
	}
	else{
		$('#leaveApply').attr('disabled', 'disabled');
	}
}

function checkLeave(dis){
	var leave_type = $(dis).val();
	var str="";
	if(leave_type == "M"){
		$('#leaveApply').removeAttr('disabled');
		$('.reason').css('display', 'none');
	}
	else if(leave_type != ""){
		
		if(leave_type == "M")
		{
			$('.reason').css('display', 'block');
		}
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/chek_leave_availability',
			dataType: "json",
			data: {leave_type : leave_type},
			success: function(data)
			{
				if(data == 1){
					
					//$('#leaveApply').removeAttr('disabled');
					checkLeaveCount();
				}
				else{
					$('#leaveApply').attr('disabled', 'disabled');
				}
		   }
		});
	}
	else{
		$('.reason').css('display', 'block');
		$('#leaveApply').attr('disabled', 'disabled');
	}
}


function confirmLeave(dis){
	$('#myModal_leave').modal('hide'); 
	$('.modal-body .headtitle1').html('');
	var leave_type = $('#leave_type').val();
	var from_date = $('#from_date').val();
	var halfday1 = $('#halfday1').val();
	var to_date = $('#to_date').val();
	var halfday2 = $('#halfday2').val();
	var txtReason = $('#txtReason').val();
	var reportingTo = $('#reportingTo').val();
	var txtDetails = $('#txtDetails').val();
	var str="";
	if(leave_type == "" && leave_type != "M"){
		$('#leave_type').attr('style', 'border-color: #f00000;');
	}
	if(from_date == ""){
		$('#from_date').attr('style', 'border-color: #f00000;');
	}
	if(to_date == ""){
		$('#to_date').attr('style', 'border-color: #f00000;');
	}
	if(txtReason.trim().length == 0){
		$('#txtReason').attr('style', 'border-color: #f00000;');
	}
	if(txtDetails == ""){
		$('#txtDetails').attr('style', 'border-color: #f00000;');
	}
	if((leave_type !="" && leave_type == "M" && from_date !="" && to_date !="" && txtDetails !="") || (leave_type !="" && from_date !="" && to_date !="" && txtReason !="" && txtDetails !="")){
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/apply_for_leave_check',
			data: {leave_type : leave_type, from_date : from_date, halfday1 : halfday1, to_date : to_date, halfday2 : halfday2, txtReason : txtReason, reportingTo : reportingTo, txtDetails : txtDetails},
			success: function(data)
			{
				var data = JSON.parse(data);
				//console.log(data.status);
				//console.log(data.nodays);
				if(data.status == '0'){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-8"><div class="alert alert-danger" role="alert"> Already applied in Date: '+from_date+' </div></div>');
					$('#from_date').attr('style', 'border-color: #f00000;');
					setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
				}
				else if(data.status == '1'){
					var type_lave ='SL';
					if(leave_type == 'P'){
						type_lave ='PL';
					}else if(leave_type == 'M'){
						type_lave ='ML';
					}
					$('.modal-body .headtitle1').html('<span style="color:#f00; font-size: 14px;" >Are you sure want to apply <b>'+type_lave+'</b> leave from Date: <b>'+from_date+' to '+to_date+'</b> of <b>'+data.nodays+' day </b>? </span>');
					$('#reportingTo1').val(reportingTo);
					$('#from_date1').val(from_date);
					$('#to_date1').val(to_date);
					$('#txtReason1').val(txtReason);
					$('#no_of_days').val(data.nodays);
					$('#myModal_leave').modal('show'); 
				}
				
		   }
		});
	}
	$('#loaderSection').hide();
}


function applyLeave(dis){
	var leave_type = $('#leave_type').val();
	var from_date = $('#from_date').val();
	var halfday1 = $('#halfday1').val();
	var to_date = $('#to_date').val();
	var halfday2 = $('#halfday2').val();
	var txtReason = $('#txtReason').val();
	var reportingTo = $('#reportingTo').val();
	var txtDetails = $('#txtDetails').val();
	var no_of_days = $('#no_of_days').val();
	var str="";
	if(leave_type == ""){
		$('#leave_type').attr('style', 'border-color: #f00000;');
	}
	if(from_date == ""){
		$('#from_date').attr('style', 'border-color: #f00000;');
	}
	if(to_date == ""){
		$('#to_date').attr('style', 'border-color: #f00000;');
	}
	if(txtReason.trim().length == 0){
		$('#txtReason').attr('style', 'border-color: #f00000;');
	}
	if(txtDetails == ""){
		$('#txtDetails').attr('style', 'border-color: #f00000;');
	}
	if((leave_type !="" && leave_type == "M" && from_date !="" && to_date !="" && txtDetails !="") || (leave_type !="" && from_date !="" && to_date !="" && txtReason !="" && txtDetails !="")){
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/apply_for_leave_submit',
			data: {leave_type : leave_type, from_date : from_date, halfday1 : halfday1, to_date : to_date, halfday2 : halfday2, txtReason : txtReason, reportingTo : reportingTo, txtDetails : txtDetails, no_of_days : no_of_days},
			success: function(data)
			{
				if(data == 1){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-8"><div class="alert alert-danger" role="alert"> Please Try Again </div></div>');
				}
				else if(data == 0){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-8"><div class="alert alert-danger" role="alert"> Already applied in Date: '+from_date+' </div></div>');
				}
				else{
					window.location.href = site_url+'timesheet/my_leave_application';
				}
				setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 2000);
		   }
		});
	}
	/* else{
		$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-8"><div class="alert alert-danger" role="alert"> Please fill all the mandetory fields </div></div>');
		setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
	} */
	$('#loaderSection').hide();
}

</script>