<legend class="pkheader_breadcrumb">Management</legend>
<!-- Menu -->
<div class="side-menu"> 
	<nav class="navbar navbar-default" role="navigation"> 
		<!-- Main Menu -->
		<div class="side-menu-container">
			<ul class="nav navbar-nav">  
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list1">Employee management<span class="caret"></span></a>
					<div id="list1" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'profile_list' || $this->uri->segment(3) == 'inactive_profile_list' || $this->uri->segment(3) == 'add_employee' || $this->uri->segment(3) == 'reset_emp_pwd' || $this->uri->segment(3) == 'emp_vintage_list' || $this->uri->segment(3) == 'emp_details_import' || $this->uri->segment(3) == 'emp_report' || $this->uri->segment(3) == 'resume_format' || $this->uri->segment(3) == 'resignation_approve_reject')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(3) == 'profile_list')?'active':''; ?>">
									<a href="<?php echo base_url('en/management/profile_list');?>">Search/View</a>
								</li>
								<li class="<?php echo ($this->uri->segment(3) == 'inactive_profile_list')?'active':''; ?>">
									<a href="<?php echo base_url('en/management/inactive_profile_list');?>">Inactive Employee</a>
								</li>
							</ul>
						</div>
					</div>
				</li>
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list3">Payroll management<span class="caret"></span></a>
					<div id="list3" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(3) == 'allowance_deduction_list' || $this->uri->segment(3) == 'generate_salary' || $this->uri->segment(3) == 'salary_sheet' || $this->uri->segment(3) == 'mail_salary_slip' || $this->uri->segment(3) == 'payroll_report' || $this->uri->segment(3) == 'increment_report' || $this->uri->segment(3) == 'epf_report' || $this->uri->segment(3) == 'esi_report' || $this->uri->segment(3) == 'graph_profile_list' || $this->uri->segment(3) == 'ctc_graph')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'> 
								<li class="<?php echo ($this->uri->segment(3) == 'graph_profile_list' || $this->uri->segment(3) == 'ctc_graph')?'active':''; ?>">
									<a href="<?php echo base_url('en/management/graph_profile_list');?>">CTC Graph</a>
								</li> 
							</ul>
						</div>
					</div>
				</li> 
			</ul>
		</div>
	</nav> 
</div>

 