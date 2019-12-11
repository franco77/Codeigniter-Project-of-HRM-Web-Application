<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
.pknoborder tr td {
	border:none;
}
.pkhead2 {
	background: #e9e9e9;
    padding: 4px;
    font-size: 15px;
    color: #000;
    font-weight: bold;
    border-bottom: 1px solid #344470;
	margin-bottom:20px;
}
</style> 
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('accounts/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<legend class="pkheader">Create New Emplyee <small>(AABSyS Resources)</small></legend>
					<div class="well">  
						<form id="frmAddEmployee" name="frmAddEmployee" method="POST" action="<?php echo base_url('en/hr/add_employee_submit'); ?>" enctype="multipart/form-data">
							<div class="row">
								<?php /* if($this->input->post('btnCreateNewEmployeeCode') == 'Create'){?>
								<div class="employee_designation">
									<p>
										Generated Employee Code will be <strong><?php echo $employeeID;?></strong>.<br/>
										Do you want to proceed?
										<input type="button" id="btnCreateNewEmployeeCancel" name="btnCreateNewEmployeeCancel" class="btn btn-md btn-danger" value="No" onclick="<?php echo base_url()?>hr/add_employee" />
										<input type="submit" id="btnCreateNewEmployeeCode" name="btnCreateNewEmployeeCode" class="btn btn-md btn-info" value="Yes" />
									</p> 
								</div>
								<?php } */ ?> 
								<section>
									<div  class="col-md-12">
										<h4 class="pkhead2">GENERAL INFO</h4>
										<div class="col-md-6"> 
											<div class="form-group" style="height: 55px;">
												<div class="col-md-4" style="padding: 2px;">
													<label for="inputCity"><font style="color:#f00;">*</font>&nbsp;First</label>
													<input type="text" id="txtFirstName" name="txtFirstName" value="<?php echo $this->input->post('txtFirstName')?>" class="form-control" onkeyup="javascript:showFullName();" required>
												</div>
												<div class="col-md-4" style="padding: 2px;">
													<label for="inputCity">Middle</label>
													<input type="text" id="txtMiddleName" name="txtMiddleName" value="<?php echo $this->input->post('txtMiddleName')?>" class="form-control" onkeyup="javascript:showFullName();" >
												</div>
												<div class="col-md-4" style="padding: 2px;">
													<label for="inputCity"><font style="color:#f00;">*</font>&nbsp;Last</label>
													<input type="text" id="txtLastName" name="txtLastName" value="<?php echo $this->input->post('txtLastName')?>" class="form-control" onkeyup="javascript:showFullName();" required>
												</div> 
											</div>
											<div class="form-group">
												<label for="exampleSelect1">Gender</label>
												<select id="rdGender" name="rdGender" class="form-control">
													<option value="M" selected="selected">Male</option>
													<option value="F" <?php if($this->input->post('rdGender') == 'F') echo 'selected="selected"';?>>Female</option> 
												</select>
											</div> 
											<div class="form-group">
												<label for="inputCity">Contact Number</label>
												<input type="text" id="txtContNo" name="txtContNo" value="<?php echo $this->input->post('txtContNo')?>" class="form-control">
											</div> 
											<div class="form-group">
												<label for="inputCity"><font style="color:#f00;">*</font>&nbsp;Blood Group</label>
												<select name="txtBGroup" class="form-control" required>
													<option value="">Select</option>
													<option value="A+" <?php if($this->input->post('txtBGroup') == 'A+') echo 'selected="selected"';?>>A+</option>
													<option value="A-" <?php if($this->input->post('txtBGroup') == 'A-') echo 'selected="selected"';?>>A-</option>
													<option value="B+" <?php if($this->input->post('txtBGroup') == 'B+') echo 'selected="selected"';?>>B+</option>
													<option value="B-" <?php if($this->input->post('txtBGroup') == 'B-') echo 'selected="selected"';?>>B-</option>
													<option value="AB+" <?php if($this->input->post('txtBGroup') == 'AB+') echo 'selected="selected"';?>>AB+</option>
													<option value="AB-" <?php if($this->input->post('txtBGroup') == 'AB-') echo 'selected="selected"';?>>AB-</option>
													<option value="O+" <?php if($this->input->post('txtBGroup') == 'O+') echo 'selected="selected"';?>>O+</option>
													<option value="O-" <?php if($this->input->post('txtBGroup') == 'O-') echo 'selected="selected"';?>>O-</option>
												</select>
											</div>
											<div class="form-group">
												<label for="exampleSelect1"><font style="color:#f00;">*</font>&nbsp;Date of Birth</label>
												<input type="text" id="txtdob" name="txtdob" value="<?php echo $this->input->post('txtdob')?>" class="form-control" required><i class="fa fa-calendar" aria-hidden="true" style="margin-right: 8px; margin-top: -28px;"></i>
											</div>  
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputCity">Full Name</label>
												<input type="text" id="txtFullName" name="txtFullName" value="<?php echo $this->input->post('txtFullName')?>" readonly="readonly" class="form-control">
											</div> 
											<div class="form-group">
												<label for="exampleSelect1">Marital Status</label>
												<select id="rdMStatus" name="rdMStatus" class="form-control">
													<option value="S" selected="selected">Single</option>
													<option value="M" <?php if($this->input->post('rdMStatus') == 'M') echo 'selected="selected"';?>>Married</option>
												</select>
											</div> 
											<div class="form-group">
												<label for="inputCity">Em. Contact Number</label>
												<input type="text" id="txtEContNo" name="txtEContNo" value="<?php echo $this->input->post('txtEContNo')?>" class="form-control">
											</div> 
											<div class="form-group">
												<label for="inputCity">Personal E-Mail</label>
												<input type="text" id="txtPEmailID" name="txtPEmailID" value="<?php echo $this->input->post('txtPEmailID')?>" class="form-control">
											</div>
										</div> 
									</div>
									<div  class="col-md-12">
										<h4 class="pkhead2">QUALIFICATION INFO</h4>
										<div class="col-md-6"> 
											<div class="form-group">
												<label for="exampleSelect1">Highest Qualification</label>
												<select id="selHgstEdu" name="selHgstEdu" class="form-control">
													<option>Select</option>
													<?php 
														$qualification_result = count($qualification);
														for($i=0; $i<$qualification_result; $i++)
														{?>
															<option value="<?php echo $qualification[$i]['course_id'];?>"><?php echo $qualification[$i]['course_name'];?></option>
														<?php }
													?>
												</select>
											</div> 
										</div>
										<div class="col-md-6"> 
											<div class="form-group">
												<label for="exampleSelect1">Location of Highest Qualification</label>
												<select id="perLoc" name="perLoc" class="form-control">
													<option>Select</option>
													<?php 
														$qualification_result = count($location_of_highest_qualification);
														for($i=0; $i<$qualification_result; $i++)
														{?>
															<option value="<?php echo $location_of_highest_qualification[$i]['state_id'];?>"><?php echo $location_of_highest_qualification[$i]['state_name'];?></option>
														<?php }
													?>
												</select>
											</div> 
										</div> 
									</div>
									<div  class="col-md-12">
										<h4 class="pkhead2">COMPANY INFO</h4>
										<div class="col-md-6"> 
											<div class="form-group">
												<label for="exampleSelect1"><font style="color:#f00;">*</font>&nbsp;Employee Type</label>
												<select id="ddlTypeEmp" name="ddlTypeEmp" class="form-control" required>
													<option value="F" selected="selected">Full Time</option>
													<option value="C" <?php if($this->input->post('ddlTypeEmp') == 'C') echo 'selected="selected"';?>>Contractual</option>
													<option value="I" <?php if($this->input->post('ddlTypeEmp') == 'I') echo 'selected="selected"';?>>Interns</option>
												</select>
											</div> 
											<div class="form-group">
												<label for="inputCity">Level</label>
												<select id="level" name="level" class="form-control">
													<option>Select</option>
													<?php 
														$level_result = count($level);
														for($i=0; $i < $level_result; $i++)
														{?>
															<option value="<?php echo $level[$i]['level_id']?>"><?php echo $level[$i]['level_name'];?></option>
														<?php }
													?>
												</select>
											</div>
											<div class="form-group">
												<label for="inputCity"><font style="color:#f00;">*</font>&nbsp;Department</label>
												<select id="department" name="department" class="form-control" onchange="getDesgnation(this)" required>
												<option value="">Select</option>
												<?php 
													$department_result = count($department);
													for($i=0; $i<$department_result; $i++)
													{?>
														<option value="<?php echo $department[$i]['dept_id'];?>"><?php echo $department[$i]['dept_name'];?></option>
													<?php }
												?>
												</select>
											</div>
											<div class="form-group">
												<label for="exampleSelect1"><font style="color:#f00;">*</font>&nbsp;Date Of Joining</label>
												<input type="text" id="txtdoj" name="txtdoj" value="<?php echo $this->input->post('txtdoj')?>" class="form-control" required><i class="fa fa-calendar" aria-hidden="true" style="margin-right: 8px; margin-top: -28px;"></i>
											</div>
											<div class="form-group">
												<label for="inputCity">Perfom. Eligibility</label>
												<select id="perofmEligb" name="perofmEligb" class="form-control">
													<option value="N" <?php if($this->input->post('perofmEligb') == 'N') echo 'selected="selected"';?>>No</option>
													<option value="Y" <?php if($this->input->post('perofmEligb') == 'Y') echo 'selected="selected"';?>>Yes</option>
												</select>
											</div>
											<div class="form-group">
												<label for="inputCity" id="confirmText">Probation Period</label>
												<div id="div_dueConf">
													<select name="due_conf" class="form-control">
														<option value="3" <?php if($this->input->post('due_conf') == '3') echo 'selected="selected"';?>>3 Month</option>
														<option value="6" <?php if($this->input->post('due_conf') == '6') echo 'selected="selected"';?>>6 Month</option>
														<option value="12" <?php if($this->input->post('due_conf') == '12') echo 'selected="selected"';?>>1 Year</option>
													</select>
												</div>
												<div id="div_contractEndDate" class="hide">
													<select name="contract_EndDate"  class="form-control" required>
													<?php
															for($i = 1; $i <= 12; $i++){
																if($contract_EndDate == $i)
																{
																		echo "<option value='$i' selected='selected'>$i Month</option>";
																}
																else
																{
																		echo "<option value='$i'>$i Month</option>";
																}
															}
													?>
													</select>
												</div>
											</div> 
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="exampleSelect1"><font style="color:#f00;">*</font>&nbsp;Location</label>
												<select id="branch" name="branch" class="form-control" required>
													<option>Select</option>
													<?php 
														$location_result = count($location);
														for($i=0; $i<$location_result; $i++)
														{?>
															<option value="<?php echo $location[$i]['branch_id'];?>"><?php echo $location[$i]['branch_name'];?></option>
														<?php }
													?>
												</select>
											</div> 
											<div class="form-group">
												<label for="inputCity">Grade</label>
												<select id="grade" name="grade" class="form-control">
													<option>Select</option>
													<?php 
														$grade_result = count($grade);
														for($i=0; $i<$grade_result; $i++)
														{?>
															<option value="<?php echo $grade[$i]['grade_id'];?>"><?php echo $grade[$i]['grade_name'];?></option>
														<?php }
													?>
												</select>
											</div>
											<div class="form-group">
												<label for="inputCity"><font style="color:#f00;">*</font>&nbsp;Designation</label>
												<select id="designation" name="designation" class="form-control" required>
													<option>Select</option>
													<?php 
														$designation_result = count($designation);
														for($i=0; $i<$designation_result; $i++)
														{?>
															<option value="<?php echo $designation[$i]['desg_id'];?>"><?php echo $designation[$i]['desg_name'];?></option>
														<?php }
													?>
												</select>
											</div>
											<!--<div class="form-group">
												<label for="exampleSelect1"><font style="color:#f00;">*</font>&nbsp;Reporting</label>
												<input type="text" id="reportingAC" name="reportingAC" value="<?php echo $this->input->post('reporting');?>" autocomplete="off" class="form-control" required/>
												<input type="hidden" id="reporting" name="reporting" value="<?php echo $this->input->post('reporting');?>" class="required" />
											</div>-->
											<div class="form-group">
												<label for="exampleSelect1"><font style="color:#f00;">*</font>&nbsp;Reporting</label>
												<select id="reporting" name="reporting" class="form-control" required>
												<?php 
													$reporting_result = count($reporting);
													for($i=0; $i<$reporting_result; $i++)
													{?>
														<option value="<?php echo $reporting[$i]['login_id'];?>"><?php echo $reporting[$i]['dispName'];?></option>
													<?php }
												?>
												</select>
											</div>
											<div class="form-group">
												<label for="inputCity">Attnd. Eligibility</label>
												<select id="attndEligb" name="attndEligb" class="form-control">
													<option value="N" <?php if($this->input->post('attndEligb') == 'N') echo 'selected="selected"';?>>No</option>
													<option value="Y" <?php if($this->input->post('attndEligb') == 'Y') echo 'selected="selected"';?>>Yes</option>
												</select>
											</div>
											<div class="form-group">
												<label for="inputCity"><font style="color:#f00;">*</font>&nbsp;Source of Hire</label>
												<select name="ddlSrcHire" class="form-control" required>
													<option>Select</option>
													<?php 
														$source_result = count($sourcehire);
														for($i=0; $i<$source_result; $i++)
														{?>
															<option value="<?php echo $sourcehire[$i]['source_hire_name'];?>"><?php echo $sourcehire[$i]['source_hire_name'];?></option>
														<?php }
													?>
												</select>
											</div>
										</div> 
									</div>
									<div  class="col-md-12">
										<h4 class="pkhead2">Permanent Address</h4>
										<div class="col-md-6"> 
											<div class="form-group">
												<label for="inputCity"><font style="color:#f00;">*</font>&nbsp;Address</label> 
												<textarea id="perAddr" name="perAddr" class="required form-control" required><?php echo $this->input->post('perAddr')?></textarea>
											</div> 
											<div class="form-group">
												<label for="inputCity"><font style="color:#f00;">*</font>&nbsp;City / District</label>
												<input type="text" id="perDist" name="perDist" value="<?php echo $this->input->post('perDist')?>" class="form-control" required>
											</div> 
											<div class="form-group">
												<label for="exampleSelect1"><font style="color:#f00;">*</font>&nbsp;Country</label>
												<select id="perCountry" name="perCountry" class="form-control">
													<option>Select</option>
													<?php 
														$country_result = count($country);
														for($i=0; $i < $country_result; $i++)
														{?>
															<option value="<?php echo $country[$i]['country_id'];?>"><?php echo $country[$i]['country_name'];?></option>
														<?php }
													?>
												</select>
											</div>  
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputCity"><font style="color:#f00;">*</font>&nbsp;State / Region</label> 
												<select id="perState" name="perState" class="required  form-control">
													<option>Select</option>
													<?php 
														$state_result = count($state);
														for($i=0; $i < $state_result; $i++)
														{?>
															<option value="<?php echo $state[$i]['state_id'];?>"><?php echo $state[$i]['state_name'];?></option>
														<?php }
													?>
												</select>
											</div>
											<div class="form-group">
												<label for="exampleSelect1"><font style="color:#f00;">*</font>&nbsp;Pin / Zip</label>
												<input type="text" id="perPin" name="perPin" value="<?php echo $this->input->post('perPin')?>" class="form-control" required/> 
											</div> 
										</div> 
									</div> 
								</section>
							</div> 
							<div class="row">
								<input type="submit" id="btnCreateNewEmployeeCode" name="btnCreateNewEmployeeCode" class="btn btn-info pull-right" value="Create" /> 
							</div>
							<div class="clearfix"></div>
						</form>
					</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>
<script>
 var site_url = '<?php echo base_url(); ?>';
$(document).ready(function(){
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});
});

function getDesgnation(dis){
	var department = $('#department').val();
	var str="";
	$.ajax({
		type: "POST",
		url: site_url+'my_account/get_designation',
		data: {department : department},
		success: function(response)
		{
			response = JSON.parse(response);
			str += '<option value="">Select</option>';
			for(var i=0; i< response.length; i++){
				str += '<option value="'+response[i].desg_id+'">'+response[i].desg_name+'</option>';
			}
			$('#designation').html(str);
	   }
	});
}
</script>