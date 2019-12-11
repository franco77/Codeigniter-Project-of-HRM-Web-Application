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
				<div class="col-md-9 center-xs">
					<legend class="pkheader">Income Tax Declaration(Estimation) <?php 
							
								echo $empDetails[0]['full_name'];
						?></legend>
					<div class="row well">
						
						</h4> 
						<form id="frmSalaryUpdate" name="frmSalaryUpdate" method="POST" action="" enctype="multipart/form-data" class="form-horizontal">
							<div class="table-responsive">
								<table class="table table-striped table-bordered">
									<thead>
										<tr class="info">
											<th>For the Financial Year <?php $fyear = $empInfo[0]['fyear']; echo (string)$empInfo[0]['fyear'].'-'.(string)($empInfo[0]['fyear']+1); ?></th>
											<th>Declared</th>
											<th>Eligible</th>
										</tr>
									</thead>
									
									<tbody>
									 
										<tr class="info">
											<td colspan="3" class="form_title"><strong>Exemption U/s 10</strong>
												<input type="hidden" id="tid" name="tid" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['tid'];?>"  />
												<input type="hidden" id="fyear" name="fyear" value="<?php echo $fyear;?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>a) Rent Paid by Employee(Per Annum)</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration1" name="rent_paid_by_employee" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['rent_paid_by_employee'];?>"  class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration2" name="eligible_rent_paid_by_employee" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['eligible_rent_paid_by_employee'];?>" READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration2_limit" name="income_tax_declaration2_limit" value="<?php echo $estimatedValue[0]['rent_oaid_by_employee'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>b) Conveyance Allowance (1600 Per Month)</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration3" name="conv_allowance" value="<?php if($estimatedValue[0]['conv_allowance'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['conv_allowance']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['conv_allowance'] =='0.00'){ echo 'disabled ';} ?> class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration4" name="eligible_conv_allowance" value="<?php if($estimatedValue[0]['conv_allowance'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['eligible_conv_allowance']; } } else { echo 'NA';} ?>" READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration4_limit" name="income_tax_declaration4_limit" value="<?php echo $estimatedValue[0]['conv_allowance'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top" > <strong>c) Children Educational Allowance (@ 100 PM for 2 child)</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration5" name="childreneducationalallowance" value="<?php if($estimatedValue[0]['childreneducationalallowance'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['childreneducationalallowance']; } } else { echo 'NA';}?>" <?php if($estimatedValue[0]['childreneducationalallowance'] =='0.00'){ echo 'disabled ';}  ?> class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration6" name="eligible_childreneducationalallowance" value="<?php if($estimatedValue[0]['childreneducationalallowance'] !='0.00'){ if(count($empInfo) > 0) { echo $empInfo[0]['eligible_childreneducationalallowance']; } } else { echo 'NA';} ?>"  READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration6_limit" name="income_tax_declaration6_limit" value="<?php echo $estimatedValue[0]['childreneducationalallowance'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>d) Medical Expenses (Max 15000 Per Annum)</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration7" name="medicalexpensesperannum" value="<?php if($estimatedValue[0]['medicalexpensesperannum'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['medicalexpensesperannum']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['medicalexpensesperannum'] =='0.00'){ echo 'disabled ';} ?> class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration8" name="eligible_medicalexpensesperannum" value="<?php if($estimatedValue[0]['medicalexpensesperannum'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['eligible_medicalexpensesperannum']; } } else { echo 'NA';} ?>" READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration8_limit" name="income_tax_declaration8_limit" value="<?php echo $estimatedValue[0]['medicalexpensesperannum'];?>"  />
											</td>
										</tr>
										<tr class="info">
											<td colspan="3" class="form_title"><strong>Deductions U/s 10(5) (Leave Travel Concession)</strong></td>
										</tr>
										<tr>
											<td valign="top"> <strong> Twice in a block of 4 years</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration9" name="leavetravelconcession" value="<?php if($estimatedValue[0]['leavetravelconcession'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['leavetravelconcession']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['leavetravelconcession'] =='0.00'){ echo 'disabled ';} ?>class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration10" name="eligible_leavetravelconcession" value="<?php if($estimatedValue[0]['leavetravelconcession'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['leavetravelconcession']; } } else { echo 'NA';} ?>" READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration10_limit" name="income_tax_declaration10_limit" value="<?php echo $estimatedValue[0]['leavetravelconcession'];?>"  />
											</td>
										</tr>
										<tr>
											<td colspan="3" class="form_title"><strong>Deductions</strong></td>
										</tr>
										<tr>
											<td valign="top"> <strong>a) Entertainment Allowance </strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration11" name="entertainment_allowance" value="<?php if($estimatedValue[0]['entertainment_allowance'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['entertainment_allowance']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['entertainment_allowance'] =='0.00'){ echo 'disabled ';} ?> class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration12" name="eligible_entertainment_allowance" value="<?php if($estimatedValue[0]['entertainment_allowance'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['entertainment_allowance']; } } else { echo 'NA';} ?>" READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration12_limit" name="income_tax_declaration12_limit" value="<?php echo $estimatedValue[0]['entertainment_allowance'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>b) Professional Tax(As Per PT Slab)</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration13" name="professional_tax" value="<?php if($estimatedValue[0]['professional_tax'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['professional_tax']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['professional_tax'] =='0.00'){ echo 'disabled ';} ?> class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration14" name="eligible_professional_tax" value="<?php if($estimatedValue[0]['professional_tax'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['professional_tax']; } } else { echo 'NA';} ?>" READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration14_limit" name="income_tax_declaration14_limit" value="<?php echo $estimatedValue[0]['professional_tax'];?>"  />
											</td>
										</tr>
										<tr class="info">
											<td colspan="3" class="form_title"><strong>Deductions U/s 24(Interest on Housing Loan)</strong></td>
										</tr>
										<tr>
											<td valign="top" > <strong>a) Self Occupied Property (Max) 200000</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration15" name="self_occupied_property" value="<?php if($estimatedValue[0]['self_occupied_property'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['self_occupied_property']; } } else { echo 'NA';} ?>" class="qty6 numbersOnly form-control" <?php if($estimatedValue[0]['self_occupied_property'] =='0.00'){ echo 'disabled ';} ?> style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration16" name="eligible_self_occupied_property" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['eligible_self_occupied_property'];?>"  READONLY class="qty6 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration16_limit" name="income_tax_declaration16_limit" value="<?php echo $estimatedValue[0]['self_occupied_property'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>c) Let-out/Rented Property - No Limit</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration17" name="let_our_rented_property" value="<?php if($estimatedValue[0]['let_out_rented_property'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['let_out_rented_property']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['let_out_rented_property'] =='0.00'){ echo 'disabled ';} ?> class="qty6 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration18" name="eligible_let_our_rented_property" value="<?php if($estimatedValue[0]['let_out_rented_property'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['let_out_rented_property']; } } else { echo 'NA';} ?>"  disabled class="qty6 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration18_limit" name="income_tax_declaration18_limit" value="<?php echo $estimatedValue[0]['let_out_rented_property'];?>"  />
											</td>
										</tr>
										<tr>
											<td colspan="3" class="form_title"><strong>Deductions Under Chapter VI-A</strong></td>
										</tr>
										<tr class="info">
											<td colspan="3" class="form_title"><strong>Deductions U/s 80C (Max 1.5 Lakh Per Annum)</strong></td>
										</tr> 
										<tr>
											<td valign="top"> <strong>a) Contribution To Provident Fund </strong></td>
											<td valign="top">
												<input type="text" id="contribution_provident_fund" name="contribution_provident_fund" value="<?php echo $get_pf; ?>" disabled class="qty1 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
											 
										<tr>
											<td valign="top"> <strong>b) Life Insurance Premium</strong></td>
											<td valign="top">
												<input type="text" id="lic" name="lic" value="<?php if(count($empInfo) > 0){ echo $empInfo[0]['lic'];} ?>" class="qty1 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>c) Public Provident Fund (PPF) </strong></td>
											<td valign="top">
												<input type="text" id="public_provident_fund" name="public_provident_fund" value="<?php if(count($empInfo) > 0){ echo $empInfo[0]['public_provident_fund'];} ?>" class="qty1 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>d) National Savings Certificate (NSC) </strong></td>
											<td valign="top">
												<input type="text" id="nsc" name="nsc" value="<?php if(count($empInfo) > 0){ echo $empInfo[0]['nsc'];} ?>" class="qty1 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>e) Children Education Fee</strong></td>
											<td valign="top">
												<input type="text" id="childreneducationfee" name="childreneducationfee" value="<?php if(count($empInfo) > 0){ echo $empInfo[0]['childreneducationfee'];} ?>" class="qty1 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>f) Mutual Funds Or UTI</strong></td>
											<td valign="top">
												<input type="text" id="mutualfund_or_uti" name="mutualfund_or_uti" value="<?php if(count($empInfo) > 0){ echo $empInfo[0]['mutualfund_or_uti'];} ?>" class="qty1 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>g) Contribution to notified pension fund </strong></td>
											<td valign="top">
												<input type="text" id="contribution_notified_pension_fund" name="contribution_notified_pension_fund" value="<?php if(count($empInfo) > 0){ echo $empInfo[0]['contribution_notified_pension_fund'];} ?>" class="qty1 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>h) Unit linked Insurance Plan(ULIP)</strong></td>
											<td valign="top">
												<input type="text" id="ulip" name="ulip" value="<?php if(count($empInfo) > 0){ echo $empInfo[0]['ulip']; } ?>" class="qty1 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>i) Deposit In Post Office Tax Saving Scheme </strong></td>
											<td valign="top">
												<input type="text" id="postofficetax" name="postofficetax" value="<?php if(count($empInfo) > 0){ echo $empInfo[0]['postofficetax'];} ?>" class="qty1 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>j) Equity Linked Savings Scheme (ELSS) </strong></td>
											<td valign="top">
												<input type="text" id="elss" name="elss" value="<?php if(count($empInfo) > 0){ echo $empInfo[0]['elss'];} ?>" class="qty1 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>k) Housing Loan Principal Amount Repayment</strong></td>
											<td valign="top">
												<input type="text" id="housingloanprincipal" name="housingloanprincipal" value="<?php if(count($empInfo) > 0){ echo $empInfo[0]['housingloanprincipal'];} ?>" class="qty1 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>l) Fixed Deposit with Scheduled Bank for a period of 5 years or more </strong></td>
											<td valign="top">
												<input type="text" id="fixeddeposit" name="fixeddeposit" value="<?php if(count($empInfo) > 0){ echo $empInfo[0]['fixeddeposit'];} ?>" class="qty1 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>l) Any Other Tax Saving Investment </strong></td>
											<td valign="top">
												<input type="text" id="any_other_tax" name="any_other_tax" value="<?php if(count($empInfo) > 0){ echo $empInfo[0]['any_other_tax'];} ?>" class="qty1 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>Total </strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration19" name="total_deduction80c" value="<?php if(count($empInfo) > 0) { echo $empInfo[0]['total_deduction80c'];} ?>" READONLY class="total1 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration20" name="eligible_total_deduction80c" value="<?php if(count($empInfo) > 0){ echo $empInfo[0]['eligible_total_deduction80c'];} ?>" READONLY class="total7 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr class="info">
											<td colspan="3" class="form_title"><strong>Deductions U/s 80CCD Contribution to NPS Rs 50000</strong></td>
										</tr>
										<tr>
											<td valign="top"> <strong>Employee Contribution</strong></td>
											<td valign="top" class="form_title">
												<input type="text" id="income_tax_declaration21" name="deduction_under_80CCD" value="<?php if($estimatedValue[0]['deduction80CCD_contribution_nps'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['deduction_under_80CCD']; } } else { echo 'NA';} ?>" class="qty211 number numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top" class="form_title">
												<input type="text" id="income_tax_declaration22" name="eligible_deduction_under_80CCD" value="<?php if($estimatedValue[0]['deduction80CCD_contribution_nps'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['eligible_deduction_under_80CCD'];} } else { echo 'NA';} ?>" READONLY class="qty221 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration22_limit" name="income_tax_declaration22_limit" value="<?php echo $estimatedValue[0]['deduction80CCD_contribution_nps'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>Employer Contribution</strong></td>
											<td valign="top" class="form_title">
												<input type="text" id="income_tax_declaration121" name="na" value="NA" class="number numbersOnly form-control" style="width:100px; margin-bottom: 10px;" disabled />
											</td>
											<td valign="top" class="form_title">
												<input type="text" id="income_tax_declaration122" name="na" value="NA" READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" disabled />
											</td>
										</tr>
										<!--<tr>
											<td valign="top"> <strong>Total </strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration21221" name="total_deduction80ccd" value="<?php if(count($empInfo) > 0) { echo $empInfo[0]['total_deduction80c'];} ?>" READONLY class="tota211 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration21222" name="eligible_total_deduction80ccd" value="<?php if(count($empInfo) > 0){ echo $empInfo[0]['eligible_total_deduction80c'];} ?>" READONLY class="tota221 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>-->
										<tr class="info">
											<td colspan="3" class="form_title"><strong>Deductions U/s 80D </strong></td>
										</tr>
										<tr>
											<td valign="top" class="form_title"> <strong>Deductions U/s 80D (Max 25000 - Self/Family)</strong></td>
											<td valign="top" class="form_title">
												<input type="text" id="income_tax_declaration23" name="deduction_under_80D_selffamily" value="<?php if($estimatedValue[0]['selfsfamily'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['deduction_under_80D_selffamily']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['selfsfamily'] =='0.00'){ echo 'disabled ';} ?> class="qty2 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top" class="form_title">
												<input type="text" id="income_tax_declaration24" name="eligible_deduction_under_80D_selffamily" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['eligible_deduction_under_80D_selffamily'];?>" READONLY class="qty6 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration24_limit" name="income_tax_declaration24_limit" value="<?php echo $estimatedValue[0]['selfsfamily'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top" class="form_title"> <strong>Deductions U/s 80D (Max 25000 - Parents) </strong></td>
											<td valign="top" class="form_title">
												<input type="text" id="income_tax_declaration25" name="deduction_under_80D_parents" value="<?php if($estimatedValue[0]['parents'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['deduction_under_80D_parents']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['parents'] =='0.00'){ echo 'disabled ';} ?> class="qty2 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top" class="form_title">
												<input type="text" id="income_tax_declaration26" name="eligible_deduction_under_80D_parents" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['eligible_deduction_under_80D_parents'];?>" READONLY class="qty6 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration26_limit" name="income_tax_declaration26_limit" value="<?php echo $estimatedValue[0]['parents'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top" class="form_title"> <strong>Additional Deductions Rs. 5000 incase of senior citizen </strong></td>
											<td valign="top" class="form_title">
												<input type="text" id="income_tax_declaration27" name="deduction_incase_senior_citizen" value="<?php if($estimatedValue[0]['deductions_senior_citizen'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['deduction_incase_senior_citizen']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['deductions_senior_citizen'] =='0.00'){ echo 'disabled ';} ?> class="qty2 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top" class="form_title">
												<input type="text" id="income_tax_declaration28" name="eligible_deduction_incase_senior_citizen" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['eligible_deduction_incase_senior_citizen'];?>" READONLY class="qty6 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration28_limit" name="income_tax_declaration28_limit" value="<?php echo $estimatedValue[0]['deductions_senior_citizen'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>Total</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration29" name="total_deduction_incase_senior_citizen" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['total_deduction_incase_senior_citizen'];?>" READONLY class="total2 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration30" name="eligible_total_deduction_incase_senior_citizen" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['eligible_total_deduction_incase_senior_citizen'];?>" READONLY class="total8 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr class="info">
											<td colspan="3" class="form_title"><strong>Deductions U/s 80DD (only Dependants)</strong></td>
										</tr>
										<tr>
											<td valign="top"> <strong>a) Medical treatment of Depedent (Normal Disability - Max 50000):</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration31" name="normal_disability" value="<?php if($estimatedValue[0]['dependants_normal_disability'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['normal_disability']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['dependants_normal_disability'] =='0.00'){ echo 'disabled ';} ?> class="qty3 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration32" name="eligible_normal_disability" value="<?php if($estimatedValue[0]['dependants_normal_disability'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['eligible_normal_disability']; } } else { echo 'NA';} ?>"  READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration32_limit" name="income_tax_declaration32_limit" value="<?php echo $estimatedValue[0]['dependants_normal_disability'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>b) Medical treatment of Depedent (Severe Disability - Max 100000)</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration33" name="severe_disability" value="<?php if($estimatedValue[0]['dependants_severe_disability'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['severe_disability']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['dependants_severe_disability'] =='0.00'){ echo 'disabled ';} ?> class="qty3 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration34" name="eligible_severe_disability" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['eligible_severe_disability'];?>"  READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration34_limit" name="income_tax_declaration34_limit" value="<?php echo $estimatedValue[0]['dependants_severe_disability'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>Total</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration35" name="total_deduction_80dd" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['total_deduction_80dd'];?>" READONLY class="total3 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration36" name="total_eligible_deduction_80dd" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['total_eligible_deduction_80dd'];?>"  READONLY class="total9 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr class="info">
											<td colspan="3" class="form_title"><strong>Deductions U/s 80DDB (Self/Dependant for specified diseases)</strong></td>
										</tr>
										<tr>
											<td valign="top"><strong>Medical treatment of normal case (Max 40000)</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration37" name="medical_treatment_normal_case" value="<?php if($estimatedValue[0]['meducal_norman_case'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['medical_treatment_normal_case']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['meducal_norman_case'] =='0.00'){ echo 'disabled ';} ?> class="qty4 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration38" name="eligible_medical_treatment_normal_case" value="<?php if($estimatedValue[0]['meducal_norman_case'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['eligible_medical_treatment_normal_case']; } } else { echo 'NA';} ?>" READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration38_limit" name="income_tax_declaration38_limit" value="<?php echo $estimatedValue[0]['meducal_norman_case'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>Senior Citizen > 60 age (60000)</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration39" name="senior_citizen_60" value="<?php if($estimatedValue[0]['senior_citizen60'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['senior_citizen_60']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['senior_citizen60'] =='0.00'){ echo 'disabled ';} ?> class="qty4 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration40" name="eligible_senior_citizen_60" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['eligible_senior_citizen_60'];?>"  READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration40_limit" name="income_tax_declaration40_limit" value="<?php echo $estimatedValue[0]['senior_citizen60'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top"><strong>Super senior citizen > 80 age(80000)</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration41" name="super_senior_citizen_80" value="<?php if($estimatedValue[0]['super_senior_citizen80'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['super_senior_citizen_80']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['super_senior_citizen80'] =='0.00'){ echo 'disabled ';} ?> class="qty4 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration42" name="eligible_super_senior_citizen_80" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['eligible_super_senior_citizen_80'];?>" READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration42_limit" name="income_tax_declaration42_limit" value="<?php echo $estimatedValue[0]['super_senior_citizen80'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>Total</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration43" name="total_deduction_80ddb" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['total_deduction_80ddb'];?>" READONLY class="total4 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration44" name="total_eligible_deduction_80ddb" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['total_eligible_deduction_80ddb'];?>" READONLY class="total10 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr>
										<tr class="info">
											<td colspan="3" class="form_title"><strong>Deductions U/s 80E</strong></td>
										</tr>
										<tr>
											<td valign="top"> <strong>a) Interest on Loan for higher education - U/s 80E</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration45" name="interest_loan_higher_education_80e" value="<?php if($estimatedValue[0]['interest_loan_higher_education_80E'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['interest_loan_higher_education_80e']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['interest_loan_higher_education_80E'] =='0.00'){ echo 'disabled ';} ?> class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration46" name="eligible_interest_loan_higher_education_80e" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['eligible_interest_loan_higher_education_80e'];?>" READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration46_limit" name="income_tax_declaration46_limit" value="<?php echo $estimatedValue[0]['interest_loan_higher_education_80E'];?>"  />
											</td>
										</tr>
										<tr class="info">
											<td colspan="3" class="form_title"><strong>Deductions U/s 80EE</strong></td>
										</tr>
										<tr>
											<td valign="top"> <strong>b) Interest on Home Loan(First Time Buyer) Rs. 50000</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration47" name="interest_home_loan_80ee" value="<?php if($estimatedValue[0]['interest_home_loan_80E'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['interest_home_loan_80ee']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['interest_home_loan_80E'] =='0.00'){ echo 'disabled ';} ?> class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration48" name="eligible_interest_home_loan_80ee" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['eligible_interest_home_loan_80ee'];?>" READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration48_limit" name="income_tax_declaration48_limit" value="<?php echo $estimatedValue[0]['interest_home_loan_80E'];?>"  />
											</td>
										</tr>
										<tr  class="info">
											<td colspan="3" class="form_title"><strong>Deductions U/s 80G (Donations)</strong></td>
										</tr>
										<tr>
											<td valign="top"> <strong>Actual Donation Made(50% or 100% depending on the donee)<br>(Subject to max 10% of Gross Total Income)</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration49" name="actual_donation_80g" value="<?php  if(count($empInfo) > 0){ echo $empInfo[0]['actual_donation_80g']; } ?>" class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration50" name="eligible_actual_donation_80g" value="<?php  if(count($empInfo) > 0){ echo $empInfo[0]['eligible_actual_donation_80g']; }?>" READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration50_limit" name="income_tax_declaration50_limit" value="<?php echo $estimatedValue[0]['actual_donation'];?>"  />
											</td>
										</tr>
										<tr class="info">
											<td colspan="3" class="form_title"><strong>Deductions U/s 80U (only for Self)</strong></td>
										</tr>
										<tr>
											<td valign="top"> <strong>a) Medical treatment of Self (Normal Disability - Max 50000)</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration51" name="deduction_under_80U_noraml_disability" value="<?php if($estimatedValue[0]['self_normal_disability'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['deduction_under_80U_noraml_disability']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['self_normal_disability'] =='0.00'){ echo 'disabled ';} ?> class="qty5 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration52" name="eligible_deduction_under_80U_noraml_disability" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['eligible_deduction_under_80U_noraml_disability'];?>" READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration52_limit" name="income_tax_declaration52_limit" value="<?php echo $estimatedValue[0]['self_normal_disability'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>b) Medical treatment of Self (Severe Disability - Max 100000)</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration53" name="deduction_under_80U_severe_disability" value="<?php if($estimatedValue[0]['self_severe_disability'] !='0.00'){ if(count($empInfo) > 0){ echo $empInfo[0]['deduction_under_80U_severe_disability']; } } else { echo 'NA';} ?>" <?php if($estimatedValue[0]['self_severe_disability'] =='0.00'){ echo 'disabled ';} ?> class="qty5 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration54" name="eligible_deduction_under_80U_severe_disability" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['eligible_deduction_under_80U_severe_disability'];?>" READONLY class="numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
												<input type="hidden" id="income_tax_declaration54_limit" name="income_tax_declaration54_limit" value="<?php echo $estimatedValue[0]['self_severe_disability'];?>"  />
											</td>
										</tr>
										<tr>
											<td valign="top"> <strong>Total</strong></td>
											<td valign="top">
												<input type="text" id="income_tax_declaration55" name="total_deduction_under_80U" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['total_deduction_under_80U'];?>" READONLY class="total5 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
											<td valign="top">
												<input type="text" id="income_tax_declaration56" name="eligible_deduction_under_80U" value="<?php if(count($empInfo) > 0) echo $empInfo[0]['eligible_deduction_under_80U'];?>" READONLY class="total11 numbersOnly form-control" style="width:100px; margin-bottom: 10px;" />
											</td>
										</tr> 
									</tbody>
								</table> 
							</div>
							<div class="form-group"> 
								<div class="col-md-12">
									<?php if($empInfo[0]['tid'] != ""){ ?>
									<input type="submit" id="btnRejectMessageReview" name="btnRejectMessageReview" class="btn btn-info pull-right" value="Reject" />
									<input type="submit" id="btnAddMessageReview" name="btnAddMessageReview" onclick="return(formValidateReview());" class="btn btn-info pull-righ" value="<?php if($this->input->get('id')!='') echo 'APPROVE'; else echo "APPLY"; ?>" />
									<?php }  else{ ?>
									<input type="submit" id="btnAddMessage" name="btnAddMessage" onclick="return(formValidate());" class="btn btn-info pull-right" value="<?php if($this->input->get('id')!='') echo 'APPLY'; else echo "APPLY"; ?>" />
									<?php } ?> 
									<input type="hidden" name="login_id" id="login_id" value="<?php echo $this->input->get('id');?>" /> 
									<!--<input type="submit" id="btnAddMessage" name="btnAddMessage" value="SUBMIT" class="btn btn-primary pull-right"/> -->
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
