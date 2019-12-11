<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php
$get_id = "";
$get_ids = "";
if (isset($_GET['id']))
{
	$get_ids = $get_ids."?id=".$_GET['id'];
	$get_id = $_GET['id'];
}
$get_mid = "";
if (isset($_GET['mid']))
{
	$get_ids = $get_ids."&mid=".$_GET['mid'];
	$get_mid = $_GET['mid'];
}
echo '<input type="hidden" id="getURLIdVal" name="getURLIdVal" class="btn btn-sm btn-info" value="'.$get_ids.'" />';
?> 
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content ">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9  center-xs">
					<div class="form-content page-content">
						<legend class="pkheader">Online Manpower Requisition Form</legend>
						<div class="row well">
							<form id="frmSalaryUpdate" name="frmSalaryUpdate" method="POST" action="" enctype="multipart/form-data">
                                <div class="form">
                                 <div class="form1">
                                   <table width="100%" border="0" cellpadding="10" cellspacing="10" id="dataTable" class="table table-striped table-bordered table-condensed" style="border: 1px solid #ccc;">
                                        <tr><td>&nbsp;</td></tr>                                        
                                      
                                         <tr>
                                        	<td><strong>Department</strong><span class="red">*</span> </td>
                                                <td width="10%">:</td>
                                            <td>
                                            	 <select id="department" name="department" class="required form-control" style="width:200px;" onchange="getDesgnation(this)" required="" >
                                                        <option value="">Select</option>
                                                        <?php 
														for($l=0; $l < count($department); $l++) 
														{?>
															<option value="<?php echo $department[$l]['dept_id']; ?>" <?php if(count($rowMRF)>0){if($department[$l]['dept_id'] == $rowMRF[0]['department']){ echo "selected"; }}?> ><?php echo $department[$l]['dept_name']; ?></option>	
														<?php } ?>
                                                 </select>
                                            </td>
                                             </tr>
                                        <tr>
                                            <td><strong>Position</strong><span class="red">*</span> </td>
                                            <td width="10%">:</td>
                                            <td id="desg_name">
                                            	<select id="designation" name="designation" class="required form-control" style="width:200px;"  required=""  >
                                                        <option value="">Select</option>
                                                        <?php 
														for($l=0; $l < count($designation); $l++) 
														{?>
															<option value="<?php echo $designation[$l]['desg_id']; ?>" <?php if(count($rowMRF)>0){if($designation[$l]['desg_id'] == $rowMRF[0]['designation']){ echo "selected"; }}?> ><?php echo $designation[$l]['desg_name']; ?></option>	
														<?php } ?>
                                              </select>
                                            </td>
                                            </tr>
                                        <tr>
                                            <td valign="top"> <strong>Location </strong> <span class="red">*</span> </td>
                                            <td width="10%">:</td>
                                            <td valign="top">
                                                <select id="branch" name="branch" class="required  form-control" style="width: 200px;"  required="" >
                                                    <?php 
													for($l=0; $l < count($branchInfo); $l++) 
													{?>
														<option value="<?php echo $branchInfo[$l]['branch_id']; ?>" <?php if(count($rowMRF)>0){if($branchInfo[$l]['branch_id'] == $rowMRF[0]['branch']){ echo "selected"; }}?> ><?php echo $branchInfo[$l]['branch_name']; ?></option>	
													<?php } ?>
                                                </select>                                    
                                                </td>
                                        </tr>
                                          <tr>
                                        	<td valign="top"> <strong>Reason for Recruitment</strong> <span class="red">*</span> </td>
                                                <td width="10%">:</td>
                                            <td valign="top">
                                                <select id="reason_recruitment" name="reason_recruitment" class="required form-control" style="width:200px;"  required="" >
                                                    <option value="New Position" <?php if(count($rowMRF)>0){ if($rowMRF[0]['reason_recruitment']=='New Position') { echo "selected"; } } ?>>New Position</option>
                                                    <option value="Replacement" <?php  if(count($rowMRF)>0){ if($rowMRF[0]['reason_recruitment']=='Replacement')echo "selected"; } ?>>Replacement</option>
                                                    <option value="Trainee" <?php  if(count($rowMRF)>0){ if($rowMRF[0]['reason_recruitment']=='Trainee')echo "selected"; } ?>>Trainee</option>
                                                </select>
                                          </td>                                            
                                        </tr> 
                                        <tr>
                                            <td valign="top"> <strong>No. of Vacancies </strong><span class="red">*</span> </td>
                                            <td width="10%">:</td>
                                            <td valign="top">
                                                <input type="text"  id="no_vacancies" name="no_vacancies" value="<?php  if(count($rowMRF)>0){ echo $rowMRF[0]['no_vacancies']; } ?>" class="required form-control" style="width:190px; margin-bottom: 10px;"  required=""  />                                           
                                                </td>
                                        </tr>
                                         <tr>
                                        	<td valign="top"> <strong>Justification</strong> (e.g. Project name)<span class="red">*</span></td>
                                                <td width="10%">:</td>
                                            <td valign="top">
                                                 <textarea name="justification" class="required form-control" rows="3"  style="width: 300px;" id="justification"  required="" ><?php  if(count($rowMRF)>0){ echo $rowMRF[0]['justification']; } ?></textarea></td>  
                                          </td>                                            
                                        </tr>  
                                         <tr>
                                            <td valign="top"> <strong>Job Description </strong> <span class="red">*</span></td>
                                            <td width="10%">:</td>
                                            <td valign="top">
                                               <textarea name="job_description" class="required form-control" rows="3"  style="width: 300px;" id="job_description"  required="" ><?php  if(count($rowMRF)>0){ echo $rowMRF[0]['job_description']; } ?></textarea></td>                                           
                                                </td>
                                        </tr>
                                        <tr><td colspan="3" class="info" align="center" ><strong>Job Specification</strong></td></tr>
                                       
                                        <tr><td colspan="3" class="info"><strong>Essential</strong></td></tr>                                         
                                         <tr>
                                        	<td valign="top"> <strong>Qualification</strong><span class="red">*</span></td>
                                                <td width="10%">:</td>
                                            <td valign="top">
                                                 <input type="text"  id="essential_qualification" value="<?php  if(count($rowMRF)>0){  echo $rowMRF[0]['essential_qualification']; } ?>" name="essential_qualification" class="required form-control" style="width:190px; margin-bottom: 10px;"  required="" />
                                          </td>                                            
                                        </tr>  
                                         <tr>
                                            <td valign="top"> <strong>Length of Experience </strong><span class="red">*</span> </td>
                                            <td width="10%">:</td>
                                            <td valign="top"><textarea name="essential_length_experience" class="required form-control" rows="3"  style="width: 300px;" id="essential_length_experience" required="" ><?php  if(count($rowMRF)>0){ echo $rowMRF[0]['essential_length_experience']; } ?></textarea>
                                                                                           
                                                </td>
                                        </tr>
                                         <tr>
                                            <td valign="top"> <strong>Kind of Experience </strong><span class="red">*</span> </td>
                                            <td width="10%">:</td>
                                            <td valign="top"><textarea name="essential_kind_experience" class="required form-control" rows="3" cols="20"  style="width: 300px;" id="essential_kind_experience" required="" ><?php  if(count($rowMRF)>0){ echo $rowMRF[0]['essential_kind_experience']; } ?></textarea>
                                                                                           
                                                </td>
                                        </tr>
                                         <tr>
                                        	<td valign="top"> <strong>Any Other</strong></td>
                                                <td width="10%">:</td>
                                            <td valign="top"><textarea name="essential_other" class="required form-control" rows="3" cols="10"  style="width: 300px;" id="essential_other"><?php  if(count($rowMRF)>0){ echo $rowMRF[0]['essential_other']; } ?></textarea>
                                                 
                                          </td>                                            
                                        </tr>  
                                        <tr><td colspan="3" class="info" ><strong>Desirable</strong></td></tr>                                         
                                         <tr>
                                        	<td valign="top"> <strong>Qualification</strong></td>
                                                <td width="10%">:</td>
                                            <td valign="top">
                                                 <input type="text"  id="desirable_qualification" name="desirable_qualification" value="<?php  if(count($rowMRF)>0){ echo $rowMRF[0]['desirable_qualification']; } ?>" class="form-control" style="width:190px; margin-bottom: 10px;" />
                                          </td>                                            
                                        </tr>  
                                         <tr>
                                            <td valign="top"> <strong>Length of Experience </strong> </td>
                                            <td width="10%">:</td>
                                            <td valign="top"><textarea name="desirable_length_experience" class="form-control" rows="3" cols="20"  style="width: 300px;" id="desirable_length_experience"><?php  if(count($rowMRF)>0){ echo $rowMRF[0]['desirable_length_experience']; } ?></textarea>
                                                              
                                                </td>
                                        </tr>
                                         <tr>
                                            <td valign="top"> <strong>Kind of Experience </strong> </td>
                                            <td width="10%">:</td>
                                            <td valign="top"><textarea name="desirable_kind_experience" class="form-control" rows="3" cols="20"  style="width: 300px;" id="desirable_kind_experience"><?php  if(count($rowMRF)>0){ echo $rowMRF[0]['desirable_kind_experience']; } ?></textarea>
                                                                
                                                </td>
                                        </tr>
                                         <tr>
                                        	<td valign="top"> <strong>Any Other</strong></td>
                                                <td width="10%">:</td>
                                            <td valign="top"><textarea name="desirable_other" class="form-control" rows="3" cols="20"  style="width: 300px;" id="desirable_other"><?php  if(count($rowMRF)>0){ echo $rowMRF[0]['desirable_other']; } ?></textarea>
                                                
                                          </td>                                            
                                        </tr> 
                                        <tr>
                                            <td valign="top"> <strong>Time Period within which this requirement need to be fulfilled. </strong> </td>
                                            <td width="10%">:</td>
                                            <td valign="top">
                                                <input type="text" id="time_period" name="time_period" value="<?php  if(count($rowMRF)>0){ echo date('d-m-Y', strtotime($rowMRF[0]['time_period'])); } ?>" class="required form-control datepickerShow" style="width:190px; margin-bottom: 10px;" />                                           
                                                </td>
                                        </tr>
					<tr><td>&nbsp;</td></tr>	
							
						</table>
                                    </div>
                                </div>
                                <div class="link_section" style="float:right;">								 
                                    <input type="submit" id="btnAddMessage" name="btnAddMessage" class="btn btn-info" value="APPLY" />
                                    <input type="hidden" name="mid" id="mid" value="<?php echo $get_mid; ?>" />
                                    <div class="clear"></div>
                                </div>
                                </form> 
						</div>
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
    </div>
</div>

