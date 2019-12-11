<link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/dubrox/Multiple-Dates-Picker-for-jQuery-UI/master/jquery-ui.multidatespicker.css">

<style>
.show-content{
	display: none;
}
.show-content-user {
	display: none;
}
.show-content-extra {
	display: none;
}
</style>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>  
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('timesheet/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9"> 
					<legend class="pkheader">Apply For Regularize</legend> 
					<div class="well">
						<div class="form-content page-content">
							<?php if($this->config->item('attendenceFreeze') == 'NO') { ?>
							<form action="<?= base_url('timesheet/apply_for_regularise')?>" method="POST" class="form-horizontal"  id="formSubmit" > 
								<?php if (validation_errors()) : ?> 
										<div class="alert alert-danger" role="alert">
											<?= validation_errors() ?>
										</div> 
								<?php endif; ?>
								<?php if (isset($error)) : ?> 
										<div class="alert alert-danger" role="alert">
											<?= $error ?>
										</div> 
								<?php endif; ?>
								<fieldset> 
									<div class="form-group">
										<label class="col-md-2 control-label" for="name">Reporting To</label>  
										<div class="col-md-6">
											<?php 
												$result = count($reporting_manager);
												for ($i = 0; $i < $result; $i++){?>
												<p class="reportingpk"><?php echo $reporting_manager[$i]['full_name'].' ('.$reporting_manager[$i]['loginhandle'].')';?></p>	
												<?php
												form_hidden('reporting_to', '"'.$reporting_manager[$i]['reporting_to'].'"');?>
												<input type="hidden" name="reportingTo" id="reportingTo" value="<?php echo $reporting_manager[$i]['reporting_to'] ?>"> 
											<?php }?> 
										</div>
									</div>
									<div class="form-group">
									
										<label class="col-md-2 control-label" for="name">From Date <span class="red">*</span></label>  
										<div class="col-md-4"> 
											<input id="from_date" name="from_date" type="text" placeholder="From Date" class="form-control input-md datepickerShowStartDate"  onfocus="removeError(this);" readonly ><i class="fa fa-calendar" aria-hidden="true" style="margin-right: 8px; margin-top: -28px;" id="datepickerImage"></i>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label" for="name">To Date <span class="red">*</span></label>  
										<div class="col-md-4">
											<input id="to_date" name="to_date" type="text" placeholder="To Date" class="form-control input-md form_datetime datepickerShowEndDate"  onfocus="removeError(this);" readonly >  <i class="fa fa-calendar" aria-hidden="true" style="margin-right: 8px; margin-top: -28px;" id="datepickerImageE"></i>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label" for="name">Regularize On Basis Of <span class="red">*</span></label>  
										<div class="col-md-6">
											<!--<textarea id="txtReason" name="txtReason" class="form-control" rows="5"  onfocus="removeError(this);"></textarea> -->
											<select id="txtReason" name="txtReason" class="form-control input-md" onchange="change_status(this);" onfocus="removeError(this);" >
												<option value="">---Select---</option>
												<option value="Delay By 1-15 Minutes">Delay By 1-15 Minutes</option>
												<option value="Biometric Failure">Biometric Failure</option>
												<option value="Forgot To Punch In">Forgot To Punch In</option>
												<option value="Forgot To Punch Out">Forgot To Punch Out</option>
												<option value="Forgot To Punch In & Punch Out">Forgot To Punch In & Punch Out</option>
												<option value="Due To Shift Change">Due To Shift Change</option>
												<option value="Extra Hour">Extra Hour</option>
												<option value="Declared By Company">Declared By Company</option>
												<option value="Compensatory Off">Compensatory Off</option>
												<option value="Tour">Tour</option>
											</select>
										</div>
									</div>
									<div class="form-group show-content">
										<label class="col-md-2 control-label" for="name">Date <span class="red">*</span></label>  
										<div class="col-md-4">
											<input id="reason_date" name="reason_date" type="text" placeholder="To Date" class="form-control input-md form_datetime datepickershowdate"  onfocus="removeError(this);" readonly >  <i class="fa fa-calendar" aria-hidden="true" style="margin-right: 8px; margin-top: -28px;" id="datepickerImageE"></i>
										</div>
									</div>
									
									<div class="form-group show-content-extra">
										<label class="col-md-2 control-label" for="name">Date <span class="red">*</span></label>  
										<div class="col-md-4">
											<input id="reason_date2" name="reason_date" type="text" placeholder="To Date" class="form-control input-md form_datetime multidatepickershowdate"  onfocus="removeError(this);" readonly >  <i class="fa fa-calendar" aria-hidden="true" style="margin-right: 8px; margin-top: -28px;" id="datepickerImageE"></i>
										</div>
										<label class="col-md-2 control-label" for="name">Hours <span class="red">*</span></label>  
										<div class="col-md-4">
											<input id="reason_hour" name="reason_hour" type="number" placeholder="Hours" class="form-control input-md"  onfocus="removeError(this);">
											
										</div>
									</div>
									
									<div class="form-group show-content-user">
										<label class="col-md-2 control-label" for="name">Date <span class="red">*</span></label>  
										<div class="col-md-4">
											<input id="reason_date1" name="reason_date" type="text" placeholder="To Date" class="form-control input-md form_datetime datepickershowdate"  onfocus="removeError(this);" readonly >  <i class="fa fa-calendar" aria-hidden="true" style="margin-right: 8px; margin-top: -28px;" id="datepickerImageE"></i>
											
										</div>
										
									</div>
									<div class="row submtSec"  style="margin-bottom: 20px;">
										<div class="form-group">
											<label class="col-md-2 control-label" for="signup"></label>
											<div class="col-md-6"> 
												<input type="button" id="btnApplyLeave" name="btnApplyLeave" class="btn btn-info pull-right" value="Apply"  onClick="confirmRegularization(this);"/>
											</div>
										</div>
										<div class="msg-sec"></div>
									</div>
								</fieldset>
							</form>
							
						<?php } else{ ?>
							<div class="col-md-12"> <h4 style="color: red;"> Please wait till Salary Generate...</h4></div>
						<?php } ?>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>


<!-- Modal -->
<div class="modal fade" id="myModal_regularization" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Confirm Regularize Application Details</h4>
			</div>
			<div class="modal-body">
				<div class="resource_box">
					<div class="headtitle1"></div>
					<input type="hidden" name="reportingTo" id="reportingTo1" value=""> 
					<input type="hidden" name="from_date" id="from_date1" value=""> 
					<input type="hidden" name="to_date" id="to_date1" value=""> 
					<input type="hidden" name="txtReason" id="txtReason1" value=""> 
					<input type="hidden" name="no_of_days" id="no_of_days" value=""> 
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" name="conf_submit"  class="btn btn-success"  onClick="return applyRegularization(this);" >Yes</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->

<script src="https://cdn.rawgit.com/dubrox/Multiple-Dates-Picker-for-jQuery-UI/master/jquery-ui.multidatespicker.js"></script>
<script type="text/javascript">
 $(document).ready(function() {
    
    $("#datepickerImage").click(function() {
       $(".datepickerShowStartDate").datepicker( "show" );
    });
    $("#datepickerImageE").click(function() {
       $(".datepickerShowEndDate").datepicker( "show" );
    });
	
	
 });
 
 function change_status(dis)
 {
	 if($(dis).val() == "Compensatory Off")
	 {
		 $('.show-content-extra').css('display', 'none');
		 $('.show-content-user').css('display', 'none');
		 $('.show-content').css('display', 'block');
	 }
	 else if($(dis).val() == "Tour"){
		 $('.show-content-user').css('display', 'block');
		 $('.show-content').css('display', 'none');
	 }
	 else if($(dis).val() == "Extra Hour")
	 {
		 $('.show-content-extra').css('display', 'block');
		 $('.show-content-user').css('display', 'none');
		 $('.show-content').css('display', 'none');
	 }
	 else{
		 $('.show-content-extra').css('display', 'none');
		 $('.show-content-user').css('display', 'none');
		 $('.show-content').css('display', 'none');
	 }
 }

</script>