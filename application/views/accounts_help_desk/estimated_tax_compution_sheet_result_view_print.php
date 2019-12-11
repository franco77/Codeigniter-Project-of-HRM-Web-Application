
<style>
table {
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid #ccc;
	padding: 1px;
    text-align: left;

}
body{
  margin-left: 0px;
  margin-right: 0px;
  height :auto;
  width:210mm;
  font-family:Arial, Helvetica, sans-serif;
   font-size:11.4px;
  
}
@media print {
html,body{height:100%;width:100%;margin:0;padding:0;}
 @page {
  size: landscape;
	max-height:100%;
	max-width:100%
  font-size:.25em
  overflow: hidden;
	}
}
  #printbtn{
    visibility: : none !important;
  }
 .table-noborder td {
	  border:none;!important
  }
  .table-noborder {
	  border:none;!important
  }
  .logo-tax img {
	  height:54px;
	  width:117px;
  }
</style>

<!DOCTYPE html>
<html lang="en">
<body>
<div class="row">
	<div class="container">
			<section id="header">
	<div class="logo-tax"><img src="<?php echo base_url('assets/images/logo.gif')?>" alt="" border="0"> </div>
	<div class="address">
			<span style="float:right"><b>POLOSOFT TECHNOLOGIES Pvt. Ltd.</b></span><br />
	</div>
</section> 
<table width="100%" class="table-noborder">
	<tr>
		<td colspan="4"><h3><center><?php if($empInfo['type'] == 'E'){ echo "Estimated"; } else{ echo "Final"; } ?> Tax Computation Sheet For FY <?php echo (string)$empInfo['fyear'].'-'.(string)($empInfo['fyear']+1); $fyear = $empInfo['fyear']; ?></center></h3></td>
	</tr>
	<tr>
		<td><strong>Name:</strong></td>
		<td><?php echo $empInfo['full_name']?></td>
		<td><strong>Pan Card No:</strong></td>
		<td><?php echo $empInfo['pan_card_no']?></td>
	</tr>
	<tr>
		<td><strong>Designation:</strong></td> 
		<td><?php echo $empInfo['desg_name']?></td>
		<td><strong>Employee ID:</strong></td>
		<td><?php echo $empInfo['loginhandle']?></td>
	</tr>
	<tr>
		<td><strong>Department:</strong></td>
		<td><?php echo $empInfo['dept_name']?></td>
		<td><strong>Date of Joining : </strong></td>
		<td><?php 
			echo $doj = date("d-m-Y", strtotime($empInfo['join_date']));
			//echo $doj = date("d-m-Y", strtotime('15-07-2018'));
			$doj_m = date("m", strtotime($doj));
			$doj_str = strtotime($doj);
			$fyear_start =  date("d-m-Y", strtotime('01-04-'.$fyear));
			$fyear_start_str = strtotime($fyear_start);
			if($fyear_start_str >= $doj_str){
				$months = 12;
			}
			else{
				$months = (13 - $doj_m) + 3;
			}
			
		?></td>
	</tr>
</table>
  <br>
  <table width="100%" id="computation">
	<tr>
		<td style="border-right: none">
			<table width="95%">
					<tr>
						<td>Description</td>
						<td>Gross</td>
						<td>Exempt</td>
						<td>Taxable</td>
					</tr>
					<tr>
						<td>Basic</td>
						<td><?php echo $basic_est = number_format((float)($empInfo['basic'])*$months, 2, '.', ''); ?></td>
						<td><?php echo $basic_ded = number_format((float)(0.00), 2, '.', ''); ?></td>
						<td><?php echo $basic_tax = number_format((float)($empInfo['basic'])*$months, 2, '.', ''); ?></td>
					</tr>
					<tr>
						<td>HRA</td>
						<td><?php echo $hra_est = number_format((float)($empInfo['hra'])*$months, 2, '.', ''); ?></td>
						<td><?php 
								$actual_hra_rec = number_format((float)($empInfo['hra'])*$months, 2, '.', '');
								$per_basic1 = number_format((float)($empInfo['basic']*$months/100)*40, 2, '.', '');
								$per_basic2 = number_format((float)($empInfo['basic']*$months/100)*50, 2, '.', '');
								$rent_paid_over_basic = number_format((float)($empInfo['eligible_rent_paid_by_employee'])-($empInfo['basic']*$months/100)*10, 2, '.', '');
								$minValue1 = min($actual_hra_rec,min($per_basic1,$rent_paid_over_basic));
								$minValue2 = min($actual_hra_rec,min($per_basic2,$rent_paid_over_basic));
								if($empInfo['eligible_rent_paid_by_employee'] == 0)
								{
									echo $hra_ded = number_format((float)(0.00), 2, '.', '');
								}
								elseif($userInfo[0]['state_region2'] == 26)
								{
									echo $hra_ded = $minValue2;
								}
								else
								{
									echo $hra_ded = $minValue1;
								} 
							?>
						</td>
						<td><?php
								if($empInfo['eligible_rent_paid_by_employee'] == 0)
								{
								echo $hra_tax = number_format((float)($empInfo['hra']*$months)-0.00, 2, '.', '');
								}
								elseif($userInfo[0]['state_region2'] ==26)
								{
								echo $hra_tax = number_format((float)($empInfo['hra']*$months)-$minValue2, 2, '.', '');
								}
								else
								{
								echo $hra_tax = number_format((float)($empInfo['hra']*$months)-$minValue1, 2, '.', '');
								}
							?>
						</td>
					</tr>
					<tr>
						<td>Medical Exp.</td>
						<td><?php echo $medical_est = number_format((float)($empInfo['medical_allowance'])*$months, 2, '.', '')?></td>
						<td><?php
								if($empInfo['medical_allowance'] == 0)
								{
									echo $medical_ded = number_format((float)(0.00), 2, '.', '');
								}
								else
								{
									echo $medical_ded = number_format((float)($empInfo['eligible_medicalexpensesperannum']), 2, '.', '');
								}
							?>
						</td>
						<td><?php
								if($empInfo['medical_allowance'] == 0)
								{
									echo $medical_tax = number_format((float)($empInfo['medical_allowance']*$months-0.00), 2, '.', '');
								}
								else
								{
									echo $medical_tax = number_format((float)($empInfo['medical_allowance']*$months-$empInfo['eligible_medicalexpensesperannum']), 2, '.', '');
								} 
							?>
						</td>
					</tr>
					<tr>
						<td>Conv Allow</td>
						<td><?php echo $conv_est = number_format((float)($empInfo['conveyance_allowance'])*$months, 2, '.', ''); ?></td>
						<td><?php echo $conv_ded = number_format((float)($empInfo['eligible_conv_allowance']), 2, '.', ''); ?></td>
						<td><?php if(($empInfo['conveyance_allowance']*$months-$empInfo['eligible_conv_allowance'])>0){ 
							echo $conv_tax = number_format((float)($empInfo['conveyance_allowance']*$months-$empInfo['eligible_conv_allowance']), 2, '.', ''); 
						}else{ echo $conv_tax = number_format((float)(0.00), 2, '.', ''); } 
						?></td>
					</tr>
					<tr>
						<td>Special Allow</td>
						<td><?php echo $special_est = number_format((float)($empInfo['special_allowance'])*$months, 2, '.', '')?></td>
						<td><?php echo $special_ded = number_format((float)($empInfo['eligible_childreneducationalallowance']), 2, '.', ''); ?></td>
						<td><?php echo $special_tax = number_format((float)($special_est-$special_ded), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>Child Ed Allow</td>
						<td><?php echo $child_est = number_format((float)(0.00), 2, '.', ''); ?></td>
						<td><?php echo $child_ded = number_format((float)(0.00), 2, '.', ''); ?></td>
						<td><?php echo $child_tax = number_format((float)(0.00), 2, '.', ''); ?></td>
					</tr>
					<tr>
						<td>Other Income</td>
						<td><?php
								$count = count($res_arr);
								$otherTotal = 0;
								if(count($res_arr)>0){
								for($i = 0; $i< $count; $i++)
								{
									echo $otherTotal = number_format((float)((int)$res_arr[0]['project_allowance_amount']+(int)$res_arr[0]['statutory_bonus_amount']+(int)$res_arr[0]['performance_bonus_amount']+(int)$res_arr[0]['incentive_payment_amount']+(int)$res_arr[0]['leave_incashment_amount']+(int)$res_arr[0]['other_payment_amount']), 2, '.', '');
									$other_est = $otherTotal;
								}
								}
								else{ echo $other_est = number_format((float)(0.00), 2, '.', ''); }
							?>
						</td>
						<td><?php echo $other_ded = number_format((float)(0.00), 2, '.', ''); ?></td>
						<td><?php 
								$count = count($res_arr);
								if(count($res_arr)>0){
								for($i = 0; $i< $count; $i++)
								{
									echo $other_tax = number_format((float)((int)$res_arr[0]['project_allowance_amount']+(int)$res_arr[0]['statutory_bonus_amount']+(int)$res_arr[0]['performance_bonus_amount']+(int)$res_arr[0]['incentive_payment_amount']+(int)$res_arr[0]['leave_incashment_amount']+(int)$res_arr[0]['other_payment_amount']), 2, '.', '');
								}
								}
								else{ echo $other_tax = "0.00";}
							?>
						</td>
					</tr>
					<tr>
						<td>Total</td>
						<td><?php  
								echo $total_est = number_format((float)( $basic_est + $hra_est + $medical_est + $conv_est + $special_est + $child_est + $other_est ), 2, '.', '');
							?>
						</td>
						<td><?php
								echo $total_ded = number_format((float)( $basic_ded + $hra_ded + $medical_ded + $conv_ded + $special_ded + $child_ded + $other_ded ), 2, '.', '');
								?>
							</td>
						<td><?php 
							echo $total = number_format((float)( $basic_tax + $hra_tax + $medical_tax + $conv_tax + $special_tax + $child_tax + $other_tax ), 2, '.', '');
						?>
						</td>
					</tr>
					<tr>
					
						<td colspan = "4"><h5><strong>HRA Calculation</strong></h5></td>
					</tr>
					<tr>
						<td colspan="2">Rent Paid by Employee</td>
						<td colspan="2"><?php echo $rent_paid = number_format((float)($empInfo['eligible_rent_paid_by_employee']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td colspan="2">From</td>
						<td colspan="2">April <?php echo (string)$fyear; ?></td>
					</tr>
					<tr>
						<td colspan="2">To</td>
						<td colspan="2">March <?php echo (string)($fyear+1); ?></td>
					</tr>
					<tr>
						<td colspan="2">1.Actual HRA Received</td>
						<td colspan="2"><?php echo $hra_received = number_format((float)($empInfo['hra'])*$months, 2, '.', '')?></td>
					</tr>
					<tr>
						<td colspan="2">2.40% or 50% of Basic</td>
						<td colspan="2"><?php 
							if($userInfo[0]['state_region2'] ==26)
							{
								echo $rent_basic = number_format((float)($empInfo['basic']*$months/100)*50, 2, '.', '');
							} 
							else
							{
								echo $rent_basic = number_format((float)($empInfo['basic']*$months/100)*40, 2, '.', '');
							}
							
						?></td>
					</tr>
					<tr>
						<td colspan="2">3.Rent Paid > 10% Basic</td>
						<td colspan="2"><?php
							if($empInfo['eligible_rent_paid_by_employee'] == 0)
							{
								echo $rent_paid_10_basic = number_format((float)(0.00), 2, '.', '');
							}
							else
							{
								echo $rent_paid_10_basic = number_format((float)($empInfo['eligible_rent_paid_by_employee'])-($empInfo['basic']*$months/100)*10, 2, '.', '');
							} 
						?></td>
					</tr>
					<tr>
						<td colspan="2">Least of above is exempt</td>
						<td colspan="2"><?php
								echo $hra_ded;
							?></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
						<td colspan="2"></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
						<td colspan="2"></td>
					</tr>
				  </table>
		</td>
		<td style="border-left: none;border-right: none">
			<table width="95%">
					<tr>
						<td colspan="3"><h5><strong>Deduction Under Chapter VI-A</strong</h5></td>
					</tr>
					<tr>
						<td>Description</td>
						<td>Declared</td>
						<td>Eligible</td>
					</tr>
					<tr>
						<td>A. Sec 80C, 80CCC, 80CCD</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>a. U/S 80 C</td>
						<td><?php echo $us_80c_dec = number_format((float)($empInfo['total_deduction80c']), 2, '.', '');?></td>
						<td><?php echo  $us_80c_eli = number_format((float)($empInfo['eligible_total_deduction80c']), 2, '.', '');?></td>
					</tr>
					<tr>
						<td>b. U/S 80CCC</td>
						<td><?php echo $us_80ccc_dec = number_format((float)(0.00), 2, '.', '');?></td>
						<td><?php echo $us_80ccc_eli = number_format((float)(0.00), 2, '.', '');?></td>
					</tr>
					<tr>
						<td>c. U/S 80CCD</td>
						<td><?php echo $us_80ccd_dec = number_format((float)($empInfo['deduction_under_80CCD']), 2, '.', '')?></td>
						<td><?php echo $us_80ccd_eli = number_format((float)($empInfo['eligible_deduction_under_80CCD']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>B. Oth Sec.(e.g. 80E/G etc.)</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>(1) Section 80CCF</td>
						<td><?php echo $us_80ccf_dec = number_format((float)(0.00), 2, '.', '');?></td>
						<td><?php echo $us_80ccf_eli = number_format((float)(0.00), 2, '.', '');?></td>
					</tr>
					<tr>
						<td>(2) Section 80D</td>
						<td><?php echo $us_80d_dec = number_format((float)($empInfo['total_deduction_incase_senior_citizen']), 2, '.', '')?></td>
						<td><?php echo $us_80d_eli = number_format((float)($empInfo['eligible_total_deduction_incase_senior_citizen']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>(3) Section 80DD</td>
						<td><?php echo $us_80dd_dec = number_format((float)($empInfo['total_deduction_80dd']), 2, '.', '')?></td>
						<td><?php echo $us_80dd_eli = number_format((float)($empInfo['total_eligible_deduction_80dd']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>(4) Section 80DDB</td>
						<td><?php echo $us_80ddb_dec = number_format((float)($empInfo['total_deduction_80ddb']), 2, '.', '')?></td>
						<td><?php echo $us_80ddb_eli = number_format((float)($empInfo['total_eligible_deduction_80ddb']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>(5) Section 80E</td>
						<td><?php echo $us_80e_dec = number_format((float)($empInfo['interest_loan_higher_education_80e']), 2, '.', '')?></td>
						<td><?php echo $us_80e_eli = number_format((float)($empInfo['eligible_interest_loan_higher_education_80e']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>(6) Section 80EE</td>
						<td><?php echo $us_80ee_dec = number_format((float)($empInfo['interest_home_loan_80ee']), 2, '.', '')?></td>
						<td><?php echo $us_80ee_eli = number_format((float)($empInfo['eligible_interest_home_loan_80ee']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>(7) Section 80G</td>
						<td><?php echo $us_80g_dec = number_format((float)($empInfo['actual_donation_80g']), 2, '.', '')?></td>
						<td><?php echo $us_80g_eli = number_format((float)($empInfo['eligible_actual_donation_80g']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>(8) Section 80U</td>
						<td><?php echo $us_80u_dec = number_format((float)($empInfo['total_deduction_under_80U']), 2, '.', '')?></td>
						<td><?php echo  $us_80u_eli = number_format((float)($empInfo['eligible_deduction_under_80U']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>Total</td>
						<td><?php 
							$total_declared = number_format((float)( $us_80c_dec + $us_80ccc_dec + $us_80ccd_dec + $us_80ccf_dec + $us_80d_dec + $us_80dd_dec + $us_80ddb_dec + $us_80e_dec + $us_80ee_dec + $us_80g_dec + $us_80u_dec ), 2, '.', '');
							echo $total_declared;
						?></td>
						<td><?php 
							$total_eligible =  number_format((float)( $us_80c_eli + $us_80ccc_eli + $us_80ccd_eli + $us_80ccf_eli + $us_80d_eli + $us_80dd_eli + $us_80ddb_eli + $us_80e_eli + $us_80ee_eli + $us_80g_eli + $us_80u_eli ), 2, '.', '');
							echo $total_eligible;
						?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				  </table>
			
		</td>
		<td style='border-left: none'>
			<table width="95%">
					  <tr>
					 
							<td colspan="2">  <h5><strong>Tax Calculation</strong></h5></td>
					  </tr>
						<tr>
							<td>Professional Tax (PT)</td>
							<td><?php
								$professional_tax = 2500;
								$professional_tax2 = 1500;
								$total_income = number_format((float)($empInfo['basic']*$months + $empInfo['hra']*$months + $empInfo['medical_allowance']*$months + $empInfo['conveyance_allowance']*$months + $empInfo['special_allowance']*$months + $empInfo['eligible_childreneducationalallowance'] + $empInfo['otherincome']), 2, '.', '');
								if($total_income > 300000)
								{
									$prof_at = $professional_tax - ((12 - $months)*200);
									echo $pf_tax =number_format((float)($prof_at), 2, '.', '');
								}
								else
								{
									$prof_at = $professional_tax2 - ((12 - $months)*125);
									echo $pf_tax =number_format((float)($prof_at), 2, '.', '');
								}
								//echo $professional_tax;
								?></td>
						</tr>
						<tr>
							<td>Under Chapter VI-A</td>
							<td><?php echo $total_eligible;?></td>
						</tr>
						<tr>
							<td>Standard Deduction</td>
							<td><?php 
								if($fyear >= 2018){
									$standard_deduction = 40000;
								}
								else{
									$standard_deduction = 0;
								}
								echo number_format((float)($standard_deduction), 2, '.', '')
							?></td>
						</tr>
						<tr>
							<td>Any other income (-)</td>
							<td><?php 
								$eligible_self_occupied_property = number_format((float)($empInfo['eligible_self_occupied_property']), 2, '.', '');
								echo $eligible_self_occupied_property;
							?></td>
						</tr>
						<tr>
							<td>Perquisite Value</td>
							<td>0.00</td>
						</tr>
						<tr>
							<td>Taxable Income</td>
							<td><?php
								$taxableincome = number_format((float)($total-$pf_tax-$total_eligible-$standard_deduction-$eligible_self_occupied_property), 2, '.', '');
								echo $taxableincome;
							?></td>
						</tr>
						<tr>
							<td>Tax on Total Income</td>
							<td><?php
								$slab1 = 250000; 
								$slab2 = 500000;
								$slab3 = 1000000;
								
								if($taxableincome <= $slab1)
								{ 
									echo number_format((float)(0.00), 2, '.', '');
								}
								elseif($taxableincome > $slab1 && $taxableincome <= $slab2)
								{
									$tax_slab2 = $taxableincome - $slab1; 
									$tax_pay2 = ($tax_slab2/100)*5;
									$result = number_format((float)($tax_pay2), 2, '.', '');
									echo $result;
								}
								elseif($taxableincome > $slab2 && $taxableincome <= $slab3)
								{
									$tax_slab3 = $taxableincome - $slab2; 
									$tax_pay3 = (($tax_slab3/100)*20)+12500;
									$result = number_format((float)($tax_pay3), 2, '.', '');
									echo $result;
								}
								elseif($taxableincome > $slab3)
								{
									$tax_slab4 = $taxableincome - $slab3; 
									$tax_pay4 = (($tax_slab4/100)*30)+12500+100000;
									$result = number_format((float)($tax_pay4), 2, '.', '');
									echo $result;
								} 
							?></td>
						</tr>
						<tr>
							<td>Tax Rebate</td>
							<td>0.00</td>
						</tr>
						<tr>
							<td>Tax Due</td>
							<td><?php
								$slab1 = 250000; 
								$slab2 = 500000;
								$slab3 = 1000000;
								
								if($taxableincome <= $slab1)
								{ 
									echo number_format((float)(0.00), 2, '.', '');
								}
								elseif($taxableincome > $slab1 && $taxableincome <= $slab2)
								{
									$tax_slab2 = $taxableincome - $slab1; 
									$tax_pay2 = ($tax_slab2/100)*5;
									$result = number_format((float)($tax_pay2), 2, '.', '');
									echo $result;
								}
								elseif($taxableincome > $slab2 && $taxableincome <= $slab3)
								{
									$tax_slab3 = $taxableincome - $slab2; 
									$tax_pay3 = (($tax_slab3/100)*20)+12500;
									$result = number_format((float)($tax_pay3), 2, '.', '');
									echo $result;
								}
								elseif($taxableincome > $slab3)
								{
									$tax_slab4 = $taxableincome - $slab3; 
									$tax_pay4 = (($tax_slab4/100)*30)+12500+100000;
									$result = number_format((float)($tax_pay4), 2, '.', '');
									echo $result;
								} 
							?></td>
						</tr>
						<tr>
							<td>Educational Cess</td>
							<td><?php
								$slab1 = 250000; 
								$slab2 = 500000;
								$slab3 = 1000000;
								//$taxableincome = number_format((float)($total-$professional_tax-$total_eligible-$standard_deduction-$eligible_self_occupied_property), 2, '.', '');
								if($taxableincome <= $slab1)
								{ 
									$taxpay = number_format((float)(0.00), 2, '.', '');
									$educational = ($taxpay/100)*3;
									$result = number_format((float)($educational), 2, '.', '');
									echo $result;
								}
								elseif($taxableincome > $slab1 && $taxableincome <= $slab2)
								{
									$tax_slab2 = $taxableincome - $slab1; 
									$tax_pay2 = ($tax_slab2/100)*5;
									$taxpay = number_format((float)($tax_pay2), 2, '.', '');
									$educational = ($taxpay/100)*3;
									$result = number_format((float)($educational), 2, '.', '');
									echo $result;
								}
								elseif($taxableincome > $slab2 && $taxableincome <= $slab3)
								{
									$tax_slab3 = $taxableincome - $slab2; 
									$tax_pay3 = (($tax_slab3/100)*20)+12500;
									$taxpay = number_format((float)($tax_pay3), 2, '.', '');
									$educational = ($taxpay/100)*3;
									$result = number_format((float)($educational), 2, '.', '');
									echo $result;
								}
								elseif($taxableincome > $slab3)
								{
									$tax_slab4 = $taxableincome - $slab3; 
									$tax_pay4 = (($tax_slab4/100)*30)+12500+100000;
									$taxpay = number_format((float)($tax_pay4), 2, '.', '');
									$educational = ($taxpay/100)*3;
									$result = number_format((float)($educational), 2, '.', '');
									echo $result;
								} 
							?>
							</td>
						</tr>
						<tr>
							<td>Total Tax</td>
							<td><?php
								$total_tax = $taxpay+$educational;
								echo number_format((float)($total_tax ), 2, '.', '');
							?></td>
						</tr>
						<tr>
							<td>Total Tax Rounded off</td>
							<td><?php echo number_format((float)(round($total_tax ) ), 2, '.', '')?></td>
						</tr>
						<tr>
							<td>Tax Deducted till date</td>
							<td>0.00</td>
						</tr>
						<tr>
							<td>Tax to be deducted</td>
							<td>0.00</td>
						</tr>
						<tr>
							<td>Tax/Month</td>
							<td>0.00</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
			 </table>
		</td>
	</tr>
	
  </table>
	</div>
</div>


</body>

</html>


<p style="text-align: center; font-size: 10px; color: #000; cursor: pointer; margin-top:20px;"><img alt="Print" id="imgprint" src="<?php echo base_url('assets/images/printer_icon.png')?>" onclick="javascript:window.print();" /></p>