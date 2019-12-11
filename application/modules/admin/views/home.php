<div class="row">
	<div class="col-md-8">
		<?php echo modules::run('adminlte/widget/box_open', 'Shortcuts'); ?>
		<?php echo modules::run('adminlte/widget/app_btn', 'fa fa-user', 'Home', 'home'); ?>
		<?php //echo modules::run('adminlte/widget/app_btn', 'fa fa-sign-out', 'Timesheet', 'timesheet'); ?>
		<?php //echo modules::run('adminlte/widget/app_btn', 'fa fa-sign-out', 'Master', 'master'); ?>
		<?php //echo modules::run('adminlte/widget/app_btn', 'fa fa-user', 'Hr', 'hr'); ?>
		<?php //echo modules::run('adminlte/widget/app_btn', 'fa fa-user', 'Account', 'account_master/define_pt_slab'); ?>
		<?php //echo modules::run('adminlte/widget/app_btn', 'fa fa-user', 'Production', 'production'); ?> 
		<?php //echo modules::run('adminlte/widget/app_btn', 'fa fa-sign-out', 'News & Events', 'panel/logout'); ?>
		<?php //echo modules::run('adminlte/widget/app_btn', 'fa fa-user', 'Resources', 'panel/account'); ?>
		<?php //echo modules::run('adminlte/widget/app_btn', 'fa fa-sign-out', 'HR Help Desk', 'panel/logout'); ?>
		<?php //echo modules::run('adminlte/widget/app_btn', 'fa fa-user', 'Account', 'panel/account'); ?> 
		<?php //echo modules::run('adminlte/widget/app_btn', 'fa fa-user', 'User Account', 'panel/account'); ?>
		<?php echo modules::run('adminlte/widget/app_btn', 'fa fa-sign-out', 'Logout', 'panel/logout'); ?>
		<?php echo modules::run('adminlte/widget/box_close'); ?>
	</div>

	<div class="col-md-4">
		<?php echo modules::run('adminlte/widget/info_box', 'yellow', $count['users'], 'Users', 'fa fa-users', 'user'); ?>
	</div>
	
</div>
