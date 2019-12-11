<script>
 var site_url = '<?php echo base_url(); ?>';
$(document).ready(function(){
	$('.datepickerShowStartDate').datepicker({
        dateFormat: 'dd-mm-yy',
		onSelect: function(selected) {
			$(".datepickerShowEndDate").datepicker("option","minDate", selected)
        }
	});
	$('.datepickerShowEndDate').datepicker({
        dateFormat: 'dd-mm-yy',
		onSelect: function(selected) {
           $(".datepickerShowStartDate").datepicker("option","maxDate", selected)
        }
	});
});

function checkLeave(dis){
	var leave_type = $(dis).val();
	var str="";
	if(leave_type != ""){
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/chek_leave_availability',
			dataType: "json",
			data: {leave_type : leave_type},
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

function applyLeave(dis){
	var leave_type = $('#leave_type').val();
	var from_date = $('#from_date').val();
	var halfday1 = $('#halfday1').val();
	var to_date = $('#to_date').val();
	var halfday2 = $('#halfday2').val();
	var txtReason = $('#txtReason').val();
	var reportingTo = $('#reportingTo').val();
	var txtDetails = $('#txtDetails').val();
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
	if(leave_type !="" && from_date !="" && to_date !="" && txtReason !="" && txtDetails !=""){
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/apply_for_leave_submit',
			data: {leave_type : leave_type, from_date : from_date, halfday1 : halfday1, to_date : to_date, halfday2 : halfday2, txtReason : txtReason, reportingTo : reportingTo, txtDetails : txtDetails},
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
				setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
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