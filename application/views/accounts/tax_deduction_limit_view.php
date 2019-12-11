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
					<span  class="pull-right " role="button" style="margin-right: 10px; margin-top: 4px;" > <span style="color:#fff;">FY : </span>
						<select name="searchYear1" id="searchYear1" class="input-sm" onchange="getFYData(this);" > 
							<?php
							  $yr=date("Y");
							  for ($j=$yr;$j>=2017;$j--){
							  if ($j == $fyear){
							 ?>
							  <option value="<?php echo $j;?>" selected ><?php echo $j.'-'.($j+1);?></option>
							 <?php }else{?>
							 <option value="<?php echo $j;?>" ><?php echo $j.'-'.($j+1);?></option>
							 <?php }
							 }?> 
						</select>
					</span>
					<legend class="pkheader">Income Tax Deduction Limit</legend>
					<div class="row well"> 
						<div class="table-responsive" ng-show="filteredItems > 0">
							<form id="income_tax" method="POST">
								<table class="table table-striped table-hover table-bordered"> 	
									<tbody>
										<tr>
											<td> <strong> For the Financial Year</strong></td>
											<td>
												<strong id="searchYear"> <?php echo (string)$fyear.'-'.(string)($fyear+1); ?> </strong>
											</td>
										</tr>
										<tr class="info">
											<td colspan="2" align="center"><strong>Exemption U/s 10</strong></td>
										</tr>
										<tr>
											<td> a) Rent Paid by Employee(Per Annum) (Actual)</td>
											<td>
												<input type="text" id="rent_paid_by_employee" name="rent_paid_by_employee" value="<?php if(count($empInfo)>0) echo $empInfo[0]['rent_oaid_by_employee']?>" class="form-control"/>
											</td>
										</tr>
										<tr>
											<td> b) Conveyance Allowance (1600 Per Month)</td>
											<td>
												 <input type="text" id="conv_allowance" value="<?php if(count($empInfo)>0) echo $empInfo[0]['conv_allowance']?>" name="conv_allowance"  class="form-control"  />
											</td>
										</tr>
										<tr>
											<td> c) Children Educational Allowance (@ 100 PM for max 2 child)</td>
											<td>
												<input type="text" id="childreneducationalallowance" name="childreneducationalallowance" value="<?php if(count($empInfo)>0) echo $empInfo[0]['childreneducationalallowance']?>" class="form-control"/>
											</td>
										</tr>
										<tr>
											<td> d) Medical Expenses (Max 15000 Per Annum)</td>
											<td>
												 <input type="text" id="medicalexpensesperannum" name="medicalexpensesperannum" value="<?php if(count($empInfo)>0) echo $empInfo[0]['medicalexpensesperannum']?>" class="form-control" />
											</td>
										</tr>
										<tr class="info">
											<td colspan="2" align="center"><strong>Deductions U/s 10(5) (Leave Travel Concession)</strong></td>
										</tr>
										<tr>
											<td>Twice in a block of 4 years</td>
											<td>
												 <input type="text" id="leavetravelconcession" name="leavetravelconcession" value="<?php if(count($empInfo)>0) echo $empInfo[0]['leavetravelconcession']?>" class="form-control"/>
											</td>
										</tr>
										<tr class="info">
											<td colspan="2" align="center"><strong>Deductions</strong></td>
										</tr>
										<tr>
											<td>a) Entertainment Allowance</td>
											<td>
												 <input type="text" id="entertainment_allowance" name="entertainment_allowance" value="<?php if(count($empInfo)>0) echo $empInfo[0]['entertainment_allowance']?>" class="form-control" />
											</td>
										</tr>
										<tr>
											<td>b) Professional Tax(As per PT Slab)</td>
											<td>
												<input type="text" id="professional_tax" name="professional_tax" value="<?php if(count($empInfo)>0) echo $empInfo[0]['professional_tax']?>" class="form-control" />
											</td>
										</tr>
										<tr class="info">
											<td colspan="2" align="center"><strong>Deductions U/s 24(Interest on Housing Loan)</strong></td>
										</tr>
										<tr>
											<td>a) Self Occupied Property (Max) 200000</td>
											<td>
												 <input type="text" id="self_occupied_property" name="self_occupied_property" value="<?php if(count($empInfo)>0) echo $empInfo[0]['self_occupied_property']?>" class="form-control" />
											</td>
										</tr>
										<tr>
											<td>b) Let-out/Rented Property - No Limit</td>
											<td>
												<input type="text" id="let_out_rented_property" name="let_out_rented_property" value="<?php if(count($empInfo)>0) echo $empInfo[0]['let_out_rented_property']?>" class="form-control"/>
											</td>
										</tr>
										<tr class="info">
											<td colspan="2" align="center"><strong>Deductions Under Chapter VI-A</strong></td>
										</tr>
										<tr>
											<td> <strong>Deductions U/s 80C (Max 1.5 Lakh Per Annum)</strong></td>
											<td>
												<input type="text" id="deduction80C" name="deduction80C" value="<?php if(count($empInfo)>0) echo $empInfo[0]['deduction80C']?>" class="form-control"/>
											</td>
										</tr>
										<tr>
											<td> <strong>Deductions U/s 80CCD Contribution to NPS Rs 50000</strong></td>
											<td>
												<input type="text" id="deduction80CCD_contribution_nps" name="deduction80CCD_contribution_nps" value="<?php if(count($empInfo)>0) echo $empInfo[0]['deduction80CCD_contribution_nps']?>" class="form-control" />
											</td>
										</tr>
										<tr>
											<td> <strong>Deductions U/s 80D (Max 25000 - Self/Family)</strong></td>
											<td>
												<input type="text" id="selfsfamily" name="selfsfamily" value="<?php if(count($empInfo)>0) echo $empInfo[0]['selfsfamily']?>" class="form-control" />
											</td>
										</tr>
										<tr>
											<td> <strong>Deductions U/s 80D (Max 25000 - Parents) </strong></td>
											<td>
												<input type="text" id="parents" name="parents" value="<?php if(count($empInfo)>0) echo $empInfo[0]['parents']?>" class="form-control" />
											</td>
										</tr>
										<tr>
											<td> <strong>Additional Deductions Rs. 5000 incase of senior citizen </strong></td>
											<td>
												 <input type="text" id="deductions_senior_citizen" name="deductions_senior_citizen" value="<?php if(count($empInfo)>0) echo $empInfo[0]['deductions_senior_citizen']?>" class="form-control"/>
											</td>
										</tr>
										<tr class="info">
											<td colspan="2" align="center"><strong>Deductions U/s 80DD (only Dependants)</strong></td>
										</tr>
										<tr>
											<td>a) Medical treatment of Depedent (Normal Disability - Max 50000):</td>
											<td>
												<input type="text" id="dependants_normal_disability" name="dependants_normal_disability" value="<?php  if(count($empInfo)>0) echo $empInfo[0]['dependants_normal_disability']?>" class="form-control"/>
											</td>
										</tr>
										<tr>
											<td>b) Medical treatment of Depedent (Severe Disability - Max 100000)</td>
											<td>
												<input type="text" id="dependants_severe_disability" name="dependants_severe_disability" value="<?php if(count($empInfo)>0) echo $empInfo[0]['dependants_severe_disability']?>" class="form-control"/>
											</td>
										</tr>
										<tr class="info">
											<td colspan="2" align="center"><strong>Deductions U/s 80DDB (Self/Dependant for specified diseases)</strong></td>
										</tr>
										<tr>
											<td> <strong>Medical treatment of normal case (Max 40000)</strong></td>
											<td>
												<input type="text" id="meducal_norman_case" name="meducal_norman_case" value="<?php if(count($empInfo)>0) echo $empInfo[0]['meducal_norman_case']?>" class="form-control" />
											</td>
										</tr>
										<tr>
											<td> <strong>Senior Citizen > 60 age (60000)</strong></td>
											<td>
												<input type="text" id="senior_citizen60" name="senior_citizen60" value="<?php if(count($empInfo)>0) echo $empInfo[0]['senior_citizen60']?>" class="form-control"  />
											</td>
										</tr>
										<tr>
											<td> <strong>Super senior citizen > 80 age(80000)</strong></td>
											<td>
												<input type="text" id="super_senior_citizen80" name="super_senior_citizen80" value="<?php if(count($empInfo)>0) echo $empInfo[0]['super_senior_citizen80']?>" class="form-control" />
											</td>
										</tr>
										<tr class="info">
											<td colspan="2" align="center"><strong>Deductions U/s 80E</strong></td>
										</tr>
										<tr>
											<td>a) Interest on Loan for higher education - U/s 80E (Actual)</td>
											<td>
												<input type="text"  name="interest_loan_higher_education_80E" id="interest_loan_higher_education_80E" value="<?php if(count($empInfo)>0) echo $empInfo[0]['interest_loan_higher_education_80E']?>" class="form-control" />
											</td>
										</tr>
										<tr class="info">
											<td colspan="2" align="center"><strong>Deductions U/s 80EE</strong></td>
										</tr>
										<tr>
											<td>a) Interest on Home Loan(First Time Buyer) Rs. 50000</td>
											<td>
												<input type="text" id="interest_home_loan_80E" name="interest_home_loan_80E" value="<?php if(count($empInfo)>0) echo $empInfo[0]['interest_home_loan_80E']?>" class="form-control"/>
											</td>
										</tr>
										<tr class="info">
											<td colspan="2" align="center"><strong>Deductions U/s 80G (Donations)</strong></td>
										</tr>
										<tr>
											<td> <strong>Actual Donation Made(50% or 100% depending on the donee)<br>(Subject to max 10% of Gross Total Income)</strong></td>
											<td>
												<input type="text" id="actual_donation" name="actual_donation" value="<?php if(count($empInfo)>0) echo $empInfo[0]['actual_donation']?>" class="form-control"/>
											</td>
										</tr> 
										<tr class="info">
											<td colspan="2" align="center"><strong>Deductions U/s 80TTA (Interest on Savings account)</strong></td>
										</tr>
										<tr>
											<td> <strong>Max. amount of Rs. 10000/-</strong></td>
											<td>
												<input type="text" id="max_amount" name="max_amount" value="<?php if(count($empInfo)>0) echo $empInfo[0]['max_amount']?>" class="form-control" />
											</td>
										</tr> 
										<tr class="info">
											<td colspan="2" align="center"><strong>Deductions U/s 80U (only for Self)</strong></td>
										</tr>
										<tr>
											<td>a) Medical treatment of Self (Normal Disability - Max 50000)</td>
											<td>
												<input type="text" id="self_normal_disability" name="self_normal_disability" value="<?php if(count($empInfo)>0) echo $empInfo[0]['self_normal_disability']?>" class="form-control"/>
											</td>
										</tr>
										<tr>
											<td>b) Medical treatment of Self (Severe Disability - Max 100000)</td>
											<td>
												 <input type="text" id="self_severe_disability" name="self_severe_disability" value="<?php if(count($empInfo)>0) echo $empInfo[0]['self_severe_disability']?>" class="form-control" />
											</td>
										</tr>  
										<tr class="info">
											<td colspan="2" align="center"><strong>Standard Deduction</strong></td>
										</tr>
										<tr>
											<td><strong>For the Financial Year <?php echo (string)$fyear.'-'.(string)($fyear+1); ?></strong></td>
											<td>
												 <input type="text" id="standard_deduction" name="standard_deduction" value="<?php if(count($empInfo)>0) echo $empInfo[0]['standard_deduction']?>" class="form-control" />
											</td>
										</tr>
									</tbody> 
								</table>
								<div class="col-md-9 successMsg" id="piMSG" style="text-align:center;"></div>
									<div class="col-md-3 add_btn">
										<input type="hidden" id="tid" name="tid" class="" value="<?php if(count($empInfo)>0) echo $empInfo[0]['tid']?>"/>
										<input type="submit" id="btn_add" name="btn_update" class="search_sbmt pull-right btn btn-primary" value="Submit"/>
									 </div>
										
							</form>	
						</div>
						<!-- /.table-responsive -->
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div> 
</div>
<script>
	var frm = $('#income_tax');
	var site_url = "<?= base_url(); ?>";
    frm.submit(function (e) {
		var searchYear = $('#searchYear1').val();
        e.preventDefault();
        $.ajax({
            type: frm.attr('method'),
            url: site_url+'en/accounts_admin/manage_income_tax/'+searchYear,
            data: frm.serialize(),
            success: function (data) {
                console.log(data);
				$('#piMSG').html('<h6>Professional Tax Slab submitted Successfully</h6>');
				setTimeout(function(){ location.reload(); }, 2000);	
				setTimeout(function(){ $('#piMSG').html(''); }, 3000);
            },
            error: function (data) {
                console.log('An error occurred.');
                console.log(data);
            },
        });
    });
	
	function getFYData(dis){
		var searchYear = $(dis).val();
		$('#searchYear').html(searchYear+'-'+ ( parseInt(searchYear)+1 ));
		$.ajax({
			type: 'POST',
			url:site_url+'en/accounts_admin/tax_deduction_limit_fy',
			data:{searchYear:searchYear},
			success:function(data){
				//var data=JSON.parse(data);
				if(data.length > 0){
					$('#tid').val(data[0].tid);
					$('#rent_paid_by_employee').val(data[0].rent_oaid_by_employee);
					$('#conv_allowance').val(data[0].conv_allowance);
					$('#childreneducationalallowance').val(data[0].childreneducationalallowance);
					$('#medicalexpensesperannum').val(data[0].medicalexpensesperannum);
					$('#leavetravelconcession').val(data[0].leavetravelconcession);
					$('#entertainment_allowance').val(data[0].entertainment_allowance);
					$('#self_occupied_property').val(data[0].self_occupied_property);
					$('#professional_tax').val(data[0].professional_tax);
					$('#let_out_rented_property').val(data[0].let_out_rented_property);
					$('#deduction80C').val(data[0].deduction80C);
					$('#deduction80CCD_contribution_nps').val(data[0].deduction80CCD_contribution_nps);
					$('#selfsfamily').val(data[0].selfsfamily);
					$('#parents').val(data[0].parents);
					$('#deductions_senior_citizen').val(data[0].deductions_senior_citizen);
					$('#dependants_normal_disability').val(data[0].dependants_normal_disability);
					$('#dependants_severe_disability').val(data[0].dependants_severe_disability);
					$('#meducal_norman_case').val(data[0].meducal_norman_case);
					$('#senior_citizen60').val(data[0].senior_citizen60);
					$('#super_senior_citizen80').val(data[0].super_senior_citizen80);
					$('#interest_loan_higher_education_80E').val(data[0].interest_loan_higher_education_80E);
					$('#interest_home_loan_80E').val(data[0].interest_home_loan_80E);
					$('#actual_donation').val(data[0].actual_donation);
					$('#max_amount').val(data[0].max_amount);
					$('#self_normal_disability').val(data[0].self_normal_disability);
					$('#self_severe_disability').val(data[0].self_severe_disability);
					$('#standard_deduction').val(data[0].standard_deduction);
				}
			}
		});
	}
</script>
