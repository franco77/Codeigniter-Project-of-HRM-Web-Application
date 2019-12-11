<legend class="pkheader_breadcrumb">Polosoft Classified Menu</legend>
<!-- Menu -->
<div class="side-menu"> 
	<nav class="navbar navbar-default" role="navigation"> 
	<!-- Main Menu -->
	<div class="side-menu-container">
		<ul class="nav navbar-nav">
			<li class="<?php echo ($this->uri->segment(2) == 'classified' || $this->uri->segment(3) == 'classified')?'active':''; ?>"><a href="<?php echo base_url('aabsys_classified/classified');?>"> <span class="glyphicon glyphicon-plane"></span> Classified </a></li> 
			<li class="<?php echo ($this->uri->segment(2) == 'my_classified' || $this->uri->segment(3) == 'my_classified')?'active':''; ?>"><a href="<?php echo base_url('aabsys_classified/my_classified');?>"><span class="glyphicon glyphicon-plane"></span> My Classified</a></li>  
		</ul>
		<!--<ul class="nav navbar-nav">
			<?php /*foreach ($aabsys_classified_menu as $parent => $parent_params): ?>

				<?php if ( empty($page_auth[$parent_params['url']]) || $this->ion_auth->in_group($page_auth[$parent_params['url']]) ): ?>
				<?php if ( empty($parent_params['children']) ): ?>

					<?php $active = ($current_uri==$parent_params['url'] || $ctrler==$parent); ?>
					<li class="<?php if ($active) echo 'active'; ?>"><a href="<?php echo $parent_params['url']; ?>"><span class="glyphicon glyphicon-plane"></span> <?php echo $parent_params['name']; ?></a></li> 
					<?php else: ?>

					<?php $parent_active = ($ctrler==$parent); ?>
					<li class="panel panel-default<?php if ($parent_active) echo 'active'; ?>" id="dropdown">
						<a data-toggle="collapse" href="#list1<?php echo str_replace(' ', '', $parent);?>">
							<span class="glyphicon glyphicon-user"></span> <?php echo $parent_params['name']; ?> <span class="caret"></span> 
						</a>
						<div id="list1<?php echo str_replace(' ', '', $parent);?>" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class='nav navbar-nav'>
									<?php foreach ($parent_params['children'] as $name => $url): ?>
										<?php if ( empty($page_auth[$url]) || $this->ion_auth->in_group($page_auth[$url]) ): ?>
										<?php $child_active = ($current_uri==$url); ?>
										<li <?php if ($child_active) echo 'class="active"'; ?>>
											<a href='<?php echo $url; ?>'><?php echo $name; ?></a>
										</li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					</li>

				<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; */ ?> 
		</ul>-->
	</div><!-- /.navbar-collapse -->
	</nav> 
</div> 

 