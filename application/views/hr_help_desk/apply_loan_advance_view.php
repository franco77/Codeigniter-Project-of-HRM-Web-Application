<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">Apply For Loan & Advance</legend> 
					<div class="row well">
						<form class="form-horizontal" id="frmSalaryUpdate" name="frmSalaryUpdate" method="POST" action="" enctype="multipart/form-data">
						  <fieldset> 
							<div class="form-group">
								<label class="col-md-2 control-label" for="name">Apply For</label>  
								<div class="col-md-4"> 
									<select id="txtapplyfor" name="txtapplyfor" class="form-control" required="">
										<option value="">Select</option>
										<option value="advance" >Advance</option>
										<option value="loan" >Loan</option> 
									</select>									  
								</div>
							</div> 
							<div class="form-group">
								<label class="col-md-2 control-label" for="name">Eligible Amount</label>  
								<div class="col-md-4"> 
									<select id="txtadvanceamount" name="txtadvanceamount" class="form-control">
										<option value="">Select</option>
										<?php if($cur_date >=$startDate && $cur_date <= $midDate){ ?> 
											<option value="30" >30% of Gross Salary</option>
										<?php } if($cur_date > $midDate && $cur_date <= $endDate){?>
											<option value="75" >75% of Gross Salary</option>                       
										<?php } ?> 
									</select>
									<select id="txtloanamount" name="txtloanamount" class="form-control">
										<option value="">Select</option>
										<?php if($noofyears >= 1){ ?>  
										<option value="1" > 1month Gross Salary</option>
									   <?php } if( $noofyears >= 2){ ?> 
										<option value="2" > 2months Gross Salary</option>  
									   <?php } if($noofyears >= 3){ ?>  
										<option value="3" > 3months Gross Salary</option>
									   <?php } ?> 
									</select>
								</div>
							</div> 									
							<div class="form-group">
							  <label class="col-md-2 control-label" for="name">Applied Amount</label>  
							  <div class="col-md-4">
							  <input type="text" id="txtamountapplied" name="txtamountapplied" onkeypress="return IsNumeric(event);" placeholder="Apply Amount" class="form-control input-md"  required="">
								<span id="error" style="color: Red; display: none">* Input digits (0 - 9)</span>							  
							  </div>
							</div> 
							<div class="form-group">
								<label class="col-md-2 control-label" for="name">No of instalments</label>  
								<div class="col-md-4"> 
									<select id="txtadvanceinstalment" name="txtadvanceinstalment" class="form-control"> 
										<option value="">Select</option>
										<?php for($i=1; $i<=$noofinstalment;$i++){ ?>
										<option value="<?php echo $i;?>"><?php echo $i;?></option>
										<?php } ?> 
									</select>
									<select id="txtloaninstalment" name="txtloaninstalment" class="form-control">
											<option value="">Select</option>
											<?php for($i=1; $i<=12;$i++){ ?>
											<option value="<?php echo $i;?>"><?php echo $i;?></option>
											<?php } ?> 
									</select>											
								</div>
							</div> 
							<div class="form-group">
							  <label class="col-md-2 control-label" for="name">Purpose Of</label>  
							  <div class="col-md-6">
							  <textarea class="form-control" rows="5" name="txtmessage" id="txtmessage"  required=""></textarea> 
							  </div>
							</div> 
							<div class="form-group">
							  <label class="col-md-2 control-label" for="signup"></label>
							  <div class="col-md-6">
								<span style="color: Red;"><?php echo $apply_msg;?> </span>
								<span style="color: #5bc0de;"><?php echo $apply_msg_scs;?> </span>
								<input type="submit" id="btnAddMessage" name="btnAddMessage" class="btn btn-info pull-right" value="APPLY" /> 
							  </div>
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
