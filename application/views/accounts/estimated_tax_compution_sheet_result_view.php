
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
		<td colspan="4"><h3><center>Estimated Tax Computation Sheet For FY <?php echo (string)$fyear.'-'.(string)($fyear+1); ?></center></h3></td>
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
		<td><?php echo date("d-m-Y", strtotime($empInfo['join_date']));?></td>
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
						<td><?php echo number_format((float)($empInfo['basic'])*12, 2, '.', '')?></td>
						<td>0.00</td>
						<td><?php echo number_format((float)($empInfo['basic'])*12, 2, '.', '')?></td>
					</tr>
					<tr>
						<td>HRA</td>
						<td><?php echo number_format((float)($empInfo['hra'])*12, 2, '.', '')?></td>
						<td><?php 
								$actual_hra_rec = number_format((float)($empInfo['hra'])*12, 2, '.', '');
								$per_basic1 = number_format((float)($empInfo['basic']*12/100)*40, 2, '.', '');
								$per_basic2 = number_format((float)($empInfo['basic']*12/100)*50, 2, '.', '');
								$rent_paid_over_basic = number_format((float)($empInfo['eligible_rent_paid_by_employee'])-($empInfo['basic']*12/100)*10, 2, '.', '');
								$minValue1 = min($actual_hra_rec,min($per_basic1,$rent_paid_over_basic));
								$minValue2 = min($actual_hra_rec,min($per_basic2,$rent_paid_over_basic));
								if($empInfo['eligible_rent_paid_by_employee'] == 0)
								{
									echo number_format((float)(0.00), 2, '.', '');
								}
								elseif($userInfo[0]['state_region2'] == 26)
								{
									echo $minValue2;
								}
								else
								{
									echo $minValue1;
								} 
							?>
						</td>
						<td><?php
								if($empInfo['eligible_rent_paid_by_employee'] == 0)
								{
								echo number_format((float)($empInfo['hra']*12)-0.00, 2, '.', '');
								}
								elseif($userInfo[0]['state_region2'] ==26)
								{
								echo number_format((float)($empInfo['hra']*12)-$minValue2, 2, '.', '');
								}
								else
								{
								echo number_format((float)($empInfo['hra']*12)-$minValue1, 2, '.', '');
								}
							?>
						</td>
					</tr>
					<tr>
						<td>Medical Exp.</td>
						<td><?php echo number_format((float)($empInfo['medical_allowance'])*12, 2, '.', '')?></td>
						<td><?php
								if($empInfo['medical_allowance'] == 0)
								{
									echo number_format((float)(0.00), 2, '.', '');
								}
								else
								{
									echo number_format((float)($empInfo['eligible_medicalexpensesperannum']), 2, '.', '');
								}
							?>
						</td>
						<td><?php
								if($empInfo['medical_allowance'] == 0)
								{
									echo number_format((float)($empInfo['medical_allowance']*12-0.00), 2, '.', '');
								}
								else
								{
									echo number_format((float)($empInfo['medical_allowance']*12-$empInfo['eligible_medicalexpensesperannum']), 2, '.', '');
								} 
							?>
						</td>
					</tr>
					<tr>
						<td>Conv Allow</td>
						<td><?php echo number_format((float)($empInfo['conveyance_allowance'])*12, 2, '.', '')?></td>
						<td><?php echo number_format((float)($empInfo['eligible_conv_allowance']), 2, '.', '')?></td>
						<td><?php if(($empInfo['conveyance_allowance']*12-$empInfo['eligible_conv_allowance'])>0) echo number_format((float)($empInfo['conveyance_allowance']*12-$empInfo['eligible_conv_allowance']), 2, '.', ''); else echo '0.00';?></td>
					</tr>
					<tr>
						<td>Special Allow</td>
						<td><?php echo number_format((float)($empInfo['special_allowance'])*12, 2, '.', '')?></td>
						<td><?php echo number_format((float)($empInfo['eligible_childreneducationalallowance']), 2, '.', '')?></td>
						<td><?php echo number_format((float)($empInfo['special_allowance']*12-$empInfo['eligible_childreneducationalallowance']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>Child Ed Allow</td>
						<td><?php echo number_format((float)($empInfo['child_edu_allow']), 2, '.', '')?></td>
						<td>0.00</td>
						<td><?php echo number_format((float)($empInfo['child_edu_allow']), 2, '.', '')?></td>
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
								}
								}
								else{ echo "0.00";}
							?>
						</td>
						<td>0.00</td>
						<td><?php 
								$count = count($res_arr);
								if(count($res_arr)>0){
								for($i = 0; $i< $count; $i++)
								{
									echo number_format((float)((int)$res_arr[0]['project_allowance_amount']+(int)$res_arr[0]['statutory_bonus_amount']+(int)$res_arr[0]['performance_bonus_amount']+(int)$res_arr[0]['incentive_payment_amount']+(int)$res_arr[0]['leave_incashment_amount']+(int)$res_arr[0]['other_payment_amount']), 2, '.', '');
								}
								}
								else{ echo "0.00";}
							?>
						</td>
					</tr>
					<tr>
						<td>Total</td>
						<td><?php  
								echo number_format((float)($empInfo['basic']*12 + $empInfo['hra']*12 + $empInfo['medical_allowance']*12 + $empInfo['conveyance_allowance']*12 + $empInfo['special_allowance']*12 + $empInfo['child_edu_allow'] + $otherTotal), 2, '.', '');
							?>
						</td>
						<td><?php
								if($empInfo['eligible_rent_paid_by_employee'] == 0)
								{
									echo number_format((float)(0.00 + 0.00 + $empInfo['eligible_medicalexpensesperannum'] + $empInfo['eligible_conv_allowance'] + $empInfo['childreneducationalallowance'] + 0.00 + 0.00), 2, '.', '');
								}
								elseif($userInfo[0]['state_region2'] ==26)
								{  
									echo number_format((float)(0.00 + $minValue2 + $empInfo['eligible_medicalexpensesperannum'] + $empInfo['eligible_conv_allowance'] + $empInfo['childreneducationalallowance'] + 0.00 + 0.00), 2, '.', '');
								}
								elseif($empInfo['medical_allowance'] == 0)
								{
									echo number_format((float)(0.00 + $minValue1 + 0.00 + $empInfo['eligible_conv_allowance'] + $empInfo['childreneducationalallowance'] + 0.00 + 0.00), 2, '.', '');
								}
								else
								{
									echo number_format((float)(0.00 + $minValue1 + $empInfo['eligible_medicalexpensesperannum'] + $empInfo['eligible_conv_allowance'] + $empInfo['childreneducationalallowance'] + 0.00 + 0.00), 2, '.', '');
								} 
								?>
							</td>
						<td><?php 
							$basic = $empInfo['basic']*12;
							$hra = $empInfo['hra']*12-$minValue1;
							$hra2 = $empInfo['hra']*12-$minValue2;
							$hra1 = $empInfo['hra']*12-0.00;
							$medical_allowance = $empInfo['medical_allowance']*12-$empInfo['eligible_medicalexpensesperannum'];
							$conveyance_allowance = $empInfo['conveyance_allowance']*12-$empInfo['eligible_conv_allowance'];
							$special_allowance = $empInfo['special_allowance']*12-$empInfo['childreneducationalallowance'];
							$child_edu_allow = $empInfo['child_edu_allow'];
							$otherincome = 0;
							if(count($res_arr)>0){
								$otherincome = (int)$res_arr[0]['project_allowance_amount']+(int)$res_arr[0]['statutory_bonus_amount']+(int)$res_arr[0]['performance_bonus_amount']+(int)$res_arr[0]['incentive_payment_amount']+(int)$res_arr[0]['leave_incashment_amount']+(int)$res_arr[0]['other_payment_amount'];
							}
							if($empInfo['eligible_rent_paid_by_employee'] == 0)
							{
								$total = number_format((float)($basic+$hra1+0.00+$conveyance_allowance+$special_allowance+$child_edu_allow+$otherincome), 2, '.', '');
								echo $total;	
							}
							else if($empInfo['medical_allowance'] == 0)
							{
								$total = number_format((float)($basic+$hra+0.00+$conveyance_allowance+$special_allowance+$child_edu_allow+$otherincome), 2, '.', '');
								echo $total;
							}
							else if($userInfo[0]['state_region2'] ==26)
							{
								$total = number_format((float)($basic+$hra2+$medical_allowance+$conveyance_allowance+$special_allowance+$child_edu_allow+$otherincome), 2, '.', '');
								echo $total;
							}
							else
							{
								$total = number_format((float)($basic+$hra+$medical_allowance+$conveyance_allowance+$special_allowance+$child_edu_allow+$otherincome), 2, '.', '');
								echo $total;
							}
						?>
						</td>
					</tr>
					<tr>
					
						<td colspan = "4"><h5><strong>HRA Calculation</strong></h5></td>
					</tr>
					<tr>
						<td colspan="2">Rent Paid by Employee</td>
						<td colspan="2"><?php echo number_format((float)($empInfo['eligible_rent_paid_by_employee']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td colspan="2">From</td>
						<td colspan="2">April</td>
					</tr>
					<tr>
						<td colspan="2">To</td>
						<td colspan="2">March</td>
					</tr>
					<tr>
						<td colspan="2">1.Actual HRA Received</td>
						<td colspan="2"><?php echo number_format((float)($empInfo['hra'])*12, 2, '.', '')?></td>
					</tr>
					<tr>
						<td colspan="2">2.40% or 50% of Basic</td>
						<td colspan="2"><?php 
							if($userInfo[0]['state_region2'] ==20)
							{
								echo number_format((float)($empInfo['basic']*12/100)*40, 2, '.', '');
							}
							elseif($userInfo[0]['state_region2'] ==26)
							{
								echo number_format((float)($empInfo['basic']*12/100)*50, 2, '.', '');
							} 
						?></td>
					</tr>
					<tr>
						<td colspan="2">Rent Paid > 10% Basic</td>
						<td colspan="2"><?php
							if($empInfo['eligible_rent_paid_by_employee'] == 0)
							{
								echo number_format((float)(0.00), 2, '.', '');
							}
							else
							{
								echo number_format((float)($empInfo['eligible_rent_paid_by_employee'])-($empInfo['basic']*12/100)*10, 2, '.', '');
							} 
						?></td>
					</tr>
					<tr>
						<td colspan="2">Least of above is exempt</td>
						<td colspan="2"><?php
								$actual_hra_rec = number_format((float)($empInfo['hra'])*12, 2, '.', '');
								$per_basic1 = number_format((float)($empInfo['basic']*12/100)*40, 2, '.', '');
								$per_basic2 = number_format((float)($empInfo['basic']*12/100)*50, 2, '.', '');
								$rent_paid_over_basic = number_format((float)($empInfo['eligible_rent_paid_by_employee'])-($empInfo['basic']*12/100)*10, 2, '.', '');
								$minValue1 = min($actual_hra_rec,min($per_basic1,$rent_paid_over_basic));
								$minValue2 = min($actual_hra_rec,min($per_basic2,$rent_paid_over_basic));
								if($empInfo['eligible_rent_paid_by_employee'] == 0)
								{
									echo number_format((float)(0.00), 2, '.', '');
								}
								elseif($userInfo[0]['state_region2'] ==26)
								{
									echo $minValue2;
								}
								else
								{
									echo $minValue1;
								}
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
						<td><?php echo number_format((float)($empInfo['total_deduction80c']), 2, '.', '');?></td>
						<td><?php echo number_format((float)($empInfo['eligible_total_deduction80c']), 2, '.', '');?></td>
					</tr>
					<tr>
						<td>b. U/S 80CCC</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>c. U/S 80CCD</td>
						<td><?php echo number_format((float)($empInfo['deduction_under_80CCD']), 2, '.', '')?></td>
						<td><?php echo number_format((float)($empInfo['eligible_deduction_under_80CCD']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>B. Oth Sec.(e.g. 80E/G etc.)</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>(1) Section 80CCF</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>(2) Section 80D</td>
						<td><?php echo number_format((float)($empInfo['total_deduction_incase_senior_citizen']), 2, '.', '')?></td>
						<td><?php echo number_format((float)($empInfo['eligible_total_deduction_incase_senior_citizen']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>(3) Section 80DD</td>
						<td><?php echo number_format((float)($empInfo['total_deduction_80dd']), 2, '.', '')?></td>
						<td><?php echo number_format((float)($empInfo['total_eligible_deduction_80dd']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>(4) Section 80DDB</td>
						<td><?php echo number_format((float)($empInfo['total_deduction_80ddb']), 2, '.', '')?></td>
						<td><?php echo number_format((float)($empInfo['total_eligible_deduction_80ddb']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>(5) Section 80E</td>
						<td><?php echo number_format((float)($empInfo['interest_loan_higher_education_80e']), 2, '.', '')?></td>
						<td><?php echo number_format((float)($empInfo['eligible_interest_loan_higher_education_80e']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>(6) Section 80EE</td>
						<td><?php echo number_format((float)($empInfo['interest_home_loan_80ee']), 2, '.', '')?></td>
						<td><?php echo number_format((float)($empInfo['eligible_interest_home_loan_80ee']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>(7) Section 80G</td>
						<td><?php echo number_format((float)($empInfo['actual_donation_80g']), 2, '.', '')?></td>
						<td><?php echo number_format((float)($empInfo['eligible_actual_donation_80g']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>(8) Section 80U</td>
						<td><?php echo number_format((float)($empInfo['total_deduction_under_80U']), 2, '.', '')?></td>
						<td><?php echo number_format((float)($empInfo['eligible_deduction_under_80U']), 2, '.', '')?></td>
					</tr>
					<tr>
						<td>Total</td>
						<td><?php 
							$total_declared = number_format((float)($empInfo['total_deduction80c']+$empInfo['deduction_under_80CCD']+$empInfo['total_deduction_incase_senior_citizen']+$empInfo['total_deduction_80dd']+$empInfo['total_deduction_80ddb']+$empInfo['interest_loan_higher_education_80e']+$empInfo['interest_home_loan_80ee']+$empInfo['actual_donation_80g']+$empInfo['total_deduction_under_80U']), 2, '.', '');
							echo $total_declared;
						?></td>
						<td><?php 
							$total_eligible = number_format((float)($empInfo['eligible_total_deduction80c']+$empInfo['eligible_deduction_under_80CCD']+$empInfo['eligible_total_deduction_incase_senior_citizen']+$empInfo['total_eligible_deduction_80dd']+$empInfo['total_eligible_deduction_80ddb']+ $empInfo['eligible_interest_loan_higher_education_80e']+$empInfo['eligible_interest_home_loan_80ee']+$empInfo['eligible_actual_donation_80g']+ $empInfo['eligible_deduction_under_80U']), 2, '.', '');
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
								if(number_format((float)($empInfo['basic']*12 + $empInfo['hra']*12 + $empInfo['medical_allowance']*12 + $empInfo['conveyance_allowance']*12 + $empInfo['special_allowance']*12 + $empInfo['child_edu_allow'] + $empInfo['otherincome']), 2, '.', '') > 300001)
								{
								echo $professional_tax;
								}
								else
								{
								echo $professional_tax2;
								}
								//echo $professional_tax;
								?></td>
						</tr>
						<tr>
							<td>Under Chapter VI-A</td>
							<td><?php echo $total_eligible;?></td>
						</tr>
						<tr>
							<td>Any other Deduction</td>
							<td><?php 
								$eligible_self_occupied_property = number_format((float)($empInfo['eligible_self_occupied_property']), 2, '.', '');
								echo $eligible_self_occupied_property;
							?></td>
						</tr>
						<tr>
							<td>Any other income</td>
							<td></td>
						</tr>
						<tr>
							<td>Perquisite Value</td>
							<td>0.00</td>
						</tr>
						<tr>
							<td>Taxable Income</td>
							<td><?php
								$taxableincome = number_format((float)($total-$professional_tax-$total_eligible-$eligible_self_occupied_property), 2, '.', '');
								echo $taxableincome;
							?></td>
						</tr>
						<tr>
							<td>Tax on Total Income</td>
							<td><?php
								$slab1 = 250000; 
								$slab2 = 250001;
								$slab3 = 500001;
								$slab4 = 100001;
								$taxableincome = number_format((float)($total-$professional_tax-$total_eligible-$eligible_self_occupied_property), 2, '.', '');
								if($taxableincome <= $slab1)
								{ 
									echo 0.00;
								}
								elseif($taxableincome > $slab2 && $taxableincome < $slab3)
								{
									$tax_slab2 = $taxableincome - $slab2; 
									$tax_pay2 = $tax_slab2/100*5;
									$result = number_format((float)($tax_pay2), 2, '.', '');
									echo $result;
								}
								elseif($taxableincome > $slab3 && $taxableincome > $slab4)
								{
									$tax_slab3 = $taxableincome - $slab3; 
									$tax_pay3 = $tax_slab3/100*20+12500;
									$result = number_format((float)($tax_pay3), 2, '.', '');
									echo $result;
								}
								elseif($taxableincome > $slab4)
								{
									$tax_slab4 = $taxableincome - $slab4; 
									$tax_pay4 = $tax_slab4/100*30+12500+100000;
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
								$slab2 = 250001;
								$slab3 = 500001;
								$slab4 = 100001;
								$taxableincome = number_format((float)($total-$professional_tax-$total_eligible-$eligible_self_occupied_property), 2, '.', '');
								if($taxableincome <= $slab1)
								{ 
								echo 0.00;
								}
								elseif($taxableincome > $slab2 && $taxableincome < $slab3)
								{
								$tax_slab2 = $taxableincome - $slab2; 
								$tax_pay2 = $tax_slab2/100*5;
								$result = number_format((float)($tax_pay2), 2, '.', '');
								echo $result;
								}
								elseif($taxableincome > $slab3 && $taxableincome > $slab4)
								{
								$tax_slab3 = $taxableincome - $slab3; 
								$tax_pay3 = $tax_slab3/100*20+12500;
								$result = number_format((float)($tax_pay3), 2, '.', '');
								echo $result;
								}
								elseif($taxableincome > $slab4)
								{
								$tax_slab4 = $taxableincome - $slab4; 
								$tax_pay4 = $tax_slab4/100*30+12500+100000;
								$result = number_format((float)($tax_pay4), 2, '.', '');
								echo $result;
								} 
								?></td>
						</tr>
						<tr>
							<td>Educational Cess</td>
							<td><?php
								$slab1 = 250000; 
								$slab2 = 250001;
								$slab3 = 500001;
								$slab4 = 100001;
								$taxableincome = number_format((float)($total-$professional_tax-$total_eligible-$eligible_self_occupied_property), 2, '.', '');
								$tax_slab4 = $taxableincome - $slab4; 
								$tax_pay4 = $tax_slab4/100*30+12500+100000;
								$result = number_format((float)($tax_pay4), 2, '.', '');
								$educational = $result/100*3;
								//echo $educational;
								echo number_format((float)($educational), 2, '.', '');
							?></td>
						</tr>
						<tr>
							<td>Total Tax</td>
							<td><?php
								$result = number_format((float)($tax_pay4), 2, '.', '');
								$total_tax = $result+$educational;
								echo number_format((float)($total_tax ), 2, '.', '')
							?></td>
						</tr>
						<tr>
							<td>Total Tax Rounded off</td>
							<td><?php echo round($total_tax )?></td>
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