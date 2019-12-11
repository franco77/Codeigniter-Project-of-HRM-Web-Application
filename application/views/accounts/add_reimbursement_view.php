<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('accounts/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">Reimbursement(<small>Define Reimbursement of Here </small>)</legend>
					<div class="row well">
						<div class="col-md-12"> <h4><?php echo $empDetails[0]['full_name'].' ( '.$empDetails[0]['loginhandle'] .' )'; ?></h4></div>
						 <form id="frmSalaryUpdate" name="frmSalaryUpdate" method="POST" action="" enctype="multipart/form-data"> 
						<?php
							if($success_msg != '') { ?>
							<div class="col-md-12"><div class="alert alert-success" role="alert"> <?php echo $success_msg; ?> </div>
							<?php } else if($error_msg != '') { ?>
							<div class="col-md-12"><div class="alert alert-danger" role="alert"> <?php echo $error_msg; ?> </div>
							<?php } ?>
							<table class="table table-striped table-bordered table-condensed">		
                                <tr>
									<td width="20%"><strong>Reimbursement For the Month of </strong></td>
									<td width="60%">
										
										<select id="selMonth" name="selMonth" class="form-control1 required" style="width:110px;     background-color: #fff; border: 1px solid #e6e6e6; border-radius: 0;  height: 34px; padding: 0 4px; border-radius: 6px;">
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
										<select id="selYear" name="selYear" class="form-control1 required" style="width:90px; margin-left:10px; background-color: #fff;  border: 1px solid #e6e6e6;  border-radius: 0;  height: 34px;  padding: 0 4px; border-radius: 6px;">
											<option value="">Select</option>
											<?php for($i=date('Y')-1; $i<=(date('Y')+10);$i++){ ?>
											<option value="<?php echo $i;?>" <?php if($this->input->post('selYear') == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
											<?php } ?> 														 
										</select> 
										<input type="submit" style="float: none !important; margin: 0 !important;" id="btnFind" name="btnFind" class="btn btn-primary pull-right" value="FIND" />
									</td>
								</tr>	
								<tr><td>&nbsp;</td></tr>                                        
								<tr>
									<td valign="top"> <strong>Mobile(Official):</strong></td>
									<td valign="top">
										<input type="text" id="txtmobile_official" name="txtmobile_official" value="<?php echo $empInfo['mobile_official']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" />
									</td> 
								</tr>
								<tr>
									<td valign="top"> <strong>Mobile(Landline):</strong></td>
									<td valign="top">
									<input type="text" id="txtmobile_landline" name="txtmobile_landline" value="<?php echo $empInfo['mobile_landline']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td> 
								</tr>
								<tr>
									<td valign="top"> <strong>Fuel:</strong></td>
									<td valign="top">
									<input type="text" id="txtfuel"  name="txtfuel" value="<?php echo $empInfo['fuel']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td> 
								</tr>
								<tr>
									<td valign="top"> <strong>Vehicle Maintenance:</strong></td>
									<td valign="top">
									<input type="text" id="txtvehicle_maintenance" name="txtvehicle_maintenance" value="<?php echo $empInfo['vehicle_maintenance']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td> 
								</tr>
								<tr>
									<td valign="top"> <strong>Entertainment:</strong></td>
									<td valign="top">
										<input type="text" id="txtentertainment" name="txtentertainment" value="<?php echo $empInfo['entertainment']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" />
									</td> 
								</tr>
								<tr>
									<td valign="top"> <strong>Books & Periodical:</strong></td>
									<td valign="top">
									<input type="text" id="txtbook_periodical" name="txtbook_periodical" value="<?php echo $empInfo['book_periodical']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td> 
								</tr>
								<tr>
									<td valign="top"> <strong>LTA:</strong></td>
									<td valign="top">
									<input type="text" id="txtlta" name="txtlta" value="<?php echo $empInfo['lta']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td> 
								</tr>
								<tr>
									<td valign="top"> <strong>Mediclaim:</strong></td>
									<td valign="top">
									<input type="text" id="txtmediclaim" name="txtmediclaim" value="<?php echo $empInfo['mediclaim']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td> 
								</tr>
								<tr>
									<td valign="top"> <strong>Lunch:</strong></td>
									<td valign="top">
									<input type="text" id="txtlunch" name="txtlunch" value="<?php echo $empInfo['lunch']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td> 
								</tr>
								<tr>
									<td valign="top"> <strong>Driver's Salary:</strong></td>
									<td valign="top">
									<input type="text" id="txtdriver_salary" name="txtdriver_salary" value="<?php echo $empInfo['driver_salary']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td> 
								</tr>
								<tr><td>&nbsp;</td></tr>	 
							</table>
							<div class="col-md-12">
								<input type="submit" id="btnAddMessage" name="btnAddMessage" class="btn btn-primary pull-right" value="SUBMIT" /> 
							</div>
						</form>   
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
 

