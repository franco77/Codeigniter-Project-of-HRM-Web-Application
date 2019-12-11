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
					<legend class="pkheader">Salary Slip Download</legend> 
					<div class=" row well" style="line-height: 20px;"> 
					
						<div class="row pkdsearch" style="line-height: 20px;">
							 <legend class="form_title col-md-12">Search / View Salary Slip </legend>
							 
							<form id="frmPhoneAdd" name="frmPhoneAdd" method="POST" action="">
								<div class="col-md-2">
									<label for="example-text-input" class="col-2 col-form-label">Month</label>
									<select id="selMonth" name="selMonth" class="form-control" style="width:110px;">
										<option value="">Select</option>
										<option value="01" <?php if($this->input->post('selMonth') == '01') echo 'selected="selected"';?>>January</option>
										<option value="01" <?php if($this->input->post('selMonth') == '02') echo 'selected="selected"';?>>February</option>
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
									<label for="example-text-input" class="col-2 col-form-label">Year</label>
									<select id="selYear" name="selYear" class="form-control" style="width:90px; margin-left:10px;">
										<option value="">Select</option>
										<?php for($i=2017; $i<=(date('Y'));$i++){ ?>
										<option value="<?php echo $i;?>" <?php if($this->input->post('selYear') == $i) echo 'selected="selected"';?>><?php echo $i;?></option>
										<?php } ?> 												 
									</select>
								</div>
								<div class="col-md-3">
									<label for="example-text-input" class="col-2 col-form-label"></label>
									<input type="submit" name="searchEmployee" value="Find" class="btn btn-primary">
								</div>
							</form>
						</div>
					</div> 
					<div class="row well" id="print_id" style="line-height: 20px;"> 
						<section id="page" style="width: 678px; display: block; margin: 0 auto;">
							<?php if($count>0)
							{ ?>
								<section>
									<div class="logo" style="display: block; float: left; margin: 10px 0;"><img src="<?= base_url('assets/images/logo.gif') ?>" alt="" border="0"> </div>
									<div class="address" style="display: block; float: right; font-size: 12px; margin: 15px 20px 0 0;"><span class="company" style="font-weight: bold; font-size: 16px;">POLOSOFT TECHNOLOGIES Pvt. Ltd.</span><br></div>
								</section> 
								
								<section style="display: block; clear: both; padding: 0 10px 10px;">
									<h2 style="text-align: center; font-size: 15px; line-height: 30px;">Pay Slip for the month of <?php echo $empInfo[0]['salary_month'].'/'.$empInfo[0]['salary_year']; ?></h2>
									<div id="empheader">
										<div style="display: block; float: left; width: 70%;">
											<ul style="list-style: none; margin: 0; padding: 0;">
												<li style="margin: 5px 0 3px 0; width: 100%; display: block; float: left;"><div class="head" style="width: 110px; font-weight: bold; display: block; float: left;">Name</div>  <?php echo $empInfo[0]['full_name']?></li>
												<li style="margin: 5px 0 3px 0; width: 100%; display: block; float: left;"><div class="head" style="width: 110px; font-weight: bold; display: block; float: left;">Designation</div> <?php echo $empInfo[0]['desg_name']?></li>
												<li style="margin: 5px 0 3px 0; width: 100%; display: block; float: left;"><div class="head" style="width: 110px; font-weight: bold; display: block; float: left;">Bank A/C No</div> <?php echo $empInfo[0]['bank_no']?></li>
                                                <?php if($empInfo[0]['pf_no']>0){ ?>
												<li style="margin: 5px 0 3px 0; width: 100%; display: block; float: left;"><div class="head" style="width: 110px; font-weight: bold; display: block; float: left;">PF A/C No</div><?php echo $empInfo[0]['pf_no']?></li>
                                                <?php } ?>
                                                
                                                <?php if($empInfo[0]['mediclaim_no']>0){ ?>
												<li style="margin: 5px 0 3px 0; width: 100%; display: block; float: left;"><div class="head" style="width: 110px; font-weight: bold; display: block; float: left;">ESI A/C No </div><?php echo $empInfo[0]['mediclaim_no']?></li>
                                                <?php } ?>
												<li style="margin: 5px 0 3px 0; width: 100%; display: block; float: left;"><div class="head" style="width: 110px; font-weight: bold; display: block; float: left;">Department </div><?php echo $empInfo[0]['dept_name']?></li>
												<li style="margin: 5px 0 3px 0; width: 100%; display: block; float: left;">&nbsp;</li>
											</ul>
										</div>
										<div  style="display: block; float: left; width: 30%;">
											<ul style="list-style: none; margin: 0; padding: 0;">
												<li style="margin: 2px 0 1px 0; width: 100%; display: block; float: left;"><div class="head" style="width: 110px; font-weight: bold; display: block; float: left;">Employee ID</div> <?php echo $empInfo[0]['loginhandle']?></li>
												<li style="margin: 5px 0 3px 0; width: 100%; display: block; float: left;"><div class="head" style="width: 110px; font-weight: bold; display: block; float: left;">Date of Joining</div><?php echo $empInfo[0]['join_date']?></li>
												<li style="margin: 5px 0 3px 0; width: 100%; display: block; float: left;"><div class="head" style="width: 110px; font-weight: bold; display: block; float: left;">Working Days</div><?php echo $empInfo[0]['working_days']?></li>
												<li style="margin: 5px 0 3px 0; width: 100%; display: block; float: left;"><div class="head" style="width: 110px; font-weight: bold; display: block; float: left;">Days Worked</div><?php echo $empInfo[0]['paid_days']?></li>
												<li style="margin: 5px 0 3px 0; width: 100%; display: block; float: left;"><div class="head" style="width: 110px; font-weight: bold; display: block; float: left;">Days Arrear</div><?php echo $empInfo[0]['arrear_days']?></li>
												<li style="margin: 5px 0 3px 0; width: 100%; display: block; float: left;"><div class="head" style="width: 110px; font-weight: bold; display: block; float: left;">Available</div> PL-<?php echo $avlPL?>&nbsp;&nbsp;SL-<?php echo $avlSL?></li>
											</ul> 
										</div>
									</div> 

								</section> 
								<br>
								<br>
								<br>
								<br>
								<table width="100%" style="border-collapse: collapse;border: 1px solid black;padding: 5px;">
									<tr>
										<td colspan="5" style="border-right:1pt solid black;border-top:1pt solid black;border-left:1pt solid black;border-bottom:1pt solid black; text-align:center;background-color:#CCCCCC !important;-webkit-print-color-adjust: exact;padding:5px;"><b>Earning</b></td>
										<td colspan="2" style="border-right:1pt solid black;border-top:1pt solid black;border-bottom:1pt solid black;text-align:center;background-color:#CCCCCC !important;-webkit-print-color-adjust: exact;padding:5px">Deduction</td>
									</tr>
									<tr style="border-top:1pt solid black;">
										<th style="border-right:1pt solid black;text-align:center;border-left:1pt solid black;padding:5px;"><b>Description</b></th>
										<th style="border-right:1pt solid black;text-align:center;padding:5px;">Rate</th>
										<th style="border-right:1pt solid black;text-align:center;padding:5px;">Monthly</th>
										<th style="border-right:1pt solid black;text-align:center;padding:5px;">Arrear</th>
										<th style="border-right:1pt solid black;text-align:center;padding:5px;">Total</th>
										<th style="border-right:1pt solid black;text-align:center;padding:5px;">Description</th>
										<th style="border-right:1pt solid black;text-align:center;padding:5px;">Amount</th>
									</tr>
									<tr>
										<td style="border-right:1pt solid black;border-left:1pt solid black;padding:5px;;">Basic</td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['basic']), 2, '.', '').'(% Of Gross)'; ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_basic']-$empInfo[0]['arrear_basic']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['arrear_basic']), 2, '.', ''); ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_basic']), 2, '.', ''); ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php if($empInfo[0]['earned_esi']>0){ echo "ESI"; }?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php if($empInfo[0]['earned_esi']>0){ echo number_format((float)($empInfo[0]['earned_esi']), 2, '.', '');  } ?></td>
									</tr>
									<tr>
										<td style="border-right:1pt solid black;border-left:1pt solid black;padding:5px;;">HRA</td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['hra']), 2, '.', '').'(% Of Basic)'; ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_hra']-$empInfo[0]['arrear_hra']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['arrear_hra']), 2, '.', ''); ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_hra']), 2, '.', ''); ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php if($empInfo[0]['earned_pf']>0){ echo "PF"; }?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php if($empInfo[0]['earned_pf']>0){ echo number_format((float)($empInfo[0]['earned_pf']), 2, '.', '');  } ?></td>
									</tr>
									<tr>
										<td style="border-right:1pt solid black;border-left:1pt solid black;padding:5px;">Conv. Allowance</td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['conveyance_allowance']), 2, '.', ''); ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_conveyance_allowance']-$empInfo[0]['arrear_conveyance_allowance']), 2, '.', ''); ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['arrear_conveyance_allowance']), 2, '.', ''); ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_conveyance_allowance']), 2, '.', ''); ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php if($empInfo[0]['pt']>0){ echo "PT";}?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php if($empInfo[0]['pt']>0){ echo number_format((float)($empInfo[0]['pt']), 2, '.', ''); } ?></td>
									</tr>
									<tr>
										<td style="border-right:1pt solid black;border-left:1pt solid black;padding:5px;">Special Allowance</td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['special_allowance']), 2, '.', ''); ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_special_allowance']-$empInfo[0]['arrear_special_allowance']), 2, '.', ''); ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['arrear_special_allowance']), 2, '.', ''); ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_special_allowance']), 2, '.', ''); ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php if($empInfo[0]['income_tax']>0){ echo "TDS"; }?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php if($empInfo[0]['income_tax']>0){ echo number_format((float)($empInfo[0]['income_tax']), 2, '.', '');  } ?></td>
									</tr>
									<!-- SOME MISTAKE -->
									<?php if($empInfo[0]['earned_medical_allowance']>0){ ?>
									<tr>
										<td style="border-right:1pt solid black;border-left:1pt solid black;padding:5px;">Medical Allowance</td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['medical_allowance']), 2, '.', ''); ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_medical_allowance']), 2, '.', ''); ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_medical_allowance']), 2, '.', ''); ?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php if($empInfo[0]['recovery']>0){ echo "RECOVERY"; }?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php if($empInfo[0]['recovery']>0){ echo number_format((float)($empInfo[0]['recovery']), 2, '.', ''); } ?></td>
									</tr>
									<?php } ?>
									<?php if($empInfo[0]['earned_food_allowance']>0){ ?>
									<tr>
										<td style="border-right:1pt solid black;border-left:1pt solid black;padding:5px;">Food Allowance</td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['food_allowance']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_food_allowance']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_food_allowance']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php if($empInfo[0]['advance']>0){ echo "EDVANCE"; }?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php if($empInfo[0]['advance']>0){ echo number_format((float)($empInfo[0]['advance']), 2, '.', ''); } ?></td>
									</tr>
									<?php } ?>
									<?php if($empInfo[0]['referal_bonus']>0){?>
									<tr>
										<td style="border-right:1pt solid black;border-left:1pt solid black;padding:5px;">Buddy Referal Bonus</td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['referal_bonus']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['referal_bonus']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo['referal_bonus']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php if($empInfo[0]['loan']>0){ echo "LOAN";}?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php if($empInfo[0]['loan']>0){ echo number_format((float)($empInfo[0]['loan']), 2, '.', ''); } ?></td>
									</tr>
									<?php } ?>
									<?php if($empInfo[0]['earned_relocation_allowance']>0){?>
									<tr>
										<td style="border-right:1pt solid black;border-left:1pt solid black;padding:5px;">Relocation Allowance</td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['relocation_allowance']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_relocation_allowance']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_relocation_allowance']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right"><?php if($empInfo[0]['donation']>0){ echo "DONATION"; }?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php if($empInfo[0]['donation']>0){ echo number_format((float)($empInfo[0]['donation']), 2, '.', ''); } ?></td>
									</tr>
									<?php } ?>
									<?php if($empInfo[0]['earned_city_allowance']>0){?>
									<tr>
										<td style="border-right:1pt solid black;border-left:1pt solid black;padding:5px;">City Allowance</td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['city_allowance']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo['earned_city_allowance']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"></td>
									</tr>
									<?php } ?>
									<?php if($empInfo[0]['earned_personal_allowance'] > 0){ ?>
									<tr>
										<td style="border-right:1pt solid black;border-left:1pt solid black;padding:5px;">Personal Allowance</td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['personal_allowance']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_personal_allowance']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['earned_personal_allowance']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"></td>
									</tr>
									
									<?php } ?>
									<?php if($empInfo[0]['child_edu_allowance']>0){?>
									<tr>
										<td style="border-right:1pt solid black;border-left:1pt solid black;padding:5px;">ChildEdu. Allowance</td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['childreneducationalallowance']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['child_edu_allowance']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"><?php echo number_format((float)($empInfo[0]['child_edu_allowance']), 2, '.', '');?></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"></td>
										<td style="border-right:1pt solid black;text-align:right;padding:5px;"></td>
									</tr>
									<?php } ?>
									<tr>
										<td style="border-right:1pt solid black;border-left:1pt solid black;padding:5px;">&nbsp;</td>
										<td style="border-right:1pt solid black;text-align:right"></td>
										<td style="border-right:1pt solid black;text-align:right"></td>
										<td style="border-right:1pt solid black;text-align:right"></td>
										<td style="border-right:1pt solid black;text-align:right"></td>
										<td style="border-right:1pt solid black;text-align:right"></td>
										<td style="border-right:1pt solid black;text-align:right"></td>
									</tr>
									<tr>
										<td style="border-right:1pt solid black;text-align:right;border-left:1pt solid black;">&nbsp;</td>
										<td style="border-right:1pt solid black;text-align:right"></td>
										<td style="border-right:1pt solid black;text-align:right"></td>
										<td style="border-right:1pt solid black;text-align:right"></td>
										<td style="border-right:1pt solid black;text-align:right"></td>
										<td style="border-right:1pt solid black;text-align:right"></td>
										<td style="border-right:1pt solid black;text-align:right"></td>
									</tr>
									<tr>
										<td style="border-top:1pt solid black;border-left:1pt solid black;border-bottom:1pt solid black;background-color:#CCCCCC !important;-webkit-print-color-adjust: exact;padding:5px;">GROSS PAY</td>
										<td style="border-top:1pt solid black;border-bottom:1pt solid black;background-color:#CCCCCC !important;-webkit-print-color-adjust: exact;padding:5px;text-align:right"><?php echo number_format((float)($empInfo[0]['gross']), 2, '.', '');?></td>
										<td style="border-top:1pt solid black;border-bottom:1pt solid black;background-color:#CCCCCC !important;-webkit-print-color-adjust: exact;padding:5px;"></td>
										<td style="border-top:1pt solid black;background-color:#CCCCCC !important;-webkit-print-color-adjust: exact;padding:5px;"></td>
										<td style="border-top:1pt solid black;border-bottom:1pt solid black;background-color:#CCCCCC !important;-webkit-print-color-adjust: exact;padding:5px;text-align:right"><?php echo number_format((float)($empInfo[0]['earned_gross']), 2, '.', '');?></td>
										<td style="border-top:1pt solid black;border-bottom:1pt solid black;background-color:#CCCCCC !important;-webkit-print-color-adjust: exact;padding:5px;">Deduction</td>
										<td style="border-top:1pt solid black;border-right:1pt solid black;border-bottom:1pt solid black;background-color:#CCCCCC !important;-webkit-print-color-adjust: exact;padding:5px;"><?php echo number_format((float)($empInfo[0]['total_deduction']), 2, '.', '');?></td>
									</tr>
									<tr>
										<td colspan="7" style="border-bottom:1pt solid black;border-left:1pt solid black;border-right:1pt solid black;background-color:#CCCCCC !important;-webkit-print-color-adjust: exact;padding:5px;"><i>Net Salary Rs <?=  $empInfo[0]['net_salary']; ?>(Rs.<span style="text-transform: capitalize;"><?= $net_salary_words; ?></span>)</i></td>
									</tr>
								</table>
								<br>
									<div style="font-size: 10px; color: #000;"><i><center>This is a computer generated statement, hence doesn't require signature.</center></i></div>
									<p style="text-align: center; font-size: 10px; color: #000; cursor: pointer;"><img id="btnprint" alt="Print" onclick="printDiv('print_id')" src="<?php echo base_url(); ?>assets/images/printer_icon.png"></p>

							<?php } else{ ?>
								  <p style="text-align: center; font-size: 10px; color: #000;">No Record Found for this Employee.</p>
							 <?php } ?>
						</section>
					</div> 
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>
<script>
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;
	 
	 var ButtonControl = document.getElementById("btnprint");
     ButtonControl.style.visibility = "hidden";

     window.print();

     document.body.innerHTML = originalContents;
}
</script>