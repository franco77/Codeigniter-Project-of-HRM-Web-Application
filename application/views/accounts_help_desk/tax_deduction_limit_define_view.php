<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="accounts" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('accounts_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<span  class="pull-right " role="button" style="margin-right: 10px; margin-top: 4px;" > <span style="color:#fff;">FY : </span>
						<select ng-model="searchYear" name="searchYear" id="searchYear" class="input-sm" ng-change="getFYData();" > 
							<?php
							  $m = date('m');
								$y = date('Y');
								if($m >=4){
									$yr = date('Y');
								}
								else{
									$yr = $y - 1;
								}
							  for ($j=$yr;$j>=2017;$j--){
							  if ($j == $fyear){
							 ?>
							  <option value="<?php echo $j.'-'.($j+1);?>" selected ><?php echo $j.'-'.($j+1);?></option>
							 <?php }else{?>
							 <option value="<?php echo $j.'-'.($j+1);?>" ><?php echo $j.'-'.($j+1);?></option>
							 <?php }
							 }?> 
						</select>
					</span>
					<legend class="pkheader">Tax Exemption/Deduction Limit</legend>
					<div class="row well"> 
						<div class="table-responsive" ng-show="filteredItems > 0">
							<table class="table table-striped table-hover table-bordered"> 
								<tbody ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
									<tr>
										<td> <strong> For the Financial Year</strong></td>
										<td>
											<strong>{{searchYear}}</strong>
										</td>
									</tr>
									<tr class="info">
										<td colspan="2" align="center"><strong>Exemption U/s 10</strong></td>
									</tr>
									<tr>
										<td> a) Rent Paid by Employee(Per Annum)</td>
										<td ng-if="data.rent_oaid_by_employee == '0.00'">
											Actual
										</td>
										<td ng-if="data.rent_oaid_by_employee != '0.00'">
											Actual
										</td>
									</tr>
									<tr>
										<td> b) Conveyance Allowance (1600 Per Month)</td>
										<td ng-if="data.conv_allowance == '0.00'">
											NA
										</td>
										<td ng-if="data.conv_allowance != '0.00'">
											{{data.conv_allowance}}
										</td>
									</tr>
									<tr>
										<td> c) Children Educational Allowance (@ 100 PM for max 2 child)</td>
										<td ng-if="data.childreneducationalallowance == '0.00'">
											NA
										</td>
										<td ng-if="data.childreneducationalallowance != '0.00'">
											{{data.childreneducationalallowance}}
										</td>
									</tr>
									<tr>
										<td> d) Medical Expenses (Max 15000 Per Annum)</td>
										<td ng-if="data.medicalexpensesperannum == '0.00'">
											NA
										</td>
										<td ng-if="data.medicalexpensesperannum != '0.00'">
											{{data.medicalexpensesperannum}}
										</td>
									</tr>
									<tr class="info">
										<td colspan="2" align="center"><strong>Deductions U/s 10(5) (Leave Travel Concession)</strong></td>
									</tr>
									<tr>
										<td>Twice in a block of 4 years</td>
										<td ng-if="data.leavetravelconcession == '0.00'">
											NA
										</td>
										<td ng-if="data.leavetravelconcession != '0.00'">
											{{data.leavetravelconcession}}
										</td>
									</tr>
									<tr class="info">
										<td colspan="2" align="center"><strong>Deductions</strong></td>
									</tr>
									<tr>
										<td>a) Entertainment Allowance</td>
										<td ng-if="data.entertainment_allowance == '0.00'">
											NA
										</td>
										<td ng-if="data.entertainment_allowance != '0.00'">
											{{data.entertainment_allowance}}
										</td>
									</tr>
									<tr>
										<td>b) Professional Tax(As per PT Slab)</td>
										<td ng-if="data.professional_tax == '0.00'">
											NA
										</td>
										<td ng-if="data.professional_tax != '0.00'">
											Actual ({{data.professional_tax}})
										</td>
									</tr>
									<tr class="info">
										<td colspan="2" align="center"><strong>Deductions U/s 24(Interest on Housing Loan)</strong></td>
									</tr>
									<tr>
										<td>a) Self Occupied Property (Max) 200000</td>
										<td ng-if="data.self_occupied_property == '0.00'">
											NA
										</td>
										<td ng-if="data.self_occupied_property != '0.00'">
											{{data.self_occupied_property}}
										</td>
									</tr>
									<tr>
										<td>b) Let-out/Rented Property - No Limit</td>
										<td ng-if="data.let_out_rented_property == '0.00'">
											NA
										</td>
										<td ng-if="data.let_out_rented_property != '0.00'">
											{{data.let_out_rented_property}}
										</td>
									</tr>
									<tr class="info">
										<td colspan="2" align="center"><strong>Deductions Under Chapter VI-A</strong></td>
									</tr>
									<tr>
										<td> <strong>Deductions U/s 80C (Max 1.5 Lakh Per Annum)</strong></td>
										<td ng-if="data.deduction80C == '0.00'">
											NA
										</td>
										<td ng-if="data.deduction80C != '0.00'">
											{{data.deduction80C}}
										</td>
									</tr>
									<tr>
										<td> <strong>Deductions U/s 80CCD (1B) Contribution to NPS Rs 50000</strong></td>
										<td ng-if="data.deduction80CCD_contribution_nps == '0.00'">
											NA
										</td>
										<td ng-if="data.deduction80CCD_contribution_nps != '0.00'">
											{{data.deduction80CCD_contribution_nps}}
										</td>
									</tr>
									<tr>
										<td> <strong>Deductions U/s 80D (Max 25000 - Self/Family)</strong></td>
										<td ng-if="data.selfsfamily == '0.00'">
											NA
										</td>
										<td ng-if="data.selfsfamily != '0.00'">
											{{data.selfsfamily}}
										</td>
									</tr>
									<tr>
										<td> <strong>Deductions U/s 80D (Max 25000 - Parents) </strong></td>
										<td ng-if="data.parents == '0.00'">
											NA
										</td>
										<td ng-if="data.parents != '0.00'">
											{{data.parents}}
										</td>
									</tr>
									<tr>
										<td> <strong>Additional Deductions Rs. 5000 incase of senior citizen </strong></td>
										<td ng-if="data.deductions_senior_citizen == '0.00'">
											NA
										</td>
										<td ng-if="data.deductions_senior_citizen != '0.00'">
											{{data.deductions_senior_citizen}}
										</td>
									</tr>
									<tr class="info">
										<td colspan="2" align="center"><strong>Deductions U/s 80DD (only Dependants)</strong></td>
									</tr>
									<tr>
										<td>a) Medical treatment of Depedent (Normal Disability - Max 50000):</td>
										<td ng-if="data.dependants_normal_disability == '0.00'">
											NA
										</td>
										<td ng-if="data.dependants_normal_disability != '0.00'">
											{{data.dependants_normal_disability}}
										</td>
									</tr>
									<tr>
										<td>b) Medical treatment of Depedent (Severe Disability - Max 100000)</td>
										<td ng-if="data.dependants_severe_disability == '0.00'">
											NA
										</td>
										<td ng-if="data.dependants_severe_disability != '0.00'">
											{{data.dependants_severe_disability}}
										</td>
									</tr>
									<tr class="info">
										<td colspan="2" align="center"><strong>Deductions U/s 80DDB (Self/Dependant for specified diseases)</strong></td>
									</tr>
									<tr>
										<td> <strong>Medical treatment of normal case (Max 40000)</strong></td>
										<td ng-if="data.meducal_norman_case == '0.00'">
											NA
										</td>
										<td ng-if="data.meducal_norman_case != '0.00'">
											{{data.meducal_norman_case}}
										</td>
									</tr>
									<tr>
										<td> <strong>Senior Citizen > 60 age (60000)</strong></td>
										<td ng-if="data.senior_citizen60 == '0.00'">
											NA
										</td>
										<td ng-if="data.senior_citizen60 != '0.00'">
											{{data.senior_citizen60}}
										</td>
									</tr>
									<tr>
										<td> <strong>Super senior citizen > 80 age(80000)</strong></td>
										<td ng-if="data.super_senior_citizen80 == '0.00'">
											NA
										</td>
										<td ng-if="data.super_senior_citizen80 != '0.00'">
											{{data.super_senior_citizen80}}
										</td>
									</tr>
									<tr class="info">
										<td colspan="2" align="center"><strong>Deductions U/s 80E</strong></td>
									</tr>
									<tr>
										<td>a) Interest on Loan for higher education - U/s 80E</td>
										<td ng-if="data.interest_loan_higher_education_80E == '0.00'">
											Actual
										</td>
										<td ng-if="data.interest_loan_higher_education_80E != '0.00'">
											Actual 
										</td>
									</tr>
									<tr class="info">
										<td colspan="2" align="center"><strong>Deductions U/s 80EE</strong></td>
									</tr>
									<tr>
										<td>a) Interest on Home Loan(First Time Buyer) Rs. 50000</td>
										<td ng-if="data.interest_home_loan_80E == '0.00'">
											NA
										</td>
										<td ng-if="data.interest_home_loan_80E != '0.00'">
											{{data.interest_home_loan_80E}}
										</td>
									</tr>
									<tr class="info">
										<td colspan="2" align="center"><strong>Deductions U/s 80G (Donations)</strong></td>
									</tr>
									<tr>
										<td> <strong>Actual Donation Made(50% or 100% depending on the donee)<br>(Subject to max 10% of Gross Total Income)</strong></td>
										<td ng-if="data.actual_donation == '0.00'">
											Actual
										</td>
										<td ng-if="data.actual_donation != '0.00'">
											Actual
										</td>
									</tr> 
									<tr class="info">
										<td colspan="2" align="center"><strong>Deductions U/s 80U (only for Self)</strong></td>
									</tr>
									<tr>
										<td>a) Medical treatment of Self (Normal Disability - Max 50000)</td>
										<td ng-if="data.self_normal_disability == '0.00'">
											NA
										</td>
										<td ng-if="data.self_normal_disability != '0.00'">
											{{data.self_normal_disability}}
										</td>
									</tr>
									<tr>
										<td>b) Medical treatment of Self (Severe Disability - Max 100000)</td>
										<td ng-if="data.self_severe_disability == '0.00'">
											NA
										</td>
										<td ng-if="data.self_severe_disability != '0.00'">
											{{data.self_severe_disability}}
										</td>
									</tr>  
									<tr class="info">
										<td colspan="2" align="center"><strong>Standard Deduction</strong></td>
									</tr>
									<tr>
										<td><strong>For the Financial Year {{searchYear}}</strong></td>
										<td ng-if="data.standard_deduction == '0.00'">
											NA
										</td>
										<td ng-if="data.standard_deduction != '0.00'">
											{{data.standard_deduction}}
										</td>
									</tr>
								</tbody> 
							</table>
						</div>
						<!-- /.table-responsive -->
					</div>
					<div class="row">
						<div class="col-lg-12" ng-show="filteredItems == 0">
							<div class="col-lg-12">
							<h4>No records found</h4>
							</div> 
						</div>
						<!--<div class="col-lg-12" ng-show="filteredItems > 0">    
							<div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="&laquo;" next-text="&raquo;"></div> 
						</div> 
						<div class="col-lg-8">
							<div id="morris-bar-chart"></div>
						</div> -->
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div> 
</div>
