
<!--<script type="text/javascript" src="<?php echo base_url(); ?>src/js/jquery.autocomplete.js"></script> -->
<script type="text/javascript">
var site_url = '<?php echo base_url(); ?>';
$(document).ready(function(){
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});

	$("#reportingAC").autocomplete({
		data: site_url+'timesheet/getReportingManager',
		onItemSelect: function(item) {
		    if (item.data.length) {
		    	$("#reporting").val(item.data.join(' '));
		    }
		},
		onNoMatch: function(){
			$("#reportingAC").addClass('error');
			$("#reporting").val("");
		},
		maxItemsToShow: 5
	});

});



function changeReportingManager(dis){
	var getURLIdVal = $('#getURLIdVal').val();
	var reporting = $('#reporting').val();
	$(dis).parents('.submtSec').find('.msg-sec').html("");
	if(reporting !=""){
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/update_reporting_manager_user'+getURLIdVal,
			data: {reporting : reporting},
			success: function(data)
			{
				if(data == 1){
					$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Updated successfully </div></div>');
					setTimeout(function(){ $(dis).parents('.submtSec').find('.msg-sec').html(""); }, 3000);
				}
				else{
					location.reload();
				}
		   }
		});
	}
	else{
		$(dis).parents('.submtSec').find('.msg-sec').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please fill all the fields </div></div>');
	}
}
	
	
	/* $k('#btnChangeReporting').click(function(){
		var reportingID = $k('#reporting').val();
		var reportingName = $k("#reportingAC").val();
                var user_id = <?php echo $_REQUEST['user_id'] ?>;
		if(reportingID != ''){
		$k.ajax({
			type: "POST",
			url: 'ajax/change_reporting.php',
			data: 'reporting='+reportingID+'&user_id='+user_id,
			success: function(data) {
				if(from == 'regular'){
					//$k('#reportingDiv').html(reportingName+'<input type="hidden" name="reportingTo" value="'+reportingID+'" />');
					//jQuery(".openColorBox").colorbox.close("RM changed succesfully");
                                        jQuery(".openColorBox").colorbox.close();
                                        $k("#successMessage").html("Reporting Manager has been changed succesfully.").slideDown().delay(4000).slideUp();					
				}else if(from == 'leave'){
					//$k('#reportingDiv').html(reportingName+'<input type="hidden" name="reportingTo" value="'+reportingID+'" />');
					//jQuery(".openColorBox").colorbox.close("RM changed succesfully");
                                        jQuery(".openColorBox").colorbox.close();
                                        $k("#successMessage").html("Reporting Manager has been changed succesfully.").slideDown().delay(4000).slideUp();					
				}else{
					$k.colorbox({scrolling:false, overlayClose: false, escKey: false, opacity: 0.8, href:"../images/under_construction.jpg"});
				}
			},
			error: function(e) {
				alert("There is somme error in the network. Please try later.");
			}
		});
		}else{
			$k('#reportingAC').addClass('error');
		}
	}); */

 </script>