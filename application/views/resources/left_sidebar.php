<?php
$get_type = "";
$get_seg = $this->uri->segment(2);
?>
<legend class="pkheader_breadcrumb">General Resources</legend>
<!-- Menu -->
<div class="side-menu"> 
	<nav class="navbar navbar-default" role="navigation"> 
	<!-- Main Menu -->
	<div class="side-menu-container">
		<ul class="nav navbar-nav">
			<li class="<?php echo ($get_seg == 'general_resources' || $this->uri->segment(3) == 'general_resources')?'active':''; ?>"><a href="<?php echo base_url('resources/general_resources');?>">General Resources</a></li> 
			<li class="<?php echo ($this->uri->segment(2) == 'photo_gallery')?'active':''; ?>"><a href="<?php echo base_url('resources/photo_gallery');?>">Photo Gallery</a></li>  
			<li class="<?php echo ($this->uri->segment(2) == 'phone_directory')?'active':''; ?>"><a href="<?php echo base_url('resources/phone_directory');?>">Phone Directory</a></li>  
			<li class="<?php echo ($this->uri->segment(2) == 'official_holidays' || $this->uri->segment(3) == 'official_holidays')?'active':''; ?>"><a href="<?php echo base_url('resources/official_holidays');?>">Official Holidays</a></li>  
			<li class="<?php echo ($this->uri->segment(2) == 'cricket_team')?'active':''; ?>"><a href="<?php echo base_url('resources/cricket_team');?>">Cricket Team</a></li>  
		</ul>
	</div><!-- /.navbar-collapse -->
	</nav> 
</div> 