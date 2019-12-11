<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>  
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3">
					<div class="form-content">
						<?php $this->load->view('timesheet/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">Leave Application</legend>
					<div class="row well"> 
						<?php if($this->config->item('attendenceFreeze') == 'NO') { ?>
						<form class="form-horizontal" id="formSubmit"  method="POST"> 
							<fieldset> 
								<div class="row">
									<?php 
										$result = count($detail_leave);
										for ($i = 0; $i < $result; $i++)
										{?>
											<div class="col-md-12">
												<label class="col-md-2 control-label" for="emp code">Emp Code :</label>  
												<div class="col-md-4">
													<P><?php echo $detail_leave[$i]['myEmpCode']?></P> 
												</div>
												<label class="col-md-2 control-label" for="name">Name :</label>  
												<div class="col-md-4">
													<P><?php echo $detail_leave[$i]['myName']?></P> 
												</div>
											</div>
										<?php } ?> 
											<div class="col-md-12">											
												<label class="col-md-2 control-label" for="available pl">Available PL :</label>  
												<div class="col-md-4">
													<button class="btn btn-circle-sm btn-primary" data-content="Default popover" title="Planned Leave" data-toggle="popover" type="button" style="padding: 12px 35px;"><?php echo $avlPL?></button>  
													<input type="hidden" id="avlPL" name="avlPL"  value="<?php echo $avlPL?>" >
												</div>
												<label class="col-md-2 control-label" for="available sl">Available SL :</label>  
												<div class="col-md-4">
													<button class="btn btn-circle-sm btn-primary" data-content="Default popover" title="Sick Leave" data-toggle="popover" type="button"  style="padding: 12px 35px;"><?php echo $avlSL?></button> 
													<input type="hidden" id="avlSL" name="avlSL"  value="<?php echo $avlSL?>" >
												</div>
											</div>
										 
								</div>
								<hr>
								<div class="row">
								<div class="col-md-12">
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
								<div class="form-group">
									<label class="col-md-2 control-label" for="name">Leave Type : <span class="red">*</span></label>  
									<div class="col-md-4">
										<select class="form-control" id="leave_type" name="leave_type" onchange="checkLeave(this);" required="" >
											<option value="">Select</option>
											<option value="P">PL</option>
											<option value="S">SL</option>
											<option value="M">ML</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" for="name">Leave From : <span class="red">*</span></label>  
									<div class="col-md-4">
										<input id="from_date" name="from_date" type="text" placeholder="Leave from" class="form-control input-md datepickerShowStartDate" onfocus="removeError(this);" autocomplete="off"  required=""> <i class="fa fa-calendar" aria-hidden="true" style="margin-right: 8px; margin-top: -28px;" id="datepickerImage"></i>
									</div>
									<label class="col-md-2 control-label" for="name">Half Day :</label>  
									<div class="col-md-2">
										<select class="form-control" id="halfday1" name="halfday1" onchange="return checkLeaveCount();">
											<option value="N">Select</option>
											<option value="Y">2nd Half</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" for="name">Leave To : <span class="red">*</span></label>  
									<div class="col-md-4">
										<input id="to_date" name="to_date" type="text" placeholder="Leave to" class="form-control input-md datepickerShowEndDate"  onfocus="removeError(this);" autocomplete="off"  required=""> <i class="fa fa-calendar" aria-hidden="true" style="margin-right: 8px; margin-top: -28px;" id="datepickerImageE"></i>
									</div>
									<label class="col-md-2 control-label" for="name">Half Day :</label>  
									<div class="col-md-2">
										<select class="form-control" id="halfday2" name="halfday2"  onchange="return checkLeaveCount();">
											<option value="N">Select</option>
											<option value="Y">1st Half</option>
										</select>
									</div>
								</div>
								<div class="form-group reason">
									<label class="col-md-2 control-label" for="name">Reason For Absence : <span class="red">*</span></label>  
									<div class="col-md-8">
										<textarea class="form-control" rows="5" id="txtReason" name="txtReason"  onfocus="removeError(this);"  required="" onchange="checkLeaveCount();" ></textarea> 
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" for="name">Reporting To :</label>  
									<div class="col-md-8">
										<?php 
											$result = count($detail_leave);
											for ($i = 0; $i < $result; $i++){
											echo $detail_leave[$i]['rmName'].' ('.$detail_leave[$i]['rmEmpCode'].')';	
											?>
											<?= form_hidden('reporting_to', '"'.$detail_leave[$i]['reporting_to'].'"');?>
											<input type="hidden" name="reportingTo" id="reportingTo" value="<?php echo $detail_leave[$i]['reporting_to'] ?>">
										<?php }?> 
									</div>
								</div> 
								<div class="form-group">
									<label class="col-md-2 control-label" for="name">Contact Details During absence <span class="red">*</span></label>  
									<div class="col-md-8">
										<textarea class="form-control" rows="5" id="txtDetails" name="txtDetails" id="txtDetails"  onfocus="removeError(this);"  required=""></textarea> 
									</div>
								</div>
								<div class="row submtSec"  style="margin-bottom: 20px;">
									<div class="msg-sec"></div>
									<div class="form-group">
										<label class="col-md-8 control-label submtSec" for="signup"><?php if($submitMSG !=""){ ?><div class="alert alert-danger" role="alert"><?php echo $submitMSG ;  ?></div><?php } ?></label>
										<div class="col-md-2">
											<input type="button" id="leaveApply" name="leaveApply" class="btn btn-primary" value="Apply" onClick="$('#loaderSection').show(); confirmLeave(this);" disabled >
										</div>
									</div>
								</div>
								</div>
								</div>
							</fieldset>
						</form> 
						<?php } else{ ?>
							<div class="col-md-12"> <h4 style="color: red;"> Please wait till Salary Generate...</h4></div>
						<?php } ?>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>

<!-- Modal -->
<div class="modal fade" id="myModal_leave" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Confirm Leave Application Details</h4>
			</div>
			<div class="modal-body">
				<div class="resource_box">
					<div class="headtitle1"></div>
					<input type="hidden" name="no_of_days" id="no_of_days" value=""> 
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" name="conf_submit"  class="btn btn-success"  onClick="applyLeave(this);" >Yes</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->

<script type="text/javascript">
 $(document).ready(function() {
    
    $("#datepickerImage").click(function() {
       $(".datepickerShowStartDate").datepicker( "show" );
    });
    $("#datepickerImageE").click(function() {
       $(".datepickerShowEndDate").datepicker( "show" );
    });
 });
 
</script>