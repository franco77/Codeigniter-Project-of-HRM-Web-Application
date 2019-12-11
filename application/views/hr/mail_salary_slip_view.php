<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">Mail Salary Slip(<small>Please select a month & year.</small>)</legend> 
					<div class="row well">
						<form id="frmPhoneAdd" name="frmPhoneAdd" method="POST" action="">
							<div class="box-body pad"> 
								<div class="form-group">
									<label class="col-md-4 control-label" for="name">Mail Salary Slip For the Month of </label>  
									<div class="col-md-2"> 
										<select id="selMonth" name="selMonth" class="form-control" id="sel1">
											<option value="">Select</option>
											<option value="01" <?php if($this->input->post('selMonth') == '01') echo 'selected="selected"';?>>January</option>
											<option value="02" <?php if($this->input->post('selMonth') == '02') echo 'selected="selected"';?>>February</option>
											<option value="03" <?php if($this->input->post('selMonth') == '03') echo 'selected="selected"';?>>March</option>
											<option value="04" <?php if($this->input->post('selMonth') == '04') echo 'selected="selected"';?>>April</option>
											<option value="05" <?php if($this->input->post('selMonth') == '05') echo 'selected="selected"';?>>May</option>
											<option value="06" <?php if($this->input->post('selMonth') == '06') echo 'selected="selected"';?>>June</option>
											<option value="07" <?php if($this->input->post('selMonth') == '07') echo 'selected="selected"';?>>July</option>
											<option value="08" <?php if($this->input->post('selMonth') == '08') echo 'selected="selected"';?>>August</option>
											<option value="09" <?php if($this->input->post('selMonth') == '09') echo 'selected="selected"';?>>September</option>
											<option value="10" <?php if($this->input->post('selMonth') == '10') echo 'selected="selected"';?>>October</option>
											<option value="11" <?php if($this->input->post('selMonth') == '11') echo 'selected="selected"';?>>November</option>
											<option value="12" <?php if($this->input->post('selMonth') == '12') echo 'selected="selected"';?>>December</option> 
										</select>									  
									</div>
									<div class="col-md-2"> 
										<select id="selYear" name="selYear" class="form-control" id="sel1">
											<option value="">Select</option>
											<?php for($i=2014; $i<=(date('Y'));$i++){ ?>
											<option value="<?php echo $i;?>" <?php if($this->input->post('selYear') == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
											<?php } ?> 
										</select>									  
									</div>
									<div class="col-md-2">
										<input type="submit" id="mailSalarySlip" name="mailSalarySlip" class="btn btn-info pull-right" value="SEND" <?php if($this->config->item('mailSalarySlipFreeze') == 'YES'){ echo "disabled"; } ?> /> 
									</div>
									<div class="col-md-12">
										<?php if($successMsg !=""){ ?>
										<div class="alert alert-success" role="alert"> <?php echo $successMsg; ?> </div>
										<?php } ?>
									</div>
								</div> 
							</div> 
						</form>   
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
 

