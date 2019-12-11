<legend class="pkheader_breadcrumb">Admin </legend>
<!-- Menu --> 
<div class="side-menu"> 
	<nav class="navbar navbar-default" role="navigation"> 
		<!-- Main Menu -->
		<div class="side-menu-container">
			<ul class="nav navbar-nav">  
				<li class="panel panel-default" id="dropdown">
					<a data-toggle="collapse" href="#list3">Hall of Fame<span class="caret"></span></a>
					<div id="list3" class="panel-collapse close-state collapse <?php echo ($this->uri->segment(2) == 'probation_assessment_form')?'in':''; ?>">
						<div class="panel-body">
							<ul class='nav navbar-nav'>  
								<li class="<?php echo ($this->uri->segment(2) == 'hall_of_fame')?'active':''; ?>">
									<a href="<?php echo base_url('admin_settings/hall_of_fame');?>">Hall of Fame Form</a>
								</li>
								<li class="<?php echo ($this->uri->segment(2) == 'hall_of_fame')?'active':''; ?>">
									<a href="<?php echo base_url('admin_settings/hall_of_fame_list');?>">Manage Hall of Fame</a>
								</li>
							</ul>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</nav> 
</div>

 