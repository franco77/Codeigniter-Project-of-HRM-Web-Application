<legend class="pkheader_breadcrumb">HR HELP DESK</legend>
<!-- Menu --> 
<div class="side-menu"> 
	<nav class="navbar navbar-default" role="navigation"> 
		<!-- Main Menu -->
		<div class="side-menu-container">
			<ul class="nav navbar-nav">  
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list1">Payroll Help<span class="caret"></span></a>
					<div id="list1" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(2) == 'payroll_help' || $this->uri->segment(3) == 'payroll_help' || $this->uri->segment(2) == 'assign_shift' || $this->uri->segment(2) == 'download_salary_slip' || $this->uri->segment(2) == 'employee_suggestion' || $this->uri->segment(2) == 'employee_reimbursement' || $this->uri->segment(2) == 'income_tax_declaration')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(2) == 'payroll_help' || $this->uri->segment(3) == 'payroll_help')?'active':''; ?>">
									<a href="<?php echo base_url('hr_help_desk/payroll_help');?>">Payroll Help</a>
								</li>
								<?php 
								if($this->session->userdata('user_type') == 'ADMINISTRATOR' || $this->session->userdata('isAReportingManager') == 'YES')
								{ ?>
									<li class="<?php echo ($this->uri->segment(2) == 'assign_shift')?'active':''; ?>">
										<a href="<?php echo base_url('hr_help_desk/assign_shift');?>">Assign Shift</a>
									</li>
								<?php } ?>
								<?php if($this->session->userdata('emp_type') != 'CO'){ ?>
								<li class="<?php echo ($this->uri->segment(2) == 'download_salary_slip')?'active':''; ?>">
									<a href="<?php echo base_url('hr_help_desk/download_salary_slip');?>">Download Salary Slip</a>
								</li>
								<?php } ?>
								<li class="<?php echo ($this->uri->segment(2) == 'employee_suggestion')?'active':''; ?>">
									<a href="<?php echo base_url('hr_help_desk/employee_suggestion');?>">Employee Suggestion</a>
								</li> 
								<li class="<?php echo ($this->uri->segment(2) == 'employee_reimbursement')?'active':''; ?>">
									<a href="<?php echo base_url('hr_help_desk/employee_reimbursement');?>">Reimbursement</a>
								</li>
                                <?php if($this->session->userdata('user_id')!='10010'){ ?>
								<li class="<?php echo ($this->uri->segment(2) == 'income_tax_declaration')?'active':''; ?>">
									<a href="<?php echo base_url('hr_help_desk/income_tax_declaration').'/?id='.$this->session->userdata('user_id');?>"> Income Tax Declaration</a>
								</li>
                                <?php } ?>
							</ul>
						</div>
					</div>
				</li> 
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list2">Employee Management<span class="caret"></span></a>
					<div id="list2" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(2) == 'resume_form' || $this->uri->segment(2) == 'apply_resignation' || $this->uri->segment(2) == 'my_resignation_application' || $this->uri->segment(2) == 'resignation_approve_reject' || $this->uri->segment(2) == 'apply_loan_advance' || $this->uri->segment(2) == 'my_loan_advance_application' || $this->uri->segment(2) == 'loan_advance_approve_reject' || $this->uri->segment(2) == 'goal_approve_reject')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(2) == 'resume_form')?'active':''; ?>">
									<a href="<?php echo base_url('hr_help_desk/resume_form');?>">Resume Format</a>
								</li>
								<?php if($this->session->userdata('emp_type') != 'I' && $this->session->userdata('emp_type') != 'CO'){ ?>
								<li class="<?php echo ($this->uri->segment(2) == 'apply_resignation')?'active':''; ?>">
									<a href="<?php echo base_url('hr_help_desk/apply_resignation');?>">Apply Resignation</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'my_resignation_application')?'active':''; ?>">
									<a href="<?php echo base_url('hr_help_desk/my_resignation_application');?>">My Resignation Application</a>
								</li>
								<?php } ?>
								<?php if($this->session->userdata('isAReportingManager') == 'YES'){ ?>
									<li class="<?php echo ($this->uri->segment(2) == 'resignation_approve_reject')?'active':''; ?>">
										<a href="<?php echo base_url('hr_help_desk/resignation_approve_reject');?>">View Resignation Letter</a>
									</li>
								<?php } ?>
								<?php if($this->session->userdata('emp_type') != 'CO'){ ?>
								<li class="<?php echo ($this->uri->segment(2) == 'apply_loan_advance')?'active':''; ?>">
									<a href="<?php echo base_url('hr_help_desk/apply_loan_advance');?>">Apply Loan/Advance</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'my_loan_advance_application')?'active':''; ?>">
									<a href="<?php echo base_url('hr_help_desk/my_loan_advance_application');?>">My Loan/Advance Application</a>
								</li>
								<?php } ?>
								<?php if($this->session->userdata('user_type') == 'ADMINISTRATOR' || $this->session->userdata('isAReportingManager') == 'YES' || $this->session->userdata('isDepartmentHead') == 'YES' ){ ?>
									<li class="<?php echo ($this->uri->segment(2) == 'loan_advance_approve_reject')?'active':''; ?>">
										<a href="<?php echo base_url('hr_help_desk/loan_advance_approve_reject');?>">View Loan/Advance</a>
									</li>
								<?php } ?>
								<?php if($this->session->userdata('isAReportingManager') == 'YES'){ ?>
									<li class="<?php echo ($this->uri->segment(2) == 'goal_approve_reject')?'active':''; ?>">
										<a href="<?php echo base_url('hr_help_desk/goal_approve_reject');?>">View Goal/JD</a>
									</li> 
								<?php } ?>
							</ul>
						</div>
					</div>
				</li> 
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list3">Performance Management<span class="caret"></span></a>
					<div id="list3" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(2) == 'probation_assessment_form' || $this->uri->segment(2) == 'probation_assessment' || $this->uri->segment(2) == 'my_probation_assessment' || $this->uri->segment(2) == 'midyear_review_form' || $this->uri->segment(2) == 'midyear_review' || $this->uri->segment(2) == 'my_midyear_review' || $this->uri->segment(2) == 'annual_appraisal_form' || $this->uri->segment(2) == 'annual_appraisal' || $this->uri->segment(2) == 'annual_appraisal_reviewer' || $this->uri->segment(2) == 'my_annual_appraisal')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<?php if($this->session->userdata('isAReportingManager') == 'YES'){ ?>
									<li class="<?php echo ($this->uri->segment(2) == 'probation_assessment_form')?'active':''; ?>">
										<a href="<?php echo base_url('hr_help_desk/probation_assessment_form');?>">Probation Assessment Form</a>
									</li>
								<?php } ?>
								<?php if($this->session->userdata('isAReportingManager') == 'YES'){ ?>
									<li class="<?php echo ($this->uri->segment(2) == 'probation_assessment')?'active':''; ?>">
										<a href="<?php echo base_url('hr_help_desk/probation_assessment');?>">Probation Assessment</a>
									</li>
								<?php } ?>
								
								<?php if($this->session->userdata('emp_type') != 'CO'){ ?>
								<li class="<?php echo ($this->uri->segment(2) == 'my_probation_assessment')?'active':''; ?>">
									<a href="<?php echo base_url('hr_help_desk/my_probation_assessment');?>">My Probation Assessment</a>
								</li>
								<?php } ?>
								<?php if($this->session->userdata('emp_type') != 'CO'){ ?>
								<?php //if( ( strtotime( date('Y-m-d') ) <  strtotime( date('Y').'-07-25' ) ) && ( strtotime( date('Y-m-d') ) >  strtotime( date('Y').'-07-05' ) ) ){ ?>
								<?php if($this->session->userdata('user_type') != 'MANAGEMENT'){ ?>
								<li class="<?php echo ($this->uri->segment(2) == 'midyear_review_form')?'active':''; ?>">
									<a href="<?php echo base_url('hr_help_desk/midyear_review_form');?>">Mid Year Review Form</a>
								</li>
								<?php } ?>
								<?php //} ?>
								<?php } ?>
								<?php if($this->session->userdata('isAReportingManager') == 'YES'){ ?>
									<li class="<?php echo ($this->uri->segment(2) == 'midyear_review')?'active':''; ?>">
										<a href="<?php echo base_url('hr_help_desk/midyear_review');?>">Mid Year Review (Approval)</a>
									</li>
								<?php } ?>
								<?php if($this->session->userdata('emp_type') != 'CO' && $this->session->userdata('user_type') != 'MANAGEMENT'){ ?>
								<li class="<?php echo ($this->uri->segment(2) == 'my_midyear_review')?'active':''; ?>">
									<a href="<?php echo base_url('hr_help_desk/my_midyear_review');?>">My Mid Year Review</a>
								</li>
								<?php /* if( ( strtotime( date('Y-m-d') ) <  strtotime( date('Y').'-03-25' ) ) && ( strtotime( date('Y-m-d') ) >  strtotime( date('Y').'-03-05' ) ) ){ */ ?>
								<?php if($this->session->userdata('user_type') != 'MANAGEMENT'){ ?>
									<li class="<?php echo ($this->uri->segment(2) == 'annual_appraisal_form')?'active':''; ?>">
										<a href="<?php echo base_url('hr_help_desk/annual_appraisal_form');?>">Annual Appraisal Form</a>
									</li>
								<?php } ?>
								<?php //} ?>
								<?php } ?>
								<?php if($this->session->userdata('isAReportingManager') == 'YES'){ ?>
									<li class="<?php echo ($this->uri->segment(2) == 'annual_appraisal')?'active':''; ?>">
										<a href="<?php echo base_url('hr_help_desk/annual_appraisal');?>">Annual Appraisal (Approval)</a>
									</li>
								<?php } ?>
								<?php if($this->session->userdata('isAReportingManager') == 'YES'){ ?>
									<li class="<?php echo ($this->uri->segment(2) == 'annual_appraisal_reviewer')?'active':''; ?>">
										<a href="<?php echo base_url('hr_help_desk/annual_appraisal_reviewer');?>">Annual Appraisal (Reviewer Approval)</a>
									</li>
								<?php } ?>
								<?php if($this->session->userdata('emp_type') != 'CO' && $this->session->userdata('user_type') != 'MANAGEMENT'){ ?>
								<li class="<?php echo ($this->uri->segment(2) == 'my_annual_appraisal')?'active':''; ?>">
									<a href="<?php echo base_url('hr_help_desk/my_annual_appraisal');?>">My Annual Appraisal</a>
								</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</li>
				<?php if($this->session->userdata('isAReportingManager') == 'YES' || $this->session->userdata('user_type') == 'ADMINISTRATOR' || $this->session->userdata('user_type') == 'HR'){ ?>
					<li class="panel panel-default" id="dropdown">
						<a data-toggle="collapse" href="#list4">Recruitment<span class="caret"></span></a>
						<div id="list4" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(2) == 'online_mrf' || $this->uri->segment(2) == 'online_mrf_detail_all' || $this->uri->segment(2) == 'interview_candidate' || $this->uri->segment(2) == 'online_room_booking')?'in':''; ?>">
							<div class="panel-body">
								<ul class='nav navbar-nav'>  
									<li class="<?php echo ($this->uri->segment(2) == 'online_mrf')?'active':''; ?>">
										<a href="<?php echo base_url('hr_help_desk/online_mrf');?>">Online MRF</a>
									</li>
									<li class="<?php echo ($this->uri->segment(2) == 'online_mrf_detail_all')?'active':''; ?>">
										<a href="<?php echo base_url('hr_help_desk/online_mrf_detail_all');?>">All MRF Detail</a>
									</li>
									<li class="<?php echo ($this->uri->segment(2) == 'interview_candidate')?'active':''; ?>">
										<a href="<?php echo base_url('hr_help_desk/interview_candidate');?>">Interview Rating</a>
									</li>
									<li class="<?php echo ($this->uri->segment(2) == 'online_room_booking')?'active':''; ?>">
										<a href="<?php echo base_url('hr_help_desk/online_room_booking');?>">Online Room Booking</a>
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

 