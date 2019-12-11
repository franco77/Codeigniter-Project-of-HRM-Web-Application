<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/frontend/main.css">
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
					<legend class="pkheader">All Employee Details</legend> 
					<div class="row well"> 
						<div class="table-responsive form1">
						<form id="frmSalaryUpdate" name="frmSalaryUpdate" method="POST" action="" enctype="multipart/form-data">
							<table class="table table-striped table-bordered table-condensed">
								<tr>
									<td><strong>Allowance/Deduction For the Month</strong></td>
									<td>
										<select id="selMonth" name="selMonth" class="required form-control" >
											<option value="">Select Month</option>
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
									</td>
									<td>
										<select id="selYear" name="selYear" class="required form-control" >
											<option value="">Select Year</option>
											<?php for($i=date('Y')-1; $i<=(date('Y')+10);$i++){ ?>
											<option value="<?php echo $i;?>" <?php if($this->input->post('selYear') == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
											<?php } ?>														 
										</select>
									</td>
									<td>
										<input type="submit" style="float: none !important; margin: 0 !important;" id="btnFind" name="btnFind" class="btn btn-info" value="FIND" />
									</td>
								</tr>	
								<tr><td colspan="4"> &nbsp;</td></tr>                                        
								<tr>
								<td valign="top"> <strong>Performance </strong>(in %):</td>
								<td valign="top">
								<input type="text" id="txtperformance_bonus" name="txtperformance_bonus" value="<?php echo $empInfo['performance_incentive']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td>
								<td valign="top"><strong>Income Tax</strong> :</td>
								<td valign="top">
								<input type="text" id="txtincometax" name="txtincometax" value="<?php echo $empInfo['income_tax']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td>

								</tr>  
								<tr><td colspan="4"class="form_title"><strong>Multiple Allowances</strong></td></tr>
								<tr>
								<td valign="top"> <strong>Buddy Referal Bonus:</strong></td>
								<td valign="top">
								<input type="text" id="txtreferal_bonus" name="txtreferal_bonus" value="<?php echo $empInfo['referal_bonus']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td>
								<td valign="top"> <strong>Leave Encashment:</strong></td>
								<td valign="top">
								<input type="text" id="txtleave_encashment" name="txtleave_encashment" value="<?php echo $empInfo['leave_encashment']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td>
								</tr>
								<tr>
								<td valign="top"> <strong>Arrear </strong>(in Amount):</td>
								<td valign="top">
								<input type="text" id="txtarrear" name="txtarrear" value="<?php echo $empInfo['arrear']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td>

								<td valign="top"> <strong>City Allowance:</strong></td>
								<td valign="top">
								<input type="text" id="txtcity_allowance" name="txtcity_allowance" value="<?php echo $empInfo['city_allowance']?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td>

								</tr>
								<tr>  
								<td valign="top"> <strong>Food Allowance:</strong></td>
								<td valign="top">
								<input type="text" id="txtfood_allowance" name="txtfood_allowance" value="<?php echo $empInfo['food_allowance'];?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td>
								<td valign="top"> <strong>Personal Allowance:</strong></td>
								<td valign="top">
								<input type="text" id="txtpersonal_allowance" name="txtpersonal_allowance" value="<?php echo $empInfo['personal_allowance'];?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td>

								</tr>                                

								<tr>
								<td valign="top"> <strong>Relocation Allowance:</strong></td>
								<td valign="top">
								<input type="text" id="txtrelocation_allowance" name="txtrelocation_allowance" value="<?php echo $empInfo['relocation_allowance'];?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td>
								<td valign="top"> <strong>Other Income (Incentive):</strong></td>
								<td valign="top">
								<input type="text" id="txtincentive" name="txtincentive" value="<?php echo $empInfo['incentive'];?>" class="number form-control" style="width:150px; margin-bottom: 10px;" /></td>

								</tr>


								<tr><td colspan="4"class="form_title"><strong>Multiple Deductions</strong></td></tr>                                       
								<tr>
								<td valign="top"> <strong>Donation:</strong></td>
								<td valign="top"><input type="text" id="txtdonation" name="txtdonation" value="<?php echo $empInfo['donation']?>" class="form-control" style="width:150px; margin-bottom: 10px;" /></td>
								<td valign="top"> <strong>Recovery:</strong></td>
								<td valign="top">
								<input type="text" id="txtrecovery" name="txtrecovery" value="<?php echo $empInfo['recovery']?>" class="form-control" style="width:150px; margin-bottom: 10px;" /></td>

								</tr>                               

								<tr>
								<td valign="top"> <strong>Other Deduction:</strong></td>
								<td valign="top">
								<input type="text" id="txtother_deduction" name="txtother_deduction" value="<?php echo $empInfo['other_deduction']?>" class="form-control" style="width:150px; margin-bottom: 10px;" /></td>
								</tr>   
								<tr><td>&nbsp;</td></tr>	
							</table>
							<input type="submit" id="btnAddMessage" name="btnAddMessage" class="btn btn-info pull-right" value="SUBMIT" />
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