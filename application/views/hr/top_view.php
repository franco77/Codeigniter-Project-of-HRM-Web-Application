<?php
$get_id = "";
if (isset($_GET['id']))
{
	$get_id = "?id=".$_GET['id'];
}
echo '<input type="hidden" id="getURLIdVal" name="getURLIdVal" class="btn btn-sm btn-info" value="'.$get_id.'" />';
?>
<div class="row">
                     <nav class="skew-menu">
						<ul>
							<li class="<?php echo ($this->uri->segment(3) == 'general_readonly' || $this->uri->segment(3) == 'profile_update_emp')?'active':''; ?>"><a href="<?php echo base_url('en/hr/general_readonly'.$get_id);?>">General</a></li>
							
							
							<li class="<?php echo ($this->uri->segment(3) == 'comp_profile_readonly_emp' || $this->uri->segment(3) == 'comp_profile_update_emp')?'active':''; ?>"><a href="<?php echo base_url('en/hr/comp_profile_readonly_emp'.$get_id);?>">Company</a></li>  
							
							<li class="<?php echo ($this->uri->segment(3) == 'salary_profile_readonly_emp' || $this->uri->segment(3) == 'salary_profile_update_emp')?'active':''; ?>"><a href="<?php echo base_url('en/hr/salary_profile_readonly_emp'.$get_id);?>">Salary</a></li> 
							
							<li class="<?php echo ($this->uri->segment(3) == 'exp_profile_readonly_emp' || $this->uri->segment(3) == 'exp_update_emp')?'active':''; ?>"><a href="<?php echo base_url('en/hr/exp_profile_readonly_emp'.$get_id);?>">Experience</a></li> 
							
							<li class="<?php echo ($this->uri->segment(3) == 'education_profile_readonly_emp' || $this->uri->segment(3) == 'education_update_emp')?'active':''; ?>"><a href="<?php echo base_url('en/hr/education_profile_readonly_emp'.$get_id);?>">Education</a></li> 
							
							<li class="<?php echo ($this->uri->segment(3) == 'family_profile_readonly_emp' || $this->uri->segment(3) == 'family_update_emp')?'active':''; ?>"><a href="<?php echo base_url('en/hr/family_profile_readonly_emp'.$get_id);?>">Family</a></li> 
							
							<li class="<?php echo ($this->uri->segment(3) == 'reference_readonly_emp' || $this->uri->segment(3) == 'reference_update_emp')?'active':''; ?>"><a href="<?php echo base_url('en/hr/reference_readonly_emp'.$get_id);?>">Reference</a></li> 
							
							<li class="<?php echo ($this->uri->segment(3) == 'job_description_readonly_emp' || $this->uri->segment(3) == 'job_description_update_emp')?'active':''; ?>"><a href="<?php echo base_url('en/hr/job_description_readonly_emp'.$get_id);?>">JD/Goal</a></li> 
							
							<li class="<?php echo ($this->uri->segment(3) == 'document_readonly_emp' || $this->uri->segment(3) == 'document_update_emp')?'active':''; ?>"><a href="<?php echo base_url('en/hr/document_readonly_emp'.$get_id);?>">Document</a></li>
							
							<li class="<?php echo ($this->uri->segment(3) == 'letter_issued_readonly_emp' || $this->uri->segment(3) == 'letter_issued_update_emp')?'active':''; ?>"><a href="<?php echo base_url('en/hr/letter_issued_readonly_emp'.$get_id);?>">Letter Issued</a></li>
						</ul>
                    </nav>
                </div>