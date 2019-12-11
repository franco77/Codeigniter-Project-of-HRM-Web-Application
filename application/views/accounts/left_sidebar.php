<legend class="pkheader_breadcrumb">Accounts</legend>
<!-- Menu -->
<div class="side-menu"> 
	<nav class="navbar navbar-default" role="navigation"> 
		<!-- Main Menu -->
		<div class="side-menu-container">
			<ul class="nav navbar-nav"> 
				<?php
				if($this->session->userdata('user_type') == 'ACM' || $this->session->userdata('user_type') == 'ADMINISTRATOR')
				{?>
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list2">Master<span class="caret"></span></a>
					<div id="list2" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(2) == 'define_pt_slab' || $this->uri->segment(3) == 'define_pt_slab' || $this->uri->segment(2) == 'define_income_tax_slab' || $this->uri->segment(3) == 'define_income_tax_slab' || $this->uri->segment(2) == 'tax_deduction_limit' || $this->uri->segment(3) == 'tax_deduction_limit')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(2) == 'define_pt_slab' || $this->uri->segment(3) == 'define_pt_slab')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/define_pt_slab');?>">Define PT Slab</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'define_income_tax_slab' || $this->uri->segment(3) == 'define_income_tax_slab')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/define_income_tax_slab');?>">Define Income Tax Slab</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'tax_deduction_limit' || $this->uri->segment(3) == 'tax_deduction_limit')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/tax_deduction_limit');?>">Tax Deduction Limit</a>
								</li>
							</ul>
						</div>
					</div>
				</li> 
				<?php } ?>
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list1">Payroll management<span class="caret"></span></a>
					<div id="list1" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'emp_leave_provision' || $this->uri->segment(2) == 'emp_leave_provision' || $this->uri->segment(2) == 'allowance_deduction_list' || $this->uri->segment(3) == 'allowance_deduction_list' || $this->uri->segment(2) == 'loan_advance_profile_list' || $this->uri->segment(2) == 'loan_advance_add' || $this->uri->segment(3) == 'loan_advance_loan' || $this->uri->segment(2) == 'loan_advance_loan' || $this->uri->segment(2) == 'reimbrusement' || $this->uri->segment(2) == 'emp_gratuity' || $this->uri->segment(2) == 'emp_bonus' || $this->uri->segment(2) == 'emp_fnf' || $this->uri->segment(2) == 'epf_report' || $this->uri->segment(2) == 'esi_report' || $this->uri->segment(2) == 'increment_report' || $this->uri->segment(2) == 'payroll_report' || $this->uri->segment(2) == 'loan_advance_report' || $this->uri->segment(2) == 'reimbrusement_report'  || $this->uri->segment(2) == 'add_reimbursement' || $this->uri->segment(3) == 'add_reimbursement' || $this->uri->segment(3) == 'multiple_allowance' || $this->uri->segment(2) == 'multiple_allowance')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>   
								<?php
								if($this->session->userdata('user_type') == 'ACM' || $this->session->userdata('user_type') == 'ADMINISTRATOR')
								{?>
								<li class="<?php echo ($this->uri->segment(2) == 'emp_leave_provision' || $this->uri->segment(3) == 'emp_leave_provision')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/emp_leave_provision');?>">Leave Provision</a>
								</li>
								<?php } ?>
								<li class="<?php echo ($this->uri->segment(2) == 'allowance_deduction_list' || $this->uri->segment(3) == 'allowance_deduction_list' || $this->uri->segment(3) == 'multiple_allowance' || $this->uri->segment(2) == 'multiple_allowance')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/allowance_deduction_list');?>">Multiple allowance/deduction</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'loan_advance_add' || $this->uri->segment(3) == 'loan_advance_loan' || $this->uri->segment(2) == 'loan_advance_loan')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/loan_advance_add');?>">Loan/Advance</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'reimbrusement' || $this->uri->segment(2) == 'add_reimbursement')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/reimbrusement');?>">Reimbursement</a>
								</li>
								 
								<?php
								if($this->session->userdata('user_type') == 'ACM' || $this->session->userdata('user_type') == 'ADMINISTRATOR')
								{?>
								<li class="<?php echo ($this->uri->segment(2) == 'loan_advance_profile_list')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/loan_advance_profile_list');?>">Loan/Advance Request</a>
								</li>
								<!--<li class="<?php echo ($this->uri->segment(2) == 'reimbrusement')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/reimbrusement');?>">Income Tax Declaration</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'reimbrusement')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/reimbrusement');?>">Income Tax Detail</a>
								</li>-->
								<li class="<?php echo ($this->uri->segment(2) == 'emp_gratuity')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/emp_gratuity');?>">Gratuity</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'emp_bonus')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/emp_bonus');?>">Bonus</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'emp_fnf')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/emp_fnf');?>">F&F</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'epf_report')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/epf_report');?>">EPF Report</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'esi_report')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/esi_report');?>">ESI Report</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'increment_report')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/increment_report');?>">Increment Report</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'payroll_report')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/payroll_report');?>">Payroll Report</a>
								</li>
								<?php } ?>
								<li class="<?php echo ($this->uri->segment(2) == 'loan_advance_report')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/loan_advance_report');?>">Loan Advance Report</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'reimbrusement_report')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/reimbrusement_report');?>">Reimbursement Report</a>
								</li> 
							</ul>
						</div>
					</div>
				</li> 
				<?php
				if($this->session->userdata('user_type') == 'ACM' || $this->session->userdata('user_type') == 'ADMINISTRATOR')
				{?>
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list2">Income Tax Management<span class="caret"></span></a>
					<div id="list2" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(2) == 'estimated_tax_declaration' || $this->uri->segment(2) == 'final_tax_declaration' || $this->uri->segment(2) == 'estimated_declaration_form' || $this->uri->segment(2) == 'estimated_computation_form' || $this->uri->segment(2) == 'estimated_tax_computation' || $this->uri->segment(2) == 'final_tax_computation' || $this->uri->segment(2) == 'final_computation_form')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(2) == 'estimated_tax_declaration' || $this->uri->segment(2) == 'estimated_declaration_form')? 'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/estimated_tax_declaration');?>">Estimated Tax Declaration</a>
								</li> 
								<li class="<?php echo ($this->uri->segment(2) == 'final_tax_declaration')? 'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/final_tax_declaration');?>">Final Tax Declaration</a>
								</li> 
								<li class="<?php echo ($this->uri->segment(2) == 'estimated_tax_computation' || $this->uri->segment(2) == 'estimated_computation_form')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/estimated_tax_computation');?>">Estimated Tax Computation</a>
								</li> 
								<li class="<?php echo ($this->uri->segment(2) == 'final_tax_computation' || $this->uri->segment(2) == 'final_computation_form')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_admin/final_tax_computation');?>">Final Tax Computation</a>
								</li> 
							</ul>
						</div>
					</div>
				</li> 
				<?php } ?>
				<?php
				if($this->session->userdata('user_type') == 'ACM' || $this->session->userdata('user_type') == 'ADMINISTRATOR')
				{?>
					<li class="<?php echo ($this->uri->segment(2) == 'other_income_list' || $this->uri->segment(3) == 'other_income_list' || $this->uri->segment(3) == 'other_income' || $this->uri->segment(3) == 'other_income')? 'active':''; ?>"><a href="<?php echo base_url('accounts_admin/other_income_list');?>">Other Income</a></li> 
				<?php } ?>
				
			</ul>
		</div>
	</nav> 
</div>
 

 