
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



function getReportingManager(dis){
	var login_id = $(dis).val();
	$(dis).parents('.submtSec').find('.msg-sec').html("");
	if(login_id !=""){
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/get_repoting_manager_for_emp',
			data: {login_id : login_id},
			success: function(data)
			{
				var data = JSON.parse(data);
				$('#reporting').val(data[0].login_id);
				$('#reporting_name').val(data[0].full_name+' ('+ data[0].loginhandle+ ')');
		   }
		});
	}
	else{
		$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please Please select Employee </div></div>');
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
	$('#loaderSection').hide();
}


function applyRegularization(dis){
	var employee = $('#employee').val();
	var reporting = $('#reporting').val();
	var reporting_name = $('#reporting_name').val();
	var from_date = $('#from_date').val();
	var to_date = $('#to_date').val();
	var txtReason = $('#txtReason').val();
	var str="";
	if(employee == ""){
		$('#employee').attr('style', 'border-color: #f00000;');
	}
	if(reporting_name == ""){
		$('#reporting_name').attr('style', 'border-color: #f00000;');
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
	if(employee !="" && reporting_name !="" && from_date !="" && to_date !="" && txtReason !=""){
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/directly_apply_for_regulaization_submit',
			data: {employee : employee, from_date : from_date, reporting : reporting, to_date : to_date, txtReason : txtReason},
			success: function(data)
			{
				
				if(data == 1){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-8"><div class="alert alert-danger" role="alert"> Please Try Again </div></div>');
				}
				else if(data == 0){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-8"><div class="alert alert-success" role="alert"> Regularize applied in Date: '+from_date+' successfully. </div></div>');
				}
				setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); location.reload(); }, 5000);
			}
		});
	}
	$('#loaderSection').hide();
}

 </script>