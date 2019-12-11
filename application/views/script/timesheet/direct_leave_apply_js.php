
<!--<script type="text/javascript" src="<?php echo base_url(); ?>src/js/jquery.autocomplete.js"></script> -->
<script type="text/javascript">
var site_url = '<?php echo base_url(); ?>';
$(document).ready(function(){
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});

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



function getEmpLeaveDetails(dis){
	var login_id = $(dis).val();
	$(dis).parents('.submtSec').find('.msg-sec').html("");
	if(login_id ==""){
		$('#employee').attr('style', 'border-color: #f00000;');
	}
	$('.leaveSec').hide();
	if(login_id !=""){
		$('#login_id').val(login_id);
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/get_direct_leave_emp_details',
			data: {login_id : login_id},
			success: function(data)
			{
				var data = JSON.parse(data);
				$('#login_id').val(data.login_id);
				$('#myEmpCode').html(data.loginhandle);
				$('#myName').html(data.full_name);
				$('#avlPL').html(data.avlPL);
				$('#avlSL').html(data.avlSL);
				$('#reporting').val(data.reporting_login_id);
				$('#reportingName').html(data.reporting_full_name+' ('+ data.reporting_loginhandle+ ')');
				$('.leaveSec').show();
		   }
		});
	}/* 
	else{
		$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please Please select Employee </div></div>');
	} */
}
	

function checkLeave(dis){
	var leave_type = $(dis).val();
	var login_id = $('#login_id').val();
	var str="";
	if(leave_type != ""){
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/chek_leave_availability_direct',
			dataType: "json",
			data: {leave_type : leave_type, login_id : login_id},
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
	var login_id = $('#login_id').val();
	var leave_type = $('#leave_type').val();
	var from_date = $('#from_date').val();
	var halfday1 = $('#halfday1').val();
	var to_date = $('#to_date').val();
	var halfday2 = $('#halfday2').val();
	var txtReason = $('#txtReason').val();
	var reporting = $('#reporting').val();
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
			url: site_url+'timesheet/direct_apply_for_leave_submit',
			data: {leave_type : leave_type, from_date : from_date, halfday1 : halfday1, to_date : to_date, halfday2 : halfday2, txtReason : txtReason, reporting : reporting, txtDetails : txtDetails, login_id: login_id},
			success: function(data)
			{
				
				if(data == 1){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-8"><div class="alert alert-danger" role="alert"> Please Try Again </div></div>');
					setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
				}
				else if(data == 0){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-8"><div class="alert alert-danger" role="alert"> Already applied in Date: '+from_date+' </div></div>');
					setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
				}
				else{
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-8"><div class="alert alert-success" role="alert"> Successfully Applied on Date: '+from_date+' </div></div>');
					//window.location.href = site_url+'timesheet/leave_app_mgt_emp';
					setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); window.location.href = site_url+'timesheet/leave_app_mgt_emp'; }, 4000);
				}
				
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