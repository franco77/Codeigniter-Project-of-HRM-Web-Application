<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
 
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<h4 class="section-title">
				<span class="c-dark">Accounts Help Desk</span>
			</h4>
		</div>
	</div>
</div>
 
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
					<div class="well">
						<h4 class="box-title">Tax Exemption/Deduction Limit</h4> 
					</div>
					<div class="row well">
						<div class="table-responsive" ng-show="filteredItems > 0">
							<table class="table table-striped table-bordered">
								<tbody ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
									<tr>
										<td> <strong> For the Financial Year</strong></td>
										<td>
											<strong>2017-18</strong>
										</td>
									</tr>
									<tr>
										<td><strong>Exemption U/s 10</strong></td>
									</tr>
									<tr>
										<td>a)Rent Paid by Employee(Per Annum)</td>
										<td>
											Actual
										</td>
									</tr>
									<tr>
										<td>b) Conveyance Allowance (1600 Per Month)</td>
										<td>
											{{data.conv_allowance}}
										</td>
									</tr>
									<tr>
										<td>c) Children Educational Allowance (@ 100 PM for max 2 child)</td>
										<td>
											{{data.childreneducationalallowance}}
										</td>
									</tr>
									<tr>
										<td>d) Medical Expenses (Max 15000 Per Annum)</td>
										<td>
											{{data.medicalexpensesperannum}}
										</td>
									</tr>
									<tr>
										<td><strong>Deductions U/s 10(5) (Leave Travel Concession)</strong></td>
									</tr>
									<tr>
										<td>Twice in a block of 4 years</td>
										<td>
											NA
										</td>
									</tr>
									<tr>
										<td><strong>Deductions</strong></td>
									</tr>
									<tr>
										<td>a) Entertainment Allowance</td>
										<td>
											NA
										</td>
									</tr>
									<tr>
										<td>b) Professional Tax(As per PT Slab)</td>
										<td>
											Actual
										</td>
									</tr>
									<tr>
										<td><strong>Deductions U/s 24(Interest on Housing Loan)</strong></td>
									</tr>
									<tr>
										<td>a) Self Occupied Property (Max) 200000</td>
										<td>
											{{data.self_occupied_property}}
										</td>
									</tr>
									<tr>
										<td>b) Let-out/Rented Property - No Limit</td>
										<td>
											NA
										</td>
									</tr>
									<tr>
										<td><strong>Deductions Under Chapter VI-A</strong></td>
									</tr>
									<tr>
										<td> <strong>Deductions U/s 80C (Max 1.5 Lakh Per Annum)</strong></td>
										<td>
											{{data.deduction80C}}
										</td>
									</tr>
									<tr>
										<td> <strong>Deductions U/s 80CCD Contribution to NPS Rs 50000</strong></td>
										<td>
											{{data.deduction80CCD_contribution_nps}}
										</td>
									</tr>
									<tr>
										<td> <strong>Deductions U/s 80D (Max 25000 - Self/Family)</strong></td>
										<td>
											{{data.selfsfamily}}
										</td>
									</tr>
									<tr>
										<td> <strong>Deductions U/s 80D (Max 25000 - Parents) </strong></td>
										<td>
											{{data.parents}}
										</td>
									</tr>
									<tr>
										<td> <strong>Additional Deductions Rs. 5000 incase of senior citizen </strong></td>
										<td>
											{{data.deductions_senior_citizen}}
										</td>
									</tr>
									<tr>
										<td><strong>Deductions U/s 80DD (only Dependants)</strong></td>
									</tr>
									<tr>
										<td>a) Medical treatment of Depedent (Normal Disability - Max 50000):</td>
										<td>
											{{data.dependants_normal_disability}}
										</td>
									</tr>
									<tr>
										<td>b) Medical treatment of Depedent (Severe Disability - Max 100000)</td>
										<td>
											{{data.dependants_severe_disability}}
										</td>
									</tr>
									<tr>
										<td><strong>Deductions U/s 80DDB (Self/Dependant for specified diseases)</strong></td>
									</tr>
									<tr>
										<td> <strong>Medical treatment of normal case (Max 40000)</strong></td>
										<td>
											{{data.senior_citizen60}}
										</td>
									</tr>
									<tr>
										<td> <strong>Senior Citizen > 60 age (60000)</strong></td>
										<td>
											{{data.super_senior_citizen80}}
										</td>
									</tr>
									<tr>
										<td> <strong>Super senior citizen > 80 age(80000)</strong></td>
										<td>
											{{data.interest_loan_higher_education_80E}}
										</td>
									</tr>
									<tr>
										<td><strong>Deductions U/s 80E</strong></td>
									</tr>
									<tr>
										<td>a) Interest on Loan for higher education - U/s 80E</td>
										<td>
											Actual
										</td>
									</tr>
									<tr>
										<td><strong>Deductions U/s 80EE</strong></td>
									</tr>
									<tr>
										<td>a) Interest on Home Loan(First Time Buyer) Rs. 50000</td>
										<td>
											{{data.interest_home_loan_80E}}
										</td>
									</tr>
									<tr>
										<td><strong>Deductions U/s 80G (Donations)</strong></td>
									</tr>
									<tr>
										<td> <strong>Actual Donation Made(50% or 100% depending on the donee)<br>(Subject to max 10% of Gross Total Income)</strong></td>
										<td>
											Actual
										</td>
									</tr> 
									<tr>
										<td><strong>Deductions U/s 80U (only for Self)</strong></td>
									</tr>
									<tr>
										<td>a) Medical treatment of Self (Normal Disability - Max 50000)</td>
										<td>
											{{data.self_normal_disability}}
										</td>
									</tr>
									<tr>
										<td>b) Medical treatment of Self (Severe Disability - Max 100000)</td>
										<td>
											{{data.self_severe_disability}}
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
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
						<div class="col-lg-12" ng-show="filteredItems > 0">    
							<div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="&laquo;" next-text="&raquo;"></div> 
						</div>
						<!-- /.col-lg-4 (nested) -->
						<div class="col-lg-8">
							<div id="morris-bar-chart"></div>
						</div> 
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div> 
</div>
