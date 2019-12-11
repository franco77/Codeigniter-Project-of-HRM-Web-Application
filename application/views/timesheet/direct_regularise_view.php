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
					<legend class="pkheader">Direct Leave Application <small>(Leave application details)</small></legend>
					<div class="row well">
						<form action="<?= base_url('timesheet/apply_for_regularise'); ?>" method="POST" class="form-horizontal" > 
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
									<label class="col-md-2 control-label" for="name">Employee For</label>  
									<div class="col-md-4">
										<!--<input id="dp1" name="from_date" type="text" placeholder="Employee For" class="form-control input-md"> -->
										<select  name="employee" id="employee"  class="selectpicker form-control" data-live-search="true" required="" onchange="getReportingManager(this);" onfocus="removeError(this);" >
											<option value=""  >--- Select ---</option>
											<?php 
											 for($l=0; $l < count($emoInfo); $l++) 
											{?>
												<option value="<?php echo $emoInfo[$l]['login_id']; ?>"  ><?php echo $emoInfo[$l]['name'].' ('.$emoInfo[$l]['loginhandle'].')'; ?></option>	
											<?php }  ?>
										</select>										
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" for="name">Reporting Manager</label>  
									<div class="col-md-4">
										<input id="reporting" name="reporting" type="hidden" placeholder="Reporting Manager Name" class="form-control input-md" >  
										<input id="reporting_name" name="reporting_name" type="text" placeholder="Reporting Manager Name" class="form-control input-md "  required="" readonly onfocus="removeError(this);">  
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" for="name">From Date</label>  
									<div class="col-md-4">
										<input id="from_date" name="from_date" type="text" placeholder="From Date" class="form-control input-md datepickerShowStartDate"  required="" onfocus="removeError(this);"> 
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" for="name">To Date</label>  
									<div class="col-md-4">
										<input id="to_date" name="to_date" type="text" placeholder="To Date" class="form-control input-md datepickerShowEndDate"  required="" onfocus="removeError(this);"> 
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label" for="name">Reason</label>  
									<div class="col-md-6">
										<textarea id="txtReason" name="txtReason" class="form-control" rows="5"  required="" onfocus="removeError(this);"></textarea> 
									</div>
								</div>
								<div class="row submtSec"  style="margin-bottom: 20px;">
									<div class="form-group">
										<label class="col-md-2 control-label" for="signup"></label>
										<div class="col-md-6"> 
											<input type="button" id="btnRegularise" name="btnRegularise" class="btn btn-primary" value="Submit" onClick="$('#loaderSection').show(); applyRegularization(this);" />
										</div>
									</div>
									<div class="msg-sec"></div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>

