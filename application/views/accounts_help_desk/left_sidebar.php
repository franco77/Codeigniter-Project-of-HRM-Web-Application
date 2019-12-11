<legend class="pkheader_breadcrumb">Accounts help desk menu</legend>
<!-- Menu -->
<div class="side-menu"> 
	<nav class="navbar navbar-default" role="navigation"> 
		<!-- Main Menu -->
		<div class="side-menu-container">
			<ul class="nav navbar-nav"> 
			
				<li class="<?php echo ($this->uri->segment(2) == 'professional_tax_slab' || $this->uri->segment(3) == 'professional_tax_slab')? 'active':''; ?>"><a href="<?php echo base_url('accounts_help_desk/professional_tax_slab');?>">Professional Tax Slab</a></li> 
				<li class="<?php echo ($this->uri->segment(2) == 'income_tax_slab_define')?'active':''; ?>"><a href="<?php echo base_url('accounts_help_desk/income_tax_slab_define');?>">Income Tax Slab </a></li> 
				
				<?php if($this->session->userdata('emp_type') != 'CO'){ ?>
				<li class="<?php echo ($this->uri->segment(2) == 'tax_deduction_limit_define')?'active':''; ?>"><a href="<?php echo base_url('accounts_help_desk/tax_deduction_limit_define');?>">Tax Exemption/Deduction Limit </a></li> 
				<?php } ?>
				
				<li class="<?php echo ($this->uri->segment(2) == 'reimbursement')?'active':''; ?>"><a href="<?php echo base_url('accounts_help_desk/reimbursement');?>">Reimbursement </a></li> 
				
				<?php if($this->session->userdata('emp_type') != 'CO'){ ?>
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list1">Income Tax Declaration Form<span class="caret"></span></a>
					<div id="list1" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(2) == 'estimated_declaration_form' || $this->uri->segment(2) == 'final_delcaration_form' || $this->uri->segment(3) == 'estimated_declaration_form' || $this->uri->segment(3) == 'final_delcaration_form')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(2) == 'estimated_declaration_form' || $this->uri->segment(3) == 'estimated_declaration_form')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_help_desk/estimated_declaration_form');?>">Estimated Declaration Form</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'final_delcaration_form' || $this->uri->segment(3) == 'final_delcaration_form')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_help_desk/final_delcaration_form');?>">Final Declaration Form</a>
								</li> 
							</ul>
						</div>
					</div>
				</li> 
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list2">My Income Tax Declaration<span class="caret"></span></a>
					<div id="list2" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(2) == 'my_estimated_declaration' || $this->uri->segment(2) == 'my_final_declaration')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(2) == 'my_estimated_declaration')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_help_desk/my_estimated_declaration');?>">My Estimated Declaration</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'my_final_declaration')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_help_desk/my_final_declaration');?>">My final Declaration</a>
								</li> 
								<!--<li class="<?php echo ($this->uri->segment(2) == 'my_other_income')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_help_desk/my_other_income');?>">My Other Income</a>
								</li> -->
							</ul>
						</div>
					</div>
				</li> 
				
				<li class="<?php echo ($this->uri->segment(2) == 'my_other_income')?'active':''; ?>"><a href="<?php echo base_url('accounts_help_desk/my_other_income');?>">My Other Income</a></li> 
				
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list3">My Tax Computation Sheet <span class="caret"></span></a>
					<div id="list3" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(2) == 'estimated_tax_compution_sheet' || $this->uri->segment(2) == 'final_tax_computation_sheet')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(2) == 'estimated_tax_compution_sheet')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_help_desk/estimated_tax_compution_sheet');?>">Estimated Tax Computaion Sheet</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'final_tax_computation_sheet')?'active':''; ?>">
									<a href="<?php echo base_url('accounts_help_desk/final_tax_computation_sheet');?>">Final Tax Computation Sheet</a>
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


