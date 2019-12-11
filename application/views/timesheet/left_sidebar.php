<legend class="pkheader_breadcrumb">TimeSheet</legend>
<!-- Menu --> 
<div class="side-menu"> 
	<nav class="navbar navbar-default" role="navigation"> 
		<!-- Main Menu -->
		<div class="side-menu-container" >
			<ul class="nav navbar-nav">  
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list1">My Timesheet<span class="caret"></span></a>
					<div id="list1" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(2) == 'yearly' || $this->uri->segment(2) == 'timesheet' || $this->uri->segment(2) == 'monthly')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(2) == 'yearly' || $this->uri->segment(2) == 'timesheet')?'active':''; ?>">
									<a href="<?php echo base_url('timesheet/yearly');?>">Yearly</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'monthly')?'active':''; ?>">
									<a href="<?php echo base_url('timesheet/monthly');?>">Monthly</a>
								</li> 
							</ul>
						</div>
					</div>
				</li> 
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list2">Regularize<span class="caret"></span></a>
					<div id="list2" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(2) == 'apply_for_regularise' || $this->uri->segment(2) == 'my_regularise_application' || $this->uri->segment(2) == 'regularise_request' || $this->uri->segment(2) == 'direct_regularise')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<?php //if($this->session->userdata('emp_type') != 'CO'){ ?>
								<li class="<?php echo ($this->uri->segment(2) == 'apply_for_regularise')?'active':''; ?>">
									<a href="<?php echo base_url('timesheet/apply_for_regularise');?>">Apply For Regularize</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'my_regularise_application')?'active':''; ?>">
									<a href="<?php echo base_url('timesheet/my_regularise_application');?>">My Regularize Application</a>
								</li>
								<?php //} ?>
								<?php if($this->session->userdata('isAReportingManager') == 'YES'){ ?>
								<li class="<?php echo ($this->uri->segment(2) == 'regularise_request')?'active':''; ?>">
									<a href="<?php echo base_url('timesheet/regularise_request');?>">Regularize Request</a>
								</li>
								<?php } ?>
								<?php 
									if($this->session->userdata('user_type') == 'ADMINISTRATOR' || $this->session->userdata('user_type') == 'HRM')
									{ ?>
										<li class="<?php echo ($this->uri->segment(2) == 'direct_regularise')?'active':''; ?>">
											<a href="<?php echo base_url('timesheet/direct_regularise');?>">Direct Regularize</a>
										</li>  
									<?php }
								?>  
							</ul>
						</div>
					</div>
				</li> 
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list3">Leave Management<span class="caret"></span></a>
					<div id="list3" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(2) == 'apply_for_leave' || $this->uri->segment(2) == 'my_leave_application' || $this->uri->segment(2) == 'leave_request' || $this->uri->segment(2) == 'my_leave_status' || $this->uri->segment(2) == 'leave_app_mgt_emp')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<?php //if($this->session->userdata('emp_type') != 'CO'){ ?>
								<li class="<?php echo ($this->uri->segment(2) == 'apply_for_leave')?'active':''; ?>">
									<a href="<?php echo base_url('timesheet/apply_for_leave');?>">Apply For Leave</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'my_leave_application')?'active':''; ?>">
									<a href="<?php echo base_url('timesheet/my_leave_application');?>">My Leave Application</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'my_leave_status')?'active':''; ?>">
									<a href="<?php echo base_url('timesheet/my_leave_status');?>">My leave Status</a>
								</li>
								<?php //} ?>
								<?php if($this->session->userdata('isAReportingManager') == 'YES'){ ?>
								<li class="<?php echo ($this->uri->segment(2) == 'leave_request')?'active':''; ?>">
									<a href="<?php echo base_url('timesheet/leave_request');?>">Leave Request</a>
								</li>
								<?php } ?>
								<?php 
									if($this->session->userdata('user_type') == 'ADMINISTRATOR' || $this->session->userdata('user_type') == 'HRM')
									{?>
										<li class="<?php echo ($this->uri->segment(2) == 'leave_app_mgt_emp')?'active':''; ?>">
											<a href="<?php echo base_url('timesheet/leave_app_mgt_emp');?>">Direct Apply For Leave</a>
										</li> 
									<?php }
								?> 
							</ul>
						</div>
					</div>
				</li>
				<?php 
				if($this->session->userdata('user_type') == 'ADMINISTRATOR' || $this->session->userdata('user_type') == 'HRM' || $this->session->userdata('isAReportingManager') == 'YES')
				//if($this->session->userdata('user_type') == 'ADMINISTRATOR' || $this->session->userdata('user_type') == 'HR' || $this->session->userdata('isAReportingManager') == 'YES')
				{
					?>
				<li class="<?php echo ($this->uri->segment(2) == 'view_members' || $this->uri->segment(2) == 'leave_status')?'active':''; ?>">
					<a href="<?php echo base_url('timesheet/view_members');?>">View Members</a>
				</li>
				<?php }
				?> 
				<?php 
					if($this->session->userdata('user_type') == 'ADMINISTRATOR' || $this->session->userdata('user_type') == 'HRM')
					{?>
						<li class="<?php echo ($this->uri->segment(2) == 'employee_timesheet')?'active':''; ?>"><a href="<?php echo base_url('timesheet/employee_timesheet');?>">Employee Time Sheet</a></li> 
					<?php }
				?> 
				
				
				<?php 
				if($this->session->userdata('user_role') <= 4)
				{ 
				?>
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list4">Assets Management System(AMS)<span class="caret"></span></a>
					<div id="list4" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(2) == 'allot_machine' )?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(2) == 'allot_machine')?'active':''; ?>">
									<a href="<?php echo base_url('timesheet/allot_machine');?>">Allot Machine</a>
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

 