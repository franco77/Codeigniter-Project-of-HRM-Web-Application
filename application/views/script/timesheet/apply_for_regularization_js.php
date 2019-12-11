<script>
 var site_url = '<?php echo base_url(); ?>';
 var arr = [];
$(document).ready(function(){
	var currentTime = new Date();
	// First Date Of the month 
	var startDateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth(),1);
	// Last Date Of the Month 
	var startDateTo = new Date(currentTime.getFullYear(),currentTime.getMonth() +1,0);
	
	var minDate = new Date(currentTime.getFullYear(), currentTime.getMonth(), -300, +0); //one day next before month
	//var maxDate =  new Date(currentTime.getFullYear(), currentTime.getMonth() +2, +0); // one day before next month
	
	$('.datepickerShowStartDate').datepicker({
        dateFormat: 'dd-mm-yy',
		minDate: startDateFrom,
		maxDate: 0,
		onSelect: function(selected) {
			$(".datepickerShowEndDate").datepicker("option","minDate", selected)
        }
	});
	$('.datepickerShowEndDate').datepicker({
        dateFormat: 'dd-mm-yy',
		minDate: startDateFrom,
		maxDate: 0,
		onSelect: function(selected) {
           $(".datepickerShowStartDate").datepicker("option","maxDate", selected)
        }
	});
	$('.datepickershowdate').datepicker({
        dateFormat: 'dd-mm-yy',
		minDate: minDate,
		maxDate: 0
	});
	
	
	$('.multidatepickershowdate').multiDatesPicker({
		maxDate: 0,
        dateFormat: 'dd-mm-yy',
		onSelect: function(datetext) {
			//console.log(datetext);
			arr.push(datetext); 
		}
	});
	
	
});


function confirmRegularization(dis){
	$('#myModal_regularization').modal('hide'); 
	$('.modal-body .headtitle1').html('');
	var reportingTo = $('#reportingTo').val();
	var from_date = $('#from_date').val();
	var to_date = $('#to_date').val();
	var txtReason = $('#txtReason').val();
	var reason_date = $('#reason_date').val(); console.log(reason_date);
	var str="";
	var status = true;
	if(from_date == ""){
		$('#from_date').attr('style', 'border-color: #f00000;');
	}
	if(to_date == ""){
		$('#to_date').attr('style', 'border-color: #f00000;');
	}
	if(txtReason == ""){
		$('#txtReason').attr('style', 'border-color: #f00000;');
	}
	if((txtReason == "Company off") && ($('#reason_date').val() == "")){
		$('#reason_date').attr('style', 'border-color: #f00000;');
	}
	if((txtReason == "Extra Hour") && ($('#reason_date2').val() == "")&& ($('#reason_hour').val() == ""))
	{
		status = false;
		$('#reason_date2').attr('style', 'border-color: #f00000;');
		$('#reason_hour').attr('style', 'border-color: #f00000;');
	}
	if((txtReason == "Tour") && ($('#reason_date1').val() == "") )
	{
		status = false;
		$('#reason_date1').attr('style', 'border-color: #f00000;');
	}
	if(from_date !="" && to_date !="" && txtReason !="" && status == true){
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/apply_for_regularise_check',
			data: {from_date : from_date, to_date : to_date, txtReason : txtReason, reportingTo : reportingTo},
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
					$('.modal-body .headtitle1').html('<span style="color:#f00; font-size: 16px;" >Are you sure want to regularize from date: <b>'+from_date+' to '+to_date+'</b> of <b>'+data.nodays+' day </b> ? </span>');
					$('#no_of_days').val(data.nodays);
					$('#myModal_regularization').modal('show'); 
				}
				
		   }
		});
	}
	$('#loaderSection').hide();
}



function applyRegularization(dis){console.log(reason_date);
	$('#myModal_regularization').modal('hide');
	var reasondate = "";	
	var reportingTo = $('#reportingTo').val();
	var from_date = $('#from_date').val();
	var to_date = $('#to_date').val();
	var txtReason = $('#txtReason').val();
	var no_of_days = $('#no_of_days').val();
	var reason_date = $('#reason_date').val();
	var reason_time = $('#reason_hour').val() || 0;
	var str="";
	if(txtReason == "Extra Hour")
	{
		reasondate = $('#reason_date2').val();
	}
	else if(txtReason == "Tour"){
		reasondate = $('#reason_date1').val();
	}
	if(from_date !="" && to_date !="" && txtReason !=""){
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/apply_for_regularise_submit',
			data: {from_date : from_date, to_date : to_date, txtReason : txtReason, reportingTo : reportingTo, no_of_days : no_of_days, reason_date: reasondate, reason_time: reason_time},
			success: function(data)
			{
				
				if(data == 2){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-8"><div class="alert alert-danger" role="alert"> Please Try Again </div></div>');
					setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
				}
				else if(data == 3){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-8"><div class="alert alert-danger" role="alert"> Already applied in Date: '+from_date+' </div></div>');
					$('#from_date').attr('style', 'border-color: #f00000;');
					setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
				}
				else{
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-8"><div class="alert alert-success" role="alert"> Applied successfully From Date: '+from_date+' - '+to_date+' </div></div>');
					setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); window.location.href = site_url+'timesheet/my_regularise_application'; }, 2000);
					
				}
				
		   }
		});
	}
	$('#loaderSection').hide();
}

</script>