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
				<div class="col-md-9 center-xs">
					<legend class="pkheader">Direct Leave application <small>(Apply & approve leave for employee)</small></legend> 
					<div class="row well"> 
						<div class="form-group">
							<label class="col-md-2 control-label" for="name">Employee For</label>  
							<div class="col-md-6">
								<!--<input id="dp1" name="from_date" type="text" placeholder="Employee For" class="form-control input-md"> -->
								<select  name="employee" id="employee"  class="selectpicker form-control" data-live-search="true" required="" onchange="getEmpLeaveDetails(this);" onfocus="removeError(this);" >
									<option value=""  >--- Select ---</option>
									<?php 
									 for($l=0; $l < count($emoInfo); $l++) 
									{?>
										<option value="<?php echo $emoInfo[$l]['login_id']; ?>"  ><?php echo $emoInfo[$l]['name'].' ('.$emoInfo[$l]['loginhandle'].')'; ?></option>	
									<?php }  ?>
								</select>										
							</div>
						</div>
						<div class="col-md-12 leaveSec" style="display:none; border-top: 1px solid #ccc; margin-top:20px;">
							
							<form class="form-horizontal" id="formSubmit"  method="POST"> 
								<fieldset> 
									<div class="row" style="margin-top:20px;">
										<div class="col-md-12">
											<label class="col-md-2 control-label" for="emp code">Emp Code :</label>  
											<div class="col-md-4">
												<P id="myEmpCode"></P> 
											</div>
											<label class="col-md-2 control-label" for="name">Name :</label>  
											<div class="col-md-4">
												<P id="myName"></P> 
											</div>
										</div>
										<div class="col-md-12">											
											<label class="col-md-2 control-label" for="available pl">Available PL :</label>  
											<div class="col-md-4">
												<button class="btn btn-circle-sm btn-primary" data-content="Default popover" title="Planned Leave" data-toggle="popover" type="button" style="padding: 12px 35px;" id="avlPL"></button>  
											</div>
											<label class="col-md-2 control-label" for="available sl">Available SL :</label>  
											<div class="col-md-4">
												<button class="btn btn-circle-sm btn-primary" data-content="Default popover" title="Sick Leave" data-toggle="popover" type="button"  style="padding: 12px 35px;" id="avlSL"></button> 
											</div>
										</div>
											 
									</div>
									<hr>
									<div class="row">
									<div class="col-md-12">
									<div class="form-group">
										<label class="col-md-2 control-label" for="name">Leave Type : <span class="red">*</span></label>  
										<div class="col-md-4">
											<select class="form-control" id="leave_type" name="leave_type" onchange="checkLeave(this);" required="" >
												<option value="">Select</option>
												<option value="P">PL</option>
												<option value="S">SL</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label" for="name">Leave From : <span class="red">*</span></label>  
										<div class="col-md-2">
											<input id="from_date" name="from_date" type="text" placeholder="Leave from" class="form-control input-md datepickerShowStartDate" onfocus="removeError(this);" readonly  required=""> 
										</div>
										<label class="col-md-2 control-label" for="name">Half Day :</label>  
										<div class="col-md-2">
											<select class="form-control" id="halfday1" name="halfday1">
												<option value="N">Select</option>
												<option value="Y">2nd Half</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label" for="name">Leave To : <span class="red">*</span></label>  
										<div class="col-md-2">
											<input id="to_date" name="to_date" type="text" placeholder="Leave to" class="form-control input-md datepickerShowEndDate"  onfocus="removeError(this);" readonly  required=""> 
										</div>
										<label class="col-md-2 control-label" for="name">Half Day :</label>  
										<div class="col-md-2">
											<select class="form-control" id="halfday2" name="halfday2">
												<option value="N">Select</option>
												<option value="Y">1st Half</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label" for="name">Reason For Absence : <span class="red">*</span></label>  
										<div class="col-md-6">
											<textarea class="form-control" rows="5" id="txtReason" name="txtReason"  onfocus="removeError(this);"  required="" ></textarea> 
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label" for="name">Reporting To :</label>  
										<div class="col-md-4">
											<span id="reportingName"></span>
												<input type="hidden" name="reporting" id="reporting" value="">
										</div>
									</div> 
									<div class="form-group">
										<label class="col-md-2 control-label" for="name">Contact Details During absence <span class="red">*</span></label>  
										<div class="col-md-6">
											<textarea class="form-control" rows="5" id="txtDetails" name="txtDetails" id="txtDetails"  onfocus="removeError(this);"  required=""></textarea> 
										</div>
									</div>
									<div class="row submtSec"  style="margin-bottom: 20px;">
										<div class="msg-sec"></div>
										<div class="form-group">
											<label class="col-md-6 control-label" for="signup"></label>
											<div class="col-md-2">
											<input type="hidden" name="login_id" id="login_id" >
												<input type="button" id="leaveApply" name="leaveApply" class="btn btn-primary" value="Apply" onClick="$('#loaderSection').show(); applyLeave(this);" disabled >
											</div>
										</div>
									</div>
									</div>
									</div>
								</fieldset>
							</form> 
						</div>
					</div>
					
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>