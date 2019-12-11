<legend class="pkheader_breadcrumb">Human Resource</legend>
<!-- Menu -->
<div class="side-menu"> 
	<nav class="navbar navbar-default" role="navigation"> 
		<!-- Main Menu -->
		<div class="side-menu-container">
			<ul class="nav navbar-nav"> 
				<?php
				if($this->session->userdata('user_type') == 'HRM' || $this->session->userdata('user_type') == 'ADMINISTRATOR')
				{?>
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list111">Masters<span class="caret"></span></a>
					<div id="list111" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'master_payroll_access' || $this->uri->segment(3) == 'master_country' || $this->uri->segment(3) == 'master_state' || $this->uri->segment(3) == 'master_company_location' || $this->uri->segment(3) == 'master_department' || $this->uri->segment(3) == 'master_designation' || $this->uri->segment(3) == 'master_skills' || $this->uri->segment(3) == 'master_grade' || $this->uri->segment(3) == 'master_level' || $this->uri->segment(3) == 'master_education' || $this->uri->segment(3) == 'master_specialization' || $this->uri->segment(3) == 'master_board_university' || $this->uri->segment(3) == 'master_experience' || $this->uri->segment(3) == 'master_joining_kit' || $this->uri->segment(3) == 'emp_minrequirement' || $this->uri->segment(3) == 'master_hod' || $this->uri->segment(3) == 'master_separation' || $this->uri->segment(3) == 'master_source_hire' || $this->uri->segment(3) == 'master_bank' || $this->uri->segment(3) == 'master_miscellaneous' || $this->uri->segment(3) == 'email_category' || $this->uri->segment(3) == 'email_template')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(3) == 'master_payroll_access')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_payroll_access');?>">Payroll Access</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_country')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_country');?>">Country</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_state')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_state');?>">State</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_company_location')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_company_location');?>">Company Location</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_department')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_department');?>">Department</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_designation')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_designation');?>">Designation</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_skills')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_skills');?>">Skills</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_grade')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_grade');?>">Grade</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_level')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_level');?>">Level</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_education')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_education');?>">Education</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_specialization')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_specialization');?>">Specialization</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_board_university')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_board_university');?>">Board/University</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_experience')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_experience');?>">Experiences</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_joining_kit')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_joining_kit');?>">Joining Kit</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'emp_minrequirement')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/emp_minrequirement');?>">Requirement</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_hod')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_hod');?>">Define HOD</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_separation')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_separation');?>">Reason of Separation</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_source_hire')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_source_hire');?>">Source of Hire</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_bank')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_bank');?>">Bank</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'master_miscellaneous')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/master_miscellaneous');?>">Define Miscellaneous</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'email_category')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/email_category');?>">Email Template Category</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'email_template')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/email_template');?>">Email Template Master</a>
								</li>
							</ul>
						</div>
					</div>
				</li>
				<?php } ?>				
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list1">Employee management<span class="caret"></span></a>
					<div id="list1" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'profile_list' || $this->uri->segment(3) == 'inactive_profile_list' || $this->uri->segment(3) == 'add_employee' || $this->uri->segment(3) == 'reset_emp_pwd' || $this->uri->segment(3) == 'emp_vintage_list' || $this->uri->segment(3) == 'emp_details_import' || $this->uri->segment(3) == 'emp_report' || $this->uri->segment(3) == 'resume_format' || $this->uri->segment(3) == 'resignation_approve_reject')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(3) == 'profile_list')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/profile_list');?>">Search/View</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'inactive_profile_list')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/inactive_profile_list');?>">Inactive Employee</a>
								</li>
								<?php
								if($this->session->userdata('user_type') == 'HRM' || $this->session->userdata('user_type') == 'ADMINISTRATOR')
								{?>
								<li class="<?php echo ($this->uri->segment(3) == 'add_employee')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/add_employee');?>">Create New Employee</a>
								</li>
								
								<li class="<?php echo ($this->uri->segment(3) == 'reset_emp_pwd')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/reset_emp_pwd');?>">Reset Password</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'emp_details_import')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/emp_details_import');?>">Import Data</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'emp_report')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/emp_report');?>">Report</a>
								</li>
								<?php } ?>
								<li class="<?php echo ($this->uri->segment(3) == 'emp_vintage_list')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/emp_vintage_list');?>">Employee Vintage</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'resume_format')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/resume_format');?>">View Download Resume</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'resignation_approve_reject')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/resignation_approve_reject');?>">View Resignation Letter</a>
								</li> 
							</ul>
						</div>
					</div>
				</li> 
				<?php
				if($this->session->userdata('user_type') == 'HRM' || $this->session->userdata('user_type') == 'ADMINISTRATOR')
				{?>
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list2">Attendance Entry<span class="caret"></span></a>
					<div id="list2" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'biometric_data_upload' || $this->uri->segment(3) == 'lwh_report' || $this->uri->segment(3) == 'emp_attendance_summary')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(3) == 'biometric_data_upload')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/biometric_data_upload');?>">Biometric Data Upload</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'lwh_report')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/lwh_report');?>">LWH Report</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'emp_attendance_summary')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/emp_attendance_summary');?>">Employee Attendance Summary</a>
								</li>
							</ul>
						</div>
					</div>
				</li> 
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list3">Payroll management<span class="caret"></span></a>
					<div id="list3" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'allowance_deduction_list' || $this->uri->segment(3) == 'generate_arrears' || $this->uri->segment(3) == 'generate_salary' || $this->uri->segment(3) == 'salary_sheet' || $this->uri->segment(3) == 'mail_salary_slip' || $this->uri->segment(3) == 'payroll_report' || $this->uri->segment(3) == 'increment_report' || $this->uri->segment(3) == 'epf_report' || $this->uri->segment(3) == 'esi_report' || $this->uri->segment(3) == 'graph_profile_list' || $this->uri->segment(3) == 'allowance_deduction' || $this->uri->segment(3) == 'ctc_graph')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'> 
								<li class="<?php echo ($this->uri->segment(3) == 'generate_arrears')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/generate_arrears');?>">Generate Arrears</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'allowance_deduction_list' || $this->uri->segment(3) == 'allowance_deduction')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/allowance_deduction_list');?>">Multiple allowance/deduction</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'generate_salary')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/generate_salary');?>">Generate Salary</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'salary_sheet')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/salary_sheet');?>">Salary Slip</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'mail_salary_slip')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/mail_salary_slip');?>">Mail Salary Slip</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'payroll_report')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/payroll_report');?>">Payroll Report</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'increment_report')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/increment_report');?>">Increment Report</a>
								</li> 
								<li class="<?php echo ($this->uri->segment(3) == 'epf_report')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/epf_report');?>">EPF Report</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'esi_report')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/esi_report');?>">ESI Report</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'graph_profile_list' || $this->uri->segment(3) == 'ctc_graph')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/graph_profile_list');?>">CTC Graph</a>
								</li> 
							</ul>
						</div>
					</div>
				</li> 
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list4">Benefits Administration<span class="caret"></span></a>
					<div id="list4" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'performance_incentive_slab' || $this->uri->segment(3) == 'attendance_benefit' || $this->uri->segment(3) == 'add_extra_hours')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(3) == 'performance_incentive_slab')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/performance_incentive_slab');?>">Performance Benefit</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'attendance_benefit')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/attendance_benefit');?>">Attendance Benefit</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'add_extra_hours')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/add_extra_hours');?>">Add Extra Hours</a>
								</li>
							</ul>
						</div>
					</div>
				</li> 
				<?php } ?>
				
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list5">Leave<span class="caret"></span></a>
					<div id="list5" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'emp_leave_provision' || $this->uri->segment(3) == 'emp_leave_details' || $this->uri->segment(3) == 'leave_status_info' || $this->uri->segment(3) == 'late_comming' || $this->uri->segment(3) == 'absent_details')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(3) == 'emp_leave_provision')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/emp_leave_provision');?>">Leave Provision</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'emp_leave_details')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/emp_leave_details');?>">Employee Leave Info</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'leave_status_info')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/leave_status_info');?>">Leave Status Info</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'late_comming')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/late_comming');?>">Late Coming</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'absent_details')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/absent_details');?>">Absent Details</a>
								</li>
							</ul>
						</div>
					</div>
				</li> 
				<?php
				if($this->session->userdata('user_type') == 'HRM' || $this->session->userdata('user_type') == 'ADMINISTRATOR')
				{?>
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list6">Expense & Reimbursement<span class="caret"></span></a>
					<div id="list6" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'reimbrusement' || $this->uri->segment(3) == 'reimbrusement_report' || $this->uri->segment(3) == 'emp_gratuity' || $this->uri->segment(3) == 'emp_bonus' || $this->uri->segment(3) == 'emp_fnf')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(3) == 'reimbrusement')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/reimbrusement');?>">Reimbursement</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'reimbrusement_report')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/reimbrusement_report');?>">Reimbursement Report</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'emp_gratuity')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/emp_gratuity');?>">Gratuity</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'emp_bonus')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/emp_bonus');?>">Bonus</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'emp_fnf')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/emp_fnf');?>">F&F</a>
								</li>
							</ul>
						</div>
					</div>
				</li> 
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list7">Loans<span class="caret"></span></a>
					<div id="list7" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'loan_advance_approve_reject' || $this->uri->segment(3) == 'loan' || $this->uri->segment(3) == 'advance_aaplied' || $this->uri->segment(3) == 'loan_advance_report')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(3) == 'loan_advance_approve_reject')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/loan_advance_approve_reject');?>">Loan/Advance Approval</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'loan')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/loan');?>">Loan Applied</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'advance_aaplied')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/advance_aaplied');?>">Advance Applied</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'loan_advance_report')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/loan_advance_report');?>">Loan Advance Report</a>
								</li>
							</ul>
						</div>
					</div>
				</li> 
				<?php } ?>
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list8">Recruitment<span class="caret"></span></a>
					<div id="list8" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'resume_databank' || $this->uri->segment(3) == 'shortisted_candidate' || $this->uri->segment(3) == 'interview_scheduled_candidate' || $this->uri->segment(3) == 'interview_rating' || $this->uri->segment(3) == 'placement_consultant' || $this->uri->segment(3) == 'online_mrf_detail' || $this->uri->segment(3) == 'recruitment_report')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(3) == 'resume_databank')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/resume_databank');?>">Resume Databank</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'shortisted_candidate')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/shortisted_candidate');?>">Shortisted Candidate</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'interview_scheduled_candidate')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/interview_scheduled_candidate');?>">Interview Scheduled Candidate</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'interview_rating')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/interview_rating');?>">Interview Rating</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'placement_consultant')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/placement_consultant');?>">Placement Consultant</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'online_mrf_detail')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/online_mrf_detail');?>">Man Power Requisition</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'recruitment_report')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/recruitment_report');?>">Recruitment Report</a>
								</li>
							</ul>
						</div>
					</div>
				</li> 
				<?php
				if($this->session->userdata('user_type') == 'HRM' || $this->session->userdata('user_type') == 'ADMINISTRATOR')
				{?>
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list9">Performance management<span class="caret"></span></a>
					<div id="list9" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'probation_assessment_all' || $this->uri->segment(3) == 'midyear_review_all' || $this->uri->segment(3) == 'midyear_appraisal_report' || $this->uri->segment(3) == 'annual_appraisal_all' || $this->uri->segment(3) == 'annual_appraisal_report')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(3) == 'probation_assessment_all')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/probation_assessment_all');?>">Probation Assessment</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'midyear_review_all')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/midyear_review_all');?>">Mid Year Review</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'midyear_appraisal_report')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/midyear_appraisal_report');?>">Mid Year Review Report</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'annual_appraisal_all')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/annual_appraisal_all');?>">Annual Appraisal All</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'annual_appraisal_report')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/annual_appraisal_report');?>">Annual Appraisal Report</a>
								</li>
							</ul>
						</div>
					</div>
				</li> 
				<?php } ?>
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list10">Events<span class="caret"></span></a>
					<div id="list10" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'emp_retired_details' || $this->uri->segment(3) == 'emp_terminated_details' || $this->uri->segment(3) == 'emp_transfer_details' || $this->uri->segment(3) == 'emp_onhold_details' || $this->uri->segment(3) == 'emp_contract_details' || $this->uri->segment(3) == 'ex_emp_details' || $this->uri->segment(3) == 'alert_general')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(3) == 'emp_retired_details')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/emp_retired_details');?>">Retired Employee Details</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'emp_terminated_details')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/emp_terminated_details');?>">Terminated Employee Details</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'emp_transfer_details')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/emp_transfer_details');?>">Transfer Employee Details</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'emp_onhold_details')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/emp_onhold_details');?>">Employees On Hold</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'emp_contract_details')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/emp_contract_details');?>">Contract based Employees</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'ex_emp_details')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/ex_emp_details');?>">Ex-Employee Details</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'alert_general')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/alert_general');?>">General Alert</a>
								</li>
							</ul>
						</div>
					</div>
				</li>
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list11">Utilities<span class="caret"></span></a>
					<div id="list11" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'birthday_reminder' || $this->uri->segment(3) == 'room_booking')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(3) == 'birthday_reminder')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/birthday_reminder');?>">Birth Day Reminder</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'room_booking')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/room_booking');?>">Online Room Booking</a>
								</li>
							</ul>
						</div>
					</div>
				</li>  
				<?php
				if($this->session->userdata('user_type') == 'HRM' || $this->session->userdata('user_type') == 'ADMINISTRATOR')
				{?>
				<li class="<?php echo ($this->uri->segment(2) == 'training_and_development' || $this->uri->segment(3) == 'training_and_development')? 'active':''; ?>"><a href="<?php echo base_url('en/hr/training_and_development');?>">Training & Development</a></li> 
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list12">HR Information Portal Module<span class="caret"></span></a>
					<div id="list12" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'directors_message' || $this->uri->segment(3) == 'hr_policies' || $this->uri->segment(3) == 'list_holiday' || $this->uri->segment(3) == 'resources_general' || $this->uri->segment(3) == 'view_phn' || $this->uri->segment(3) == 'list_offices' || $this->uri->segment(3) == 'resources_general' || $this->uri->segment(3) == 'policy_approval')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(3) == 'directors_message')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/directors_message');?>">Directors Message</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'hr_policies')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/hr_policies');?>">HR Policies</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'list_holiday')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/list_holiday');?>">List of Holidays</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'resources_general')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/resources_general');?>">In House Monthly Magazine</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'view_phn')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/view_phn');?>">List of Contact Details</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'list_offices')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/list_offices');?>">List of Offices</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'resources_general')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/resources_general');?>">News/Circulars</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'policy_approval')?'active':''; ?>">
									<a href="<?php echo base_url('en/hr/policy_approval');?>">Policy Approval</a>
								</li>
							</ul>
						</div>
					</div>
				</li> 
				<?php } ?>
			</ul>
		</div>
	</nav> 
</div>

 