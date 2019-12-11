<legend class="pkheader_breadcrumb">My Profile</legend>
<?php
$get_id = "";
if (isset($_GET['id']))
{
	$get_id = "?id=".$_GET['id'];
}
?>
<!-- Menu -->
<div class="side-menu"> 
	<nav class="navbar navbar-default" role="navigation"> 
	<!-- Main Menu -->
	<div class="side-menu-container">
		<ul class="nav navbar-nav">
			<li class="<?php echo ($this->uri->segment(2) == 'my_account')? 'active':''; ?>"><a href="<?php echo base_url('my_account'.$get_id);?>">General</a></li>
			<li class="<?php echo ($this->uri->segment(2) == 'comp_profile_readonly_emp')? 'active':''; ?>"><a href="<?php echo base_url('my_account/comp_profile_readonly_emp'.$get_id);?>">Company</a></li>  
			<li class="<?php echo ($this->uri->segment(2) == 'salary_profile_readonly_emp')? 'active':''; ?>"><a href="<?php echo base_url('my_account/salary_profile_readonly_emp'.$get_id);?>">Salary</a></li> 
			<li class="<?php echo ($this->uri->segment(2) == 'exp_profile_readonly_emp')? 'active':''; ?>"><a href="<?php echo base_url('my_account/exp_profile_readonly_emp'.$get_id);?>">Experience</a></li> 
			<li class="<?php echo ($this->uri->segment(2) == 'education_profile_readonly_emp')? 'active':''; ?>"><a href="<?php echo base_url('my_account/education_profile_readonly_emp'.$get_id);?>">Education</a></li> 
			<li class="<?php echo ($this->uri->segment(2) == 'family_profile_readonly_emp')? 'active':''; ?>"><a href="<?php echo base_url('my_account/family_profile_readonly_emp'.$get_id);?>">Family</a></li> 
			<li class="<?php echo ($this->uri->segment(2) == 'reference_readonly_emp')? 'active':''; ?>"><a href="<?php echo base_url('my_account/reference_readonly_emp'.$get_id);?>">Reference</a></li> 
			<li class="<?php echo ($this->uri->segment(2) == 'job_description_readonly_emp')? 'active':''; ?>"><a href="<?php echo base_url('my_account/job_description_readonly_emp'.$get_id);?>">JD/Goal</a></li> 
			<li class="<?php echo ($this->uri->segment(2) == 'document_readonly_emp')? 'active':''; ?>"><a href="<?php echo base_url('my_account/document_readonly_emp'.$get_id);?>">Document</a></li> 
		</ul>
	</div><!-- /.navbar-collapse -->
	</nav> 
</div> 

 