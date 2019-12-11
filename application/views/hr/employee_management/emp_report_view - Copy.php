<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<h4 class="section-title">
				<span class="c-dark">Hr</span>
			</h4>
		</div>
	</div>
</div>
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<div class="well">
						<h4 class="box-title">Employee Report</h4> 
					</div>
					<div class="well">
						<div class="row"> 
							<form method="POST" id="frmReport" name="frmReport" action="<?php base_url('en/hr/emp_report_export');?>">
								<div class="pad_10">
									<div class="search_box">
										<div class="search_box_top">
											<div class="search_box_btm">
												<div class="pad_10">
													<h4>Employee Report &nbsp;&nbsp;&nbsp;<input type="checkbox" id="chooseall" name="chooseall" value="" /><label for="chooseall" style="font-size: 11px;">&nbsp;&nbsp;Choose All</label></h4>
													<table width="100%" cellspacing="0" cellpadding="6" border="0">
														<tr class="search_odd">
															<td><label for="adharcard_no">Aadhar Card No.</label></td>
															<td><input type="checkbox" class="chkColumns" id="adharcard_no" name="adharcard_no" /></td>
															<td><label for="user_status">Active/Inactive</label></td>
															<td><input type="checkbox" class="chkColumns" id="user_status" name="user_status" /></td>
															<td><label for="age">Age</label></td>
															<td><input type="checkbox" class="chkColumns" id="age" name="age" /></td>
															<td><label for="ff_amount">Amount Of F&F</label></td>
															<td><input type="checkbox" class="chkColumns" id="ff_amount" name="ff_amount" /></td>
															<td><label for="anniversary_date">Anniversary Date</label></td>
															<td><input type="checkbox" class="chkColumns" id="anniversary_date" name="anniversary_date" /></td>
														</tr>
														<tr>
															<td><label for="appoint_letter_issued" title="Appointment Letter Issued Status (Y/N)">Appoint. Letter</label></td>
															<td><input type="checkbox" class="chkColumns" id="appoint_letter_issued" name="appoint_letter_issued" /></td>
															<td><label for="bank_no">Bank Account No.</label></td>
															<td><input type="checkbox" class="chkColumns" id="bank_no" name="bank_no" /></td>
															<td><label for="bank">Bank Name</label></td>
															<td><input type="checkbox" class="chkColumns" id="bank" name="bank" /></td>
															<td><label for="basic">Basic</label></td>
															<td><input type="checkbox" class="chkColumns" id="basic" name="basic" /></td>
															<td><label for="blood_group">Blood Group</label></td>
															<td><input type="checkbox" class="chkColumns" id="blood_group" name="blood_group" /></td>
														</tr>
														<tr class="search_odd">
															<td><label for="grad_board" title="Board/University(Graduation)">Board/University(G)</label></td>
															<td><input type="checkbox" class="chkColumns" id="grad_board" name="grad_board" /></td>
															<td><label for="prof_board" title="Board/University(Professional)">Board/University(P)</label></td>
															<td><input type="checkbox" class="chkColumns" id="prof_board" name="prof_board" /></td>
															<td><label for="edu_catG" title="Category of Education(Graduation)">Cat of Edu.(G)</label></td>
															<td><input type="checkbox" class="chkColumns" id="edu_catG" name="edu_catG" /></td>
															<td><label for="edu_catP" title="Category of Education(Professional)">Cat of Edu.(P)</label></td>
															<td><input type="checkbox" class="chkColumns" id="edu_catP" name="edu_catP" /></td>
															<td><label for="child1">Child1</label></td>
															<td><input type="checkbox" class="chkColumns" id="child1" name="child1" /></td>
														</tr>
														<tr>
															<td><label for="child_dob1">Child1 DOB</label></td>
															<td><input type="checkbox" class="chkColumns" id="child_dob1" name="child_dob1" /></td>
															<td><label for="child2">Child2</label></td>
															<td><input type="checkbox" class="chkColumns" id="child2" name="child2" /></td>
															<td><label for="child_dob2">Child2 DOB</label></td>
															<td><input type="checkbox" class="chkColumns" id="child_dob2" name="child_dob2" /></td>
															<td><label for="child3">Child3</label></td>
															<td><input type="checkbox" class="chkColumns" id="child3" name="child3" /></td>
															<td><label for="child_dob3">Child3 DOB</label></td>
															<td><input type="checkbox" class="chkColumns" id="child_dob3" name="child_dob3" /></td>
														</tr>
														<tr class="search_odd">
															<td><label for="confirm_status" title="Confirmation Status">Conf. Status</label></td>
															<td><input type="checkbox" class="chkColumns" id="confirm_status" name="confirm_status" /></td>
															<td><label for="conf_letter_issued" title="Confirmation Letter Issued Status (Y/N)">Confirmation Letter</label></td>
															<td><input type="checkbox" class="chkColumns" id="conf_letter_issued" name="conf_letter_issued" /></td>
															<td><label for="conv">Conv.</label></td>
															<td><input type="checkbox" class="chkColumns" id="conv" name="conv" /></td>
															<td><label for="corr_add" title="Correspondence Address">Corres. Addr.</label></td>
															<td><input type="checkbox" class="chkColumns" id="corr_add" name="corr_add" /></td>
															<td><label for="dop">Date of Promotion</label></td>
															<td><input type="checkbox" class="chkColumns" id="dop" name="dop" /></td>
														</tr>
														<tr>
															<td><label for="dept_name">Department</label></td>
															<td><input type="checkbox" class="chkColumns" id="dept_name" name="dept_name" /></td>
															<td><label for="desg_name">Designation</label></td>
															<td><input type="checkbox" class="chkColumns" id="desg_name" name="desg_name" /></td>
															<td><label for="drl_no" title="Driving License No.">DL No.</label></td>
															<td><input type="checkbox" class="chkColumns" id="drl_no" name="drl_no" /></td>
															<td><label for="doc" title="Date of Confirmation">DOC</label></td>
															<td><input type="checkbox" class="chkColumns" id="doc" name="doc" /></td>
															<td><label for="doj" title="Date of Joining">DOJ</label></td>
															<td><input type="checkbox" class="chkColumns" id="doj" name="doj" /></td>
														</tr>
														<tr class="search_odd">
															<td><label for="dob">Emp D.O.B</label></td>
															<td><input type="checkbox" class="chkColumns" id="dob" name="dob" /></td>
															<td><label for="loginhandle" title="Employee Code">Emp. Code</label></td>
															<td><input type="checkbox" class="chkColumns" id="loginhandle" name="loginhandle" value="" /></td>
															<td><label for="emp_type" title="Employee Type">Employee Type</label></td>
															<td><input type="checkbox" class="chkColumns" id="emp_type" name="emp_type" /></td>
															<td><label for="exp_aabsys" title="Experience in Polosoft">Exp. In AABSyS</label></td>
															<td><input type="checkbox" class="chkColumns" id="exp_aabsys" name="exp_aabsys" /></td>
															<td><label for="exp_others" title="Experience Prior to Polosoft">Exp. Out AABSyS</label></td>
															<td><input type="checkbox" class="chkColumns" id="exp_others" name="exp_others" /></td>
														</tr>
														<tr>
															<td><label for="ff_handed_date" title="F&F Amount Handed Over Date">F&F Amt HO Date</label></td>
															<td><input type="checkbox" class="chkColumns" id="ff_handed_date" name="ff_handed_date" /></td>
															<td><label for="fathers_name">Fathers Name</label></td>
															<td><input type="checkbox" class="chkColumns" id="fathers_name" name="father_name" /></td>
															<td><label for="FnF_status">FnF Status</label></td>
															<td><input type="checkbox" class="chkColumns" id="FnF_status" name="FnF_status" /></td>
															<td><label for="ff_date">Full & Final Date</label></td>
															<td><input type="checkbox" class="chkColumns" id="ff_date" name="ff_date" /></td>
															<td><label for="gender">Gender</label></td>
															<td><input type="checkbox" class="chkColumns" id="gender" name="gender" /></td>
														</tr>
														<tr class="search_odd">
															<td><label for="grade">Grade</label></td>
															<td><input type="checkbox" class="chkColumns" id="grade" name="grade" /></td>
															<td><label for="graduation">Graduation</label></td>
															<td><input type="checkbox" class="chkColumns" id="graduation" name="graduation" /></td>
															<td><label for="grad_percentage">Graduation %age</label></td>
															<td><input type="checkbox" class="chkColumns" id="grad_percentage" name="grad_percentage" /></td>
															<td><label for="gross_salary">Gross Salary</label></td>
															<td><input type="checkbox" class="chkColumns" id="gross_salary" name="gross_salary" /></td>
															<td><label for="highest_qual" title="Highest Qualification">Highest Qual.</label></td>
															<td><input type="checkbox" class="chkColumns" id="highest_qual" name="highest_qual" /></td>
														</tr>
														<tr>
															<td><label for="hod">HOD Name</label></td>
															<td><input type="checkbox" class="chkColumns" id="hod" name="hod" /></td>
															<td><label for="hra">HRA</label></td>
															<td><input type="checkbox" class="chkColumns" id="hra" name="hra" /></td>
															<td><label for="phone2" title="Contact No. Correspondence Address (Landline)">Landline C Addr.</label></td>
															<td><input type="checkbox" class="chkColumns" id="phone2" name="phone2" /></td>
															<td><label for="phone1" title="Contact No. Permanent Address (Landline)">Landline P Addr.</label></td>
															<td><input type="checkbox" class="chkColumns" id="phone1" name="phone1" /></td>
															<td><label for="increment">Last Increment</label></td>
															<td><input type="checkbox" class="chkColumns" id="increment" name="increment" /></td>
														</tr>
														<tr class="search_odd">
															<td><label for="level">Level</label></td>
															<td><input type="checkbox" class="chkColumns" id="level" name="level" /></td>
															<td><label for="loc_highest_qualActual" title="Location of Highest Qualification">Loc. of H Qual.</label></td>
															<td><input type="checkbox" class="chkColumns" id="loc_highest_qualActual" name="loc_highest_qualActual" /></td>
															<td><label for="loc">Location</label></td>
															<td><input type="checkbox" class="chkColumns" id="loc" name="loc" /></td>
															<td><label for="LWD" title="Last Working Date">LWD</label></td>
															<td><input type="checkbox" class="chkColumns" id="LWD" name="LWD" /></td>
															<td><label for="marital_status">Marital Status</label></td>
															<td><input type="checkbox" class="chkColumns" id="marital_status" name="marital_status" /></td>
														</tr>
														<tr>
															<td><label for="mediclaim_no" title="Mediclaim/ESI No.">Mediclaim/ESI No.</label></td>
															<td><input type="checkbox" class="chkColumns" id="mediclaim_no" name="mediclaim_no" /></td>
															<td><label for="miscunduct_issue" title="Misconduct/Integrity Issues Details">Misconduct Det.</label></td>
															<td><input type="checkbox" class="chkColumns" id="miscunduct_issue" name="miscunduct_issue" /></td>
															<td><label for="mobile" title="Contact No. Correspondence Address (Mobile)">Mobile C Addr.</label></td>
															<td><input type="checkbox" class="chkColumns" id="mobile" name="mobile" /></td>
															<td><label for="mobile1" title="Contact No. Permanent  Address (Mobile)">Mobile  P Addr.</label></td>
															<td><input type="checkbox" class="chkColumns" id="mobile1" name="mobile1" /></td>
															<td><label for="mother_name">Mothers Name</label></td>
															<td><input type="checkbox" class="chkColumns" id="mother_name" name="mother_name" /></td>
														</tr>
														<tr class="search_odd">
															<td><label for="full_name">Name</label></td>
															<td><input type="checkbox" class="chkColumns" class="chk" id="full_name" name="full_name" /></td>
															<td><label for="State">Native State</label></td>
															<td><input type="checkbox" class="chkColumns" id="State" name="State" /></td>
															<td><label for="official_mobile" title="Official Mobile No. (If Provided)">Off. Mobile No.</label></td>
															<td><input type="checkbox" class="chkColumns" id="official_mobile" name="official_mobile" /></td>
															<td><label for="offer_letter_issued" title="Offer Letter Issued Status (Y/N)">Offer Letter</label></td>
															<td><input type="checkbox" class="chkColumns" id="offer_letter_issued" name="offer_letter_issued" /></td>
															<td><label for="email">Official Email Id</label></td>
															<td><input type="checkbox" class="chkColumns" id="email" name="email" /></td>
														</tr>
														<tr>
															<td><label for="pan_card_no">PAN Card No.</label></td>
															<td><input type="checkbox" class="chkColumns" id="pan_card_no" name="pan_card_no" /></td>
															<td><label for="passport_no">Passport No.</label></td>
															<td><input type="checkbox" class="chkColumns" id="passport_no" name="passport_no" /></td>
															<td><label for="per_add" title="Permanent Address">Permanent Addr.</label></td>
															<td><input type="checkbox" class="chkColumns" id="per_add" name="per_add" /></td>
															<td><label for="per_email" title="Personal EMail ID">Pers. EMail ID</label></td>
															<td><input type="checkbox" class="chkColumns" id="per_email" name="per_email" /></td>
															<td><label for="pf_no">PF No.</label></td>
															<td><input type="checkbox" class="chkColumns" id="pf_no" name="pf_no" /></td>
														</tr>
														<tr class="search_odd">
															<td><label for="prev_comp1" title="Previous Company 1">Prev. Company 1</label></td>
															<td><input type="checkbox" class="chkColumns" id="prev_comp1" name"prev_comp1" /></td>
															<td><label for="prev_comp2" title="Previous Company 2">Prev. Company 2</label></td>
															<td><input type="checkbox" class="chkColumns" id="prev_comp2" name="prev_comp2" /></td>
															<td><label for="prev_deg1" title="Previous Designation 1">Prev. Desg. 1</label></td>
															<td><input type="checkbox" class="chkColumns" id="prev_deg1" name="prev_deg1" /></td>
															<td><label for="prev_deg2" title="Previous Designation 2">Prev. Desg. 2</label></td>
															<td><input type="checkbox" class="chkColumns" id="prev_deg2" name="prev_deg2" /></td>
															<td><label for="professional" title="Professional Qualification">Prof. Qual.</label></td>
															<td><input type="checkbox" class="chkColumns" id="professional" name="professional" /></td>
														</tr>
														<tr>
															<td><label for="prof_percentage">Professional %age</label></td>
															<td><input type="checkbox" class="chkColumns" id="prof_percentage" name="prof_percentage" /></td>
															<td><label for="resignReson" title="Reason of Separation">Reason of Sep.</label></td>
															<td><input type="checkbox" class="chkColumns" id="resignReson" name="resignReson" /></td>
															<td><label for="hrRemark">Remarks</label></td>
															<td><input type="checkbox" class="chkColumns" id="hrRemark" name="hrRemark" /></td>
															<td><label for="reporting">Reporting Officer</label></td>
															<td><input type="checkbox" class="chkColumns" id="reporting" name="reporting" /></td>
															<td><label for="DOR">Resignation Date</label></td>
															<td><input type="checkbox" class="chkColumns" id="DOR" name="DOR" /></td>
														</tr>
														<tr class="search_odd">
															<td><label for="rev_officer" title="Reviewing Officer">Rev. Officer</label></td>
															<td><input type="checkbox" class="chkColumns" id="rev_officer" name="rev_officer" /></td>
															<td><label for="skype">Skype ID</label></td>
															<td><input type="checkbox" class="chkColumns" id="skype" name="skype" /></td>
															<td><label for="source_hire">Source of Hire</label></td>
															<td><input type="checkbox" class="chkColumns" id="source_hire" name="source_hire" /></td>
															<td><label for="spouse_dob">Spouse DOB</label></td>
															<td><input type="checkbox" class="chkColumns" id="spouse_dob" name="spouse_dob" /></td>
															<td><label for="spouse_name">Spouse Name</label></td>
															<td><input type="checkbox" class="chkColumns" id="spouse_name" name="spouse_name" /></td>
														</tr>
														<tr>
															<td><label for="graduation"  title="Specialization (Graduation)">Specialization(G)</label></td>
															<td><input type="checkbox" class="chkColumns" id="specializationGrad" name="specializationGrad" /></td>
															<td><label for="professional" title="Specialization (Professional)">Specialization(P)</label></td>
															<td><input type="checkbox" class="chkColumns" id="specializationProf" name="specializationProf" /></td>
															<td><label for="exp_total" title="Total Experience">Total Exp.</label></td>
															<td><input type="checkbox" class="chkColumns" id="exp_total" name="exp_total" /></td>
															<td><label for="voter_id">Voter ID Card No.</label></td>
															<td><input type="checkbox" class="chkColumns" id="voter_id" name="voter_id" /></td>
															<td><label for="grad_passing_year" title="Year Of Passing(Graduation)">Year Of Passing(G)</label></td>
															<td><input type="checkbox" class="chkColumns" id="grad_passing_year" name="grad_passing_year" /></td>
														</tr>
														<tr class="search_odd">
															<td><label for="prof_passing_year" title="Year Of Passing(Professional)">Year Of Passing(P)</label></td>
															<td><input type="checkbox" class="chkColumns" id="prof_passing_year" name="prof_passing_year" /></td>
															<td><label for="no_exp1" title="No. of Years of Experience 1">Years of Exp. 1</label></td>
															<td><input type="checkbox" class="chkColumns" id="no_exp1" name="no_exp1" /></td>
															<td><label for="no_exp2" title="No. of Years of Experience 2">Years of Exp. 2</label></td>
															<td><input type="checkbox" class="chkColumns" id="no_exp2" name="no_exp2" /></td>
															<td><label for="emp_status_type" title="Employee Status Type">Emp Status Type</label></td>
															<td><input type="checkbox" class="chkColumns" id="emp_status_type" name="emp_status_type" /></td>
															<td><label for="actual_skill" title="Actual Skill">Actual Skill</label></td>
															<td><input type="checkbox" class="chkColumns" id="actual_skill" name="actual_skill" /></td>
														</tr>
														<tr>
															<td><label for="required_skill" title="Required Skill">Required Skill</label></td>
															<td><input type="checkbox" class="chkColumns" id="required_skill" name="required_skill" /></td>
															<td><label for="actual_exp" title="Actual Exp.">Actual Exp.</label></td>
															<td><input type="checkbox" class="chkColumns" id="actual_exp" name="actual_exp" /></td>
															<td><label for="required_exp" title="Required Exp.">Required Exp.</label></td>
															<td><input type="checkbox" class="chkColumns" id="required_exp" name="required_exp" /></td>
															<td><label for="actual_edu" title="Actual Edu.">Actual Edu.</label></td>
															<td><input type="checkbox" class="chkColumns" id="actual_edu" name="actual_edu" /></td>
															<td><label for="required_edu" title="Required Edu.">Required Edu.</label></td>
															<td><input type="checkbox" class="chkColumns" id="required_edu" name="required_edu" /></td>
															<td colspan="3">&nbsp;</td>
														</tr>
														<tr>
															<td colspan="10">&nbsp;</td>
														</tr>
													</table>
													<h4>Filter Data From Selected Columns</h4>
													<div class="multiSelectSearchHolder">
														<div id="dojBox" class="filterItem">
															Date of Joining
															<div>
																<input type="text" id="dojFrom" name="dojFrom" value="" readonly placeholder="From" class="cal search_UI" />
																<input type="text" id="dojTo" name="dojTo" value="" readonly placeholder="To" class="cal search_UI" />
															</div>
														</div>
														<div id="ageBox" class="filterItem">
															Age (in Months)
															<div>
																<input type="text" id="ageFrom" name="ageFrom" value="" placeholder="From" class="search_UI" />
																<input type="text" id="ageTo" name="ageTo" value="" placeholder="To" class="search_UI" />
															</div>
														</div>
														<div id="docBox" class="filterItem">
															Date of Confirmation
															<div>
																<input type="text" id="docFrom" name="docFrom" value="" readonly placeholder="From" class="cal search_UI" />
																<input type="text" id="docTo" name="docTo" value="" readonly placeholder="To" class="cal search_UI" />
															</div>
														</div>
														<div id="levelBox" class="filterItem">
															Level
															<div>
																<input type="hidden" name="hdnLevel" id="hdnLevel" value="" />
																<select id="selLevel" name="selLevel" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$level_result = count($level);
																	for($i=0; $i<$level_result; $i++)
																	{?>
																		<option><?php echo $level[$i]['level_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="gradeBox" class="filterItem">
															Grade
															<div>
																<input type="hidden" name="hdnGrade" id="hdnGrade" value="" />
																<select id="selGrade" name="selGrade" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$grade_result = count($grade);
																	for($i=0; $i<$grade_result; $i++)
																	{?>
																		<option><?php echo $grade[$i]['grade_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="dept_nameBox" class="filterItem">
															Department                                    
															<div>
																<input type="hidden" name="hdnDept" id="hdnDept" value="" />
																<select id="selDepartment" name="selDepartment" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$department_result = count($department);
																	for($i=0; $i<$department_result; $i++)
																	{?>
																		<option><?php echo $department[$i]['dept_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="desg_nameBox" class="filterItem">
															Designation
															<div>                                         
																<select id="selDesignation" name="selDesignation" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$designation_result = count($designation);
																	for($i=0; $i<$designation_result; $i++)
																	{?>
																		<option><?php echo $designation[$i]['desg_name'];?></option>
																	<?php }
																?>
																</select>                                        
															</div>
														</div>
														<input type="hidden" name="hdnDesg" id="hdnDesg" value="" />
														<div id="loc_highest_qualActualBox" class="filterItem">
															Location of Highest Qualification
															<div>
																<input type="hidden" name="hdnLocHQ" id="hdnLocHQ" value="" />
																<select id="selLocationHQ" name="selLocationHQ" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$qualification_result = count($location_of_highest_qualification);
																	for($i=0; $i<$qualification_result; $i++)
																	{?>
																		<option><?php echo $location_of_highest_qualification[$i]['state_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="locBox" class="filterItem">
															Location
															<div>
																<input type="hidden" name="hdnLoc" id="hdnLoc" value="" />
																<select id="selLocation" name="selLocation" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$location_result = count($location);
																	for($i=0; $i<$location_result; $i++)
																	{?>
																		<option><?php echo $location[$i]['branch_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="reportingBox" class="filterItem">
															Reporting Officer
															<div>
																<input type="hidden" name="hdnReporting" id="hdnReporting" value="" />
																<select id="selReporting" name="selReporting" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$reporting_result = count($reporting);
																	for($i=0; $i<$reporting_result; $i++)
																	{?>
																		<option><?php echo $reporting[$i]['full_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="rev_officerBox" class="filterItem">
															Reviewing Officer
															<div>
																<input type="hidden" name="hdnReviewing" id="hdnReviewing" value="" />
																<select id="selReviewing" name="selReviewing" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$reviewing_result = count($reviewing);
																	for($i=0; $i<$reviewing_result; $i++)
																	{?>
																		<option><?php echo $reviewing[$i]['full_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="hodBox" class="filterItem">
															Head of Department
															<div>
																<input type="hidden" name="hdnHOD" id="hdnHOD" value="" />
																<select id="selHOD" name="selHOD" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$hod_result = count($hod);
																	for($i=0; $i<$hod_result; $i++)
																	{?>
																		<option><?php echo $hod[$i]['full_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="StateBox" class="filterItem">
															Native State
															<div>
																<input type="hidden" name="hdnState" id="hdnState" value="" />
																<select id="selState" name="selState" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$state_result = count($state);
																	for($i=0; $i<$state_result; $i++)
																	{?>
																		<option><?php echo $state[$i]['state_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="genderBox" class="filterItem">
															Gender
															<div>
																<select id="selGender" name="selGender"  class="search_UI" style="width:200px;">
																	<option value="">All</option>
																	<option value="M">Male</option>
																	<option value="F">Female</option>
																</select>
															</div>
														</div>
														<div id="marital_statusBox" class="filterItem">
															Marital Status
															<div>
																<select id="selMarital_status" name="selMarital_status"  class="search_UI" style="width:200px;">
																	<option value="">All</option>
																	<option value="S">Single</option>
																	<option value="M">Married</option>
																</select>
															</div>
														</div>
														<div id="exp_aabsysBox" class="filterItem">
															Experience in Polosoft
															<div>
																<input type="text" id="expAABSySFrom" name="expAABSySFrom" value="" placeholder="From" class="search_UI" />
																<input type="text" id="expAABSySTo" name="expAABSySTo" value="" placeholder="To" class="search_UI" />
															</div>
														</div>
														<div id="exp_othersBox" class="filterItem">
															Experience Prior to Polosoft
															<div>
																<input type="text" id="expOFrom" name="expOFrom" value="" placeholder="From" class="search_UI" />
																<input type="text" id="expOTo" name="expOTo" value="" placeholder="To" class="search_UI" />
															</div>
														</div>
														<div id="exp_totalBox" class="filterItem">
															Total Experience
															<div>
																<input type="text" id="expFrom" name="expFrom" value="" placeholder="From" class="search_UI" />
																<input type="text" id="expTo" name="expTo" value="" placeholder="To" class="search_UI" />
															</div>
														</div>
														<div id="basicBox" class="filterItem">
															Basic
															<div>
																<input type="text" id="basicFrom" name="basicFrom" value="" placeholder="From" class="search_UI" />
																<input type="text" id="basicTo" name="basicTo" value="" placeholder="To" class="search_UI" />
															</div>
														</div>
														<div id="gross_salaryBox" class="filterItem">
															Gross Salary
															<div>
																<input type="text" id="gSalFrom" name="gSalFrom" value="" placeholder="From" class="search_UI" />
																<input type="text" id="gSalTo" name="gSalTo" value="" placeholder="To" class="search_UI" />
															</div>
														</div>
														<div id="highest_qualBox" class="filterItem">
															Highest Qualification
															<div>
																<input type="hidden" name="hdnHQ" id="hdnHQ" value="" />
																<select id="selHQ" name="selHQ" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$qualif = count($qualification);
																	for($i=0; $i<$qualif; $i++)
																	{?>
																		<option><?php echo $qualification[$i]['course_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="bankBox" class="filterItem">
															Bank
															<div>
																<input type="hidden" name="hdnBank" id="hdnBank" value="" />
																<select id="selBank" name="selBank" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$bank_result = count($bank);
																	for($i=0; $i<$bank_result; $i++)
																	{?>
																		<option><?php echo $bank[$i]['bank_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="graduationBox" class="filterItem">
															Graduation
															<div>
																<input type="hidden" name="hdnGraduation" id="hdnGraduation" value="" />
																<select id="selGraduation" name="selGraduation" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$graduation_result = count($graduation);
																	for($i=0; $i<$graduation_result; $i++)
																	{?>
																		<option><?php echo $graduation[$i]['course_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="specializationGradBox" class="filterItem">
															Specialization (Graduation)
															<div>                                         
																<select id="selSpecializationGrad" name="selSpecializationGrad" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$specialization_grade_result = count($specialization_grade);
																	for($i=0; $i<$specialization_grade_result; $i++)
																	{?>
																		<option><?php echo $specialization_grade[$i]['specialization_name'];?></option>
																	<?php }
																?>
																</select>                                        
															</div>
														</div>
														<input type="hidden" name="hdnSpecializationGrad" id="hdnSpecializationGrad" value="" />
														<div id="grad_passing_yearBox" class="filterItem">
															Year of Passing (Graduation)
															<div>
																<input type="text" id="gYOPFrom" name="gYOPFrom" value="" placeholder="From" class="search_UI" />
																<input type="text" id="gYOPTo" name="gYOPTo" value="" placeholder="To" class="search_UI" />
															</div>
														</div>
														<div id="grad_boardBox" class="filterItem">
															Board/University (Graduation)
															<div>
																<input type="hidden" name="hdngBorU" id="hdngBorU" value="" />
																<select id="selgBorU" name="selgBorU" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$board_university_result = count($board_university);
																	for($i=0; $i<$board_university_result; $i++)
																	{?>
																		<option><?php echo $board_university[$i]['board_university_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="grad_percentageBox" class="filterItem">
															Graduation Percentage
															<div>
																<input type="text" id="grad_perFrom" name="grad_perFrom" value="" placeholder="From" class="search_UI" />
																<input type="text" id="grad_perTo" name="grad_perTo" value="" placeholder="To" class="search_UI" />
															</div>
														</div>
														<div id="edu_catGBox" class="filterItem">
															Cat. of Education (Graduation)
															<div>
																<select id="selEduCategoryG" name="selEduCategoryG"  class="search_UI" style="width:200px;">
																	<option value="">All</option>
																	<option value="Technical" <?php if($this->input->post('selEduCategoryG') == 'Technical') echo 'selected="selected"';?>>Technical</option>
																	<option value="Non Technical" <?php if($this->input->post('selEduCategoryG') == 'Non Technical') echo 'selected="selected"';?>>Non Technical</option>
																</select>
															</div>
														</div>
														<div id="professionalBox" class="filterItem">
															Professional
															<div>
																<input type="hidden" name="hdnProfessional" id="hdnProfessional" value="" />
																<select id="selProfessional" name="selProfessional" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$professional_qualification_result = count($professional_qualification);
																	for($i=0; $i<$professional_qualification_result; $i++)
																	{?>
																		<option><?php echo $professional_qualification[$i]['course_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="specializationProfBox" class="filterItem">
															Specialization (Professional)
															<div>                                         
																<select id="selSpecializationProf" name="selSpecializationProf" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$specialization_professional_result = count($specialization_professional);
																	for($i=0; $i<$specialization_professional_result; $i++)
																	{?>
																		<option><?php echo $specialization_professional[$i]['specialization_name'];?></option>
																	<?php }
																?>
																</select>                                        
															</div>
														</div>
														<input type="hidden" name="hdnSpecializationProf" id="hdnSpecializationProf" value="" />
														<div id="prof_passing_yearBox" class="filterItem">
															Year of Passing (Professional)
															<div>
																<input type="text" id="pYOPFrom" name="pYOPFrom" value="" placeholder="From" class="search_UI" />
																<input type="text" id="pYOPTo" name="pYOPTo" value="" placeholder="To" class="search_UI" />
															</div>
														</div>
														<div id="prof_boardBox" class="filterItem">
															Board/University (Professional)
															<div>
																<input type="hidden" name="hdnpBorU" id="hdnpBorU" value="" />
																<select id="selpBorU" name="selpBorU" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$board_university_result = count($board_university);
																	for($i=0; $i<$board_university_result; $i++)
																	{?>
																		<option><?php echo $board_university[$i]['board_university_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="prof_percentageBox" class="filterItem">
															Professional Percentage
															<div>
																<input type="text" id="prof_perFrom" name="prof_perFrom" value="" placeholder="From" class="search_UI" />
																<input type="text" id="prof_perTo" name="prof_perTo" value="" placeholder="To" class="search_UI" />
															</div>
														</div>
														<div id="edu_catPBox" class="filterItem">
															Cat. of Education (Professional)
															<div>
																<select id="selEduCategoryP" name="selEduCategoryP"  class="search_UI" style="width:200px;">
																	<option value="">All</option>
																	<option value="Technical" <?php if($this->input->post('selEduCategoryP') == 'Technical') echo 'selected="selected"';?>>Technical</option>
																	<option value="Non Technical" <?php if($this->input->post('selEduCategoryP') == 'Non Technical') echo 'selected="selected"';?>>Non Technical</option>
																</select>
															</div>
														</div>
														<div id="dopBox" class="filterItem">
															Date of Promotion
															<div>
																<input type="text" id="dopFrom" name="dopFrom" value="" readonly placeholder="From" class="cal search_UI" />
																<input type="text" id="dopTo" name="dopTo" value="" readonly placeholder="To" class="cal search_UI" />
															</div>
														</div>
														<div id="dobBox" class="filterItem">
															Date of Birth
															<div>
																<input type="text" id="dobFrom" name="dobFrom" value="" readonly placeholder="From" class="cal search_UI" />
																<input type="text" id="dobTo" name="dobTo" value="" readonly placeholder="To" class="cal search_UI" />
															</div>
														</div>
														<div id="anniversary_dateBox" class="filterItem">
															Date of Anniversary
															<div>
																<input type="hidden" name="hdnAnniversaryDate" id="hdnAnniversaryDate" value="" />
																<select id="selAnniversaryDate" name="selAnniversaryDate"  multiple="multiple" class="search_UI" style="width:200px;">
																	<option value="01">January</option>
																	<option value="02">February</option>
																	<option value="03">March</option>
																	<option value="04">April</option>
																	<option value="05">May</option>
																	<option value="06">June</option>
																	<option value="07">July</option>
																	<option value="08">August</option>
																	<option value="09">September</option>
																	<option value="10">October</option>
																	<option value="11">November</option>
																	<option value="12">December</option>
																</select>
															</div>
														</div>
														<div id="blood_groupBox" class="filterItem">
															Blood Group
															<div>
																<input type="hidden" name="hdnBGroup" id="hdnBGroup" value="" />
																<select id="selBGroup" name="selBGroup"  multiple="multiple" class="search_UI" style="width:200px;">
																	<option value="A+">A+</option>
																	<option value="A-">A-</option>
																	<option value="B+">B+</option>
																	<option value="B-">B-</option>
																	<option value="AB+">AB+</option>
																	<option value="AB-">AB-</option>
																	<option value="O+">O+</option>
																	<option value="O-">O-</option>
																</select>
															</div>
														</div>
														<div id="source_hireBox" class="filterItem">
															Source of Hire
															<div>
																<input type="hidden" name="hdnHire" id="hdnHire" value="" />
																<select id="selHire" name="selHire" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$hire_result = count($sourcehire);
																	for($i=0; $i<$hire_result; $i++)
																	{?>
																		<option><?php echo $sourcehire[$i]['source_hire_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="resignResonBox" class="filterItem">
															Reason of Separation
															<div>
																<input type="hidden" name="hdnReaSep" id="hdnReaSep" value="" />
																<select id="selReaSep" name="selReaSep" multiple="multiple" class="search_UI" style="width:200px;">
																<?php 
																	$separation_result = count($separation);
																	for($i=0; $i<$hire_result; $i++)
																	{?>
																		<option><?php echo $separation[$i]['separation_name'];?></option>
																	<?php }
																?>
																</select>
															</div>
														</div>
														<div id="emp_typeBox" class="filterItem">
															Employee Type
															<div>
																<input type="hidden" name="hdnEmpType" id="hdnEmpType" value="" />
																<select id="selEmpType" name="selEmpType"  multiple="multiple" class="search_UI" style="width:200px;">
																	<option value="F" <?php if($this->input->post('selEmpType') == 'F') echo 'selected="selected"';?>>Full Time</option>
																	<option value="C" <?php if($this->input->post('selEmpType') == 'C') echo 'selected="selected"';?>>Contractual</option>
																	<option value="I" <?php if($this->input->post('selEmpType') == 'I') echo 'selected="selected"';?>>Interns</option>
																</select>
															</div>
														</div>
														<div id="emp_status_typeBox" class="filterItem">
															Employee Status Type
															<div>
																<input type="hidden" name="hdnEmpStatusType" id="hdnEmpStatusType" value="" />
																<select id="selEmpStatusType" name="selEmpStatusType"  multiple="multiple" class="search_UI" style="width:200px;">
																	<option value="Normal" <?php if($this->input->post('selEmpStatusType') == 'Normal') echo 'selected="selected"';?>>Normal</option>
																	<option value="Resigned" <?php if($this->input->post('selEmpStatusType') == 'Resigned') echo 'selected="selected"';?>>Resigned</option>
																	<option value="Retired" <?php if($this->input->post('selEmpStatusType') == 'Retired') echo 'selected="selected"';?>>Retired</option>
																	<option value="Terminated" <?php if($this->input->post('selEmpStatusType') == 'Terminated') echo 'selected="selected"';?>>Terminated</option>
																	<option value="Transferred" <?php if($this->input->post('selEmpStatusType') == 'Transferred') echo 'selected="selected"';?>>Transferred</option>
																</select>
															</div>
														</div>
														<div id="user_statusBox" class="filterItem">
															Employee Status
															<div>
																<select id="selEmpStatus" name="selEmpStatus"  class="search_UI" style="width:200px;">
																	<option value="">All</option>
																	<option value="1" <?php if($this->input->post('selEmpStatus') == '1') echo 'selected="selected"';?>>Active</option>
																	<option value="2" <?php if($this->input->post('selEmpStatus') == '2') echo 'selected="selected"';?>>Inactive</option>
																</select>
															</div>
														</div>
														<div id="FnF_statusBox" class="filterItem">
															FnF Status
															<div>
																<select id="selFnFStatus" name="selFnFStatus"  class="search_UI" style="width:200px;">
																	<option value="">All</option>
																	<option value="Pending">Pending</option>
																	<option value="Cleared">Cleared</option>
																</select>
															</div>
														</div>
														<div id="confirm_statusBox" class="filterItem">
															Confirmation Status
															<div>
																<select id="selConfStatus" name="selConfStatus"  class="search_UI" style="width:200px;">
																	<option value="">All</option>
																	<option value="Confirmed">Confirmed</option>
																	<option value="Not Confirmed">Not Confirmed</option>
																</select>
															</div>
														</div>
														<div id="DORBox" class="filterItem">
															Resignation Date
															<div>
																<input type="text" id="dorFrom" name="dorFrom" value="" readonly placeholder="From" class="cal search_UI" />
																<input type="text" id="dorTo" name="dorTo" value="" readonly placeholder="To" class="cal search_UI" />
															</div>
														</div>
													</div>
													<div class="clear"></div>
													<input type="submit" name="exportEmployee" value="Generate" class="search_sbmt"/>
													<div class="clear"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
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
