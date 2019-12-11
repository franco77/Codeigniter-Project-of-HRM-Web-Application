
<style>
    a.tooltip {outline:none; text-decoration: none;
    background: none repeat scroll 0 0 #06c;
    border-radius: 50%;
    box-shadow: 1px 1px 2px rgba(255, 255, 255, 0.6) inset, -1px -1px 2px rgba(0, 0, 0, 0.6) inset;
    color: #fff;
    cursor: pointer;
    display: inline-block;
    font-size: 13px;
    font-weight: bold;
    height: 15px;
    line-height: 15px;    
    text-align: left;
    vertical-align: middle;
    width: 15px;
    }
    a.tooltip strong {line-height:30px;} 
    a.tooltip:hover {text-decoration:none;font-weight: normal;} 
    a.tooltip span { z-index:10;display:none; padding:14px 20px; margin-top:-30px; margin-left:15px; width:300px; line-height:16px; }
    a.tooltip:hover span{ display:inline; position:absolute; color:#111; border:1px solid #DCA; background:#fffAF0;} 
    .callout {z-index:20;position:absolute;top:30px;border:0;left:-12px;} 
    /*CSS3 extras*/ 
    a.tooltip span { border-radius:4px; box-shadow: 5px 5px 8px #CCC; }
    #dataTable td{
        padding: 4px 7px !important;
    }
</style>
<div id="lightbox_form" style="width:900px;">
    <div class="form_bg">    	
		<!--<h3 align="center"> Incom Tax Declaration(<?php if($rowAppraisal[0]['type'] =='E'){ echo 'Estimation'; } else{ echo 'Final'; } ?>) </h3> -->      
		<h3 align="center"> <span style="color: #000; font-size: 18px;">FORM NO.12BB <small>(See rule 26C)</small> </span> <br/> <small style="color: #3e3939;">( Statement showing particulars of claims by an employee for deduction of tax under section 192 )</small> </h3>       
		<div class="form">
			<div class="form1">
				<table cellpadding="0" cellspacing="0" width="100%" class="form1 itax">
					<tr><td width='55%'><strong>Name:&nbsp;</strong><?php echo $rowAppraisal[0]['full_name']; ?></td><td><strong>Designation:&nbsp;</strong><?php echo $rowAppraisal[0]['desg_name']; ?></td></tr>
					<tr><td><strong>Department:&nbsp;</strong><?php echo $rowAppraisal[0]['dept_name']; ?></td><td><strong>Reporting manager's Name:&nbsp;</strong><?php echo $rowAppraisal[0]['reporting_manager_full_name']; ?></td></tr>
					<tr><td><strong>Employee ID:&nbsp;</strong><?php echo $rowAppraisal[0]['loginhandle']; ?></td><td><strong>Date of Joinning:&nbsp;</strong><?php echo date('d-m-Y',strtotime($rowAppraisal[0]['join_date'])); ?></td></tr>
					<!--<tr><td><strong>Evaluation Period - From:&nbsp;</strong> 1st April 2015</td><td><strong>To: &nbsp;</strong>31st Mar 2016</td></tr>-->
					<tr><td><strong>Evaluation Period - From:</strong> 1st April <?php echo $rowAppraisal[0]['fyear'];?></td><td><strong>To:</strong> 31st Mar <?php echo ($rowAppraisal[0]['fyear']+1);?></td></tr>
				</table>
				<table cellpadding="0" cellspacing="0" width="100%" class="form1 itax">
					<tr>
						<td valign="top"> <strong> For the Financial Year</strong></td>
							<td valign="top" colspan="2"><b><?php echo $rowAppraisal[0]['fyear'];?>-<?php echo ($rowAppraisal[0]['fyear']+1);?></b></td>
					</tr> 
					<tr>
						<td colspan="3" class="form_title"><strong>Exemption U/s 10</strong></td>
					</tr>
					<tr>
						<td valign="top">a) Rent Paid by Employee</td>
						<td valign="top"><?php echo $rowAppraisal[0]['eligible_rent_paid_by_employee']?></td> 
					</tr>
					<tr>
						<td valign="top">b) Conveyance Allowance (1600 PM)</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['eligible_conv_allowance']?>
						</td> 
					</tr>
					<tr>
						<td valign="top" >c) Children Educational Allowance (@ 100 PM for max 2 child)</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['eligible_childreneducationalallowance']?>
						</td> 
					</tr>
					<tr>
						<td valign="top">d) Medical Expenses (Max 15000 PA)</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['eligible_medicalexpensesperannum']?>
						</td> 
					</tr>
					<tr>
						<td colspan="3" class="form_title"><strong>Deductions U/s 10(5) (Leave Travel Concession)</strong></td>
					</tr>
					<tr>
						<td valign="top">Twice in a block of 4 years</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['leavetravelconcession']?>
						</td> 
					</tr>
					<tr>
						<td colspan="3" class="form_title"><strong>Deductions</strong></td>
					</tr>
					<tr>
						<td valign="top">a) Entertainment Allowance</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['entertainment_allowance']?>
						</td> 
					</tr>
					<tr>
						<td valign="top">b) Professional Tax</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['professional_tax']?>
						</td> 
					</tr>
					<tr>
						<td colspan="3" class="form_title"><strong>Deductions U/s 24(Interest on Housing Loan)</strong></td>
					</tr>
					<tr>
						<td valign="top" >a) Self Occupied Property (Max) 200000</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['self_occupied_property']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> c) Let-our Occupied/Rented Property - No Limit</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['let_our_rented_property']?>
						</td> 
					</tr>
					<tr>
						<td colspan="3" class="form_title"><strong>Deductions Under Chapter VI-A</strong></td>
					</tr>									
					<tr>
						<td colspan="3" class="form_title"><strong>Deductions U/s 80C (Max 1.5 Lakh PA)</strong></td>
					</tr>
					<tr>
						<td valign="top"> a) Contribution To Provident Fund</td>
						<td valign="top">
							<?php echo $get_pf; ?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> b) Life Insurance Premium</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['lic']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> c) Public Provident Fund (PPF)</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['public_provident_fund']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> d) National Savings Certificate (NSC)</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['nsc']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> e) Children Education Fee (Max 2 child)</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['childreneducationfee']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> f) Mutual Funds Or UTI</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['mutualfund_or_uti']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> g) Contribution to notified pension fund</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['contribution_notified_pension_fund']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> h) Unit Lined Insurance Plan(ULIP)</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['ulip']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> i) Deposit In Post Office Tax Saving Scheme</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['postofficetax']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> j) Equity Linked Savings Scheme (ELSS)</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['elss']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> k) Housing Loan Principal Amount Repayment</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['housingloanprincipal']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> l) Fixed Deposit with Scheduled Bank for a period of 5 years or more</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['fixeddeposit']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> l) Any Other</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['any_other_tax']?>
						</td> 
					</tr> 
					<tr>
						<td valign="top" class="form_title"> <strong>Deductions U/s 80CCD(1B) Contribution to NPS Rs 50000</strong></td>
						<td valign="top" class="form_title">
							<?php echo $rowAppraisal[0]['eligible_deduction_under_80CCD']?>
						</td> 
					</tr>
					<tr>
						<td valign="top" class="form_title"> <strong>Deductions U/s 80D (Max 25000 - Self/Family)</strong></td>
						<td valign="top" class="form_title">
							<?php echo $rowAppraisal[0]['eligible_deduction_under_80D_selffamily']?>
						</td> 
					</tr>
					<tr>
						<td valign="top" class="form_title"> <strong>Deductions U/s 80D (Max 25000 - Parents) </strong></td>
						<td valign="top" class="form_title">
							<?php echo $rowAppraisal[0]['eligible_deduction_under_80D_parents']?>
						</td> 
					</tr>
					<tr>
						<td valign="top" class="form_title"> <strong>Additional Deductions Rs. 5000 incase of senior citizen </strong></td>
						<td valign="top" class="form_title">
							<?php echo $rowAppraisal[0]['eligible_deduction_incase_senior_citizen']?>
						</td> 
					</tr>
					<tr>
						<td colspan="3" class="form_title"><strong>Deductions U/s 80DD (only Dependants)</td>
					</tr>
					<tr>
						<td valign="top"> a) Medical treatment of Depedent (Normal Disability - Max 50K)</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['normal_disability']?>
						</td> 
					</tr>
					<tr>
						<td valign="top"> b) Medical treatment of Depedent (Severe Disability - Max 100K)</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['severe_disability']?>
						</td> 
					</tr>
					<tr>
						<td colspan="3" class="form_title"><strong>Deductions U/s 80DDB (Self/Dependant for specified diseases)</strong></td>
					</tr>
					<tr>
						<td valign="top"> Medical treatment of normal case (Max 40K)</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['medical_treatment_normal_case']?>
						</td> 
					</tr>
					<tr>
						<td valign="top">Senior Citizen > 60 age (60000)</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['senior_citizen_60']?>
						</td> 
					</tr>
					<tr>
						<td valign="top">Super senior citizen > 80 age(80000)</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['super_senior_citizen_80']?>
						</td> 
					</tr>
					<tr>
						<td colspan="3" class="form_title"><strong>Deductions U/s 80E</strong></td>
					</tr>
					<tr>
						<td valign="top">a) Interest on Loan for higher education - U/s 80E</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['interest_loan_higher_education_80e']?>
						</td> 
					</tr>
					<tr>
						<td colspan="3" class="form_title"><strong>Deductions U/s 80EE</strong></td>
					</tr>
					<tr>
						<td valign="top">b) Interest on Home Loan(First Time Buyer) Rs. 50000</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['interest_home_loan_80ee']?>
						</td> 
					</tr>
					<tr>
						<td colspan="3" class="form_title"><strong>Deductions U/s 80G (Donations)</strong></td>
					</tr>
					<tr>
						<td valign="top"> <strong>Actual Donation Made(50% or 100% depending on the donee)<br>(Subject to max 10% of Gross Total Income)</strong></td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['actual_donation_80g']?>
						</td> 
					</tr>
					<tr>
						<td colspan="3" class="form_title"><strong>Deductions U/s 80TTA (Interest on Savings account)</strong></td>
					</tr>
					<tr>
						<td valign="top">Max. amount of Rs. 10000/-</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['eligible_actual_donation_80g']?>
						</td> 
					</tr> 
					<tr>
						<td colspan="3" class="form_title"><strong>Deductions U/s 80U (only for Self)</strong></td>
					</tr>
					<tr>
						<td valign="top">a) Medical treatment of Self (Normal Disability - Max 50K)</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['deduction_under_80U_noraml_disability']?>
						</td> 
					</tr>
					<tr>
						<td valign="top">b) Medical treatment of Self (Severe Disability - Max 100K)</td>
						<td valign="top">
							<?php echo $rowAppraisal[0]['deduction_under_80U_severe_disability']?>
						</td> 
					</tr>  
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr> 
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr> 
					<tr>
						<td colspan="2">I, <b><?php echo $rowAppraisal[0]['full_name']; ?></b> PAN <b><?php echo $rowAppraisal[0]['pan_card_no']; ?></b> do hereby certify that the information given above is complete and correct. <br/><br/>
						<span style="width:100%; height: 30px;"> Place : Bhubaneswar   <span style="height:30px; width:200px; position: relative; left:500px;"> <img src="<?php echo base_url(); ?>assets/upload/profile/<?php echo $rowAppraisal[0]['user_sign_name']; ?>" width="200" height="30"  />   </span> </span><br/>
						<span> Date : <?php echo date('d-m-Y', strtotime($rowAppraisal[0]['apply_date'])); ?>  <span style="margin-left:600px;"> Signature : </span> </span>
						</td>
					</tr>
				</table>  
			</div>
		</div>   
	</div>
    
    <div class="clear"></div>
</div>
<div style="height: 50px;">&nbsp;</div>
<p style="text-align: center; font-size: 10px; color: #000; cursor: pointer;"><img alt="Print" onclick="javascript:window.print();" src="<?php echo base_url(); ?>assets/images/printer_icon.png" /></p>
<div style="height: 50px;">&nbsp;</div>
<link rel="stylesheet" media="all" type="text/css" href="<?php echo base_url();?>assets/dist/frontend/main.css" />
                                              
                             