<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php
$get_id = "";
if (isset($_GET['id']))
{
	$get_id = "?id=".$_GET['id'];
}
echo '<input type="hidden" id="getURLIdVal" name="getURLIdVal" class="btn btn-sm btn-info" value="'.$get_id.'" />';
?>
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
				<?php $this->load->view('accounts/top_view');?>
					<?php if($this->session->userdata('user_type') != 'EMP' ){ ?>
						<a href="<?= base_url('en/accounts_admin/comp_profile_readonly_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;View</a> 
					<?php } ?>
					<legend class="pkheader">Company</legend>
					<div class="row well">
						<div class="table-responsive">
							<form id="frmCompanyUpdate" name="frmCompanyUpdate" method="POST" action="<?= base_url('en/accounts_admin/comp_profile_update_emp'.$get_id);?>" enctype="multipart/form-data"> 
							<table class="table table-striped table-bordered table-condensed"> 
								<tbody>
									<tr>
                                        	<td><span class="red">*</span> <strong>Department:</strong></td>
                                            <td>
                                            	 <select id="department" name="department" class="required form-control" style="width:200px;" onchange="getDesgnation(this)">
                                                    <option value="">Select</option>
													<?php 
													for($l=0; $l < count($department); $l++) 
													{?>
														<option value="<?php echo $department[$l]['dept_id']; ?>" <?php if($department[$l]['dept_id'] == $empInfo[0]['department']){ echo "selected"; }?> ><?php echo $department[$l]['dept_name']; ?></option>	
													<?php } ?>
                                                 </select>
                                            </td>
                                            <td><span class="red">*</span> <strong>Designation:</strong></td>
                                            <td id="desg_name">
                                            	<select id="designation" name="designation" class="required form-control" style="width:200px;">
                                                    <option value="">Select</option>
													<?php 
													for($l=0; $l < count($desInfo); $l++) 
													{?>
														<option value="<?php echo $desInfo[$l]['desg_id']; ?>" <?php if($desInfo[$l]['desg_id'] == $empInfo[0]['designation']){ echo "selected"; }?> ><?php echo $desInfo[$l]['desg_name']; ?></option>	
													<?php } ?>
                                              </select>
                                            </td>
                                            </tr>
                                            
                                        <tr>
                                            <td><span class="red">&nbsp;</span><strong> Level:</strong></td>
                                            <td>
                                                <select id="level" name="level" class="form-control" style="width:200px;">
                                                    <option value="">Select</option>
													<?php 
													for($l=0; $l < count($levelInfo); $l++) 
													{?>
														<option value="<?php echo $levelInfo[$l]['level_id']; ?>" <?php if($levelInfo[$l]['level_id'] == $empInfo[0]['level']){ echo "selected"; }?> ><?php echo $levelInfo[$l]['level_name']; ?></option>	
													<?php } ?>
                                                    
                                              </select>
                                            </td>
                                            <td><span class="red">&nbsp;</span><strong> Grade:</strong></td>
                                            <td id="grade_name">
                                                <select id="grade" name="grade" class="form-control" style="width:200px;">
                                                    <option value="">Select</option>
                                                    <?php 
													for($l=0; $l < count($gradeInfo); $l++) 
													{?>
														<option value="<?php echo $gradeInfo[$l]['grade_id']; ?>" <?php if($gradeInfo[$l]['grade_id'] == $empInfo[0]['grade']){ echo "selected"; }?> ><?php echo $gradeInfo[$l]['grade_name']; ?></option>	
													<?php } ?>
                                              </select>
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td><span class="red">*</span> <strong>DOJ:</strong></td>
                                            <td>
                                            	<input type="text" id="txtdoj" name="txtdoj" value="<?php echo date("d-m-Y", strtotime($empInfo[0]['join_date']))?>" class="datepickerShow required form-control" style="width:190px;" />
                                            </td>
                                            <td><span class="red">*</span><strong>Location:</strong></td>
                                            <td>
                                                <select id="branch" name="branch" class="required  form-control" style="width: 200px;">
                                                    <?php 
													for($l=0; $l < count($branchInfo); $l++) 
													{?>
														<option value="<?php echo $branchInfo[$l]['branch_id']; ?>" <?php if($branchInfo[$l]['branch_id'] == $empInfo[0]['branch']){ echo "selected"; }?> ><?php echo $branchInfo[$l]['branch_name']; ?></option>	
													<?php } ?>
                                                </select>
                                             </td>
                                        </tr>
                                        <tr>
                                        	<td><strong>Corporate Email:</strong></td>
                                            <td><input type="text" id="txtemail" name="txtemail" value="<?php echo $empInfo[0]['email'];?>" class="form-control" style="width:190px;" /></td>
                                            <td><strong>Skype ID:</strong></td>
                                            <td><input type="text" id="txtSkype" name="txtSkype" value="<?php echo $empInfo[0]['skype'];?>" class="form-control" style="width:190px;" /></td>
                                        </tr>
                                        <tr>
                                        	<td><span class="red">*</span><strong>Perfom. Eligibility:</strong></td>
                                            <td>
                                            <select id="perofmEligb" name="perofmEligb" class="required form-control" style="width:200px;">
												  <option value="N" <?php if($empInfo[0]['isPerfomAllowance'] == 'N') echo 'selected="selected"';?>>No</option>
												  <option value="Y" <?php if($empInfo[0]['isPerfomAllowance'] == 'Y') echo 'selected="selected"';?>>Yes</option>
											</select>
                                            </td>
                                            <td><span class="red">*</span><strong>Attnd. Eligibility:</strong></td>
                                            <td>
                                            <select id="attndEligb" name="attndEligb" class="required form-control" style="width:200px;">
												  <option value="N" <?php if($empInfo[0]['isAttndAllowance'] == 'N') echo 'selected="selected"';?>>No</option>
												  <option value="Y" <?php if($empInfo[0]['isAttndAllowance'] == 'Y') echo 'selected="selected"';?>>Yes</option>
											</select>
                                            </td>
                                        </tr>
                                        <tr>
                                        	<td><span class="red">*</span><strong>Reporting:</strong></td>
                                            <td>
	                                            <input type="text" id="reportingAC" value="<?php echo $empInfo[0]['rmName'].' ('.$empInfo[0]['rmECode'].')'?>" class="form-control" style="width:190px;" readonly />
												<input type="hidden" id="reporting" name="reporting" value="<?php echo $empInfo[0]['reporting_to'];?>" class="required" />
                                            </td>	
											<?php if($empInfo[0]['emp_type'] == 'F') { ?>
													<td><span class="red">*</span><strong> Confirmation:</strong></td>
											<?php }else {?>
													<td><strong>Contract end date:</strong></td>
											<?php }?>
											<td>
													<input type="text" id="txtConfm" name="txtConfm" value="<?php if($empInfo[0]['employee_conform'] != NULL && $empInfo[0]['employee_conform'] != '0000-00-00') { echo date("d-m-Y", strtotime($empInfo[0]['employee_conform']));} else{ echo "";} ?>" class="datepickerShow required form-control" style="width:190px;" />
											</td>
                                        </tr>
                                        <tr>
                                        <td><span class="red">*</span><strong>Source of Hire:</strong></td>
                                        <td>
                                            <select name="ddlSrcHire"  class="required form-control" style="width:200px;">
                                                <option value="">Select</option>
												<?php 
												for($l=0; $l < count($source_hireInfo); $l++) 
												{?>
													<option value="<?php echo $source_hireInfo[$l]['source_hire_id']; ?>" <?php if($source_hireInfo[$l]['source_hire_id'] == $empInfo[0]['source_hire']){ echo "selected"; }?> ><?php echo $source_hireInfo[$l]['source_hire_name']; ?></option>	
												<?php } ?>
                                            </select>
                                        </td>
                                        <td></td>
                                        <td>
                                            <?php if($empInfo[0]['emp_type'] == 'F') { ?>
                                                    <input type="radio" id="rbConfm" name="rbConfm"  value="Confirmed"  <?php if($empInfo[0]['confirm_status'] == 'Confirmed') echo 'checked="checked"';?> /><strong>Confirmed</strong>
                                                    <input type="radio" id="rbConfm" name="rbConfm"  value="Not Confirmed"  <?php if($empInfo[0]['confirm_status'] == 'Not Confirmed') echo 'checked="checked"';?> /><strong>Not Confirmed</strong>
                                            <?php }else {?>
                                                    <input type="radio" id="rbConfm" name="rbConfm"  value="Contract Extended"  <?php if($empInfo[0]['confirm_status'] == 'Contract Extended') echo 'checked="checked"';?> /><strong>Contract Extended<br/></strong>
                                                    <input type="radio" id="rbConfm" name="rbConfm"  value="Contract Terminated"  <?php if($empInfo[0]['confirm_status'] == 'Contract Terminated') echo 'checked="checked"';?> /><strong>Contract Terminated</strong>
                                            <?php }?>
                                        </td>
                                        </tr>
                                        <tr>
                                        	<td><strong>Remote access:</strong></td>
                                            <td>
                                            	<input type="radio" id="rbRemote" name="rbRemote"  value="Y"  <?php if($empInfo[0]['remote_access'] == 'Y') echo 'checked="checked"';?> /><strong>Yes</strong>
												<input type="radio" id="rbRemote" name="rbRemote"  value="N"  <?php if($empInfo[0]['remote_access'] == 'N') echo 'checked="checked"';?> /><strong>No</strong>
                                            </td>
                                            <td><strong>Offer Letter Issued:</strong></td>
                                            <td>
                                            	<input type="radio" id="rboffer_letter_issued" name="rboffer_letter_issued"  value="Y"  <?php if($empInfo[0]['offer_letter_issued'] == 'Y') echo 'checked="checked"';?> /><strong>Yes</strong>
												<input type="radio" id="rboffer_letter_issued" name="rboffer_letter_issued"  value="N"  <?php if($empInfo[0]['offer_letter_issued'] == 'N') echo 'checked="checked"';?> /><strong>No</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                        	<td><strong>Appointment Letter Issued:</strong></td>
                                            <td>
                                            	<input type="radio" id="rbappoint_letter_issued" name="rbappoint_letter_issued"  value="Y"  <?php if($empInfo[0]['appoint_letter_issued'] == 'Y') echo 'checked="checked"';?> /><strong>Yes</strong>
												<input type="radio" id="rbappoint_letter_issued" name="rbappoint_letter_issued"  value="N"  <?php if($empInfo[0]['appoint_letter_issued'] == 'N') echo 'checked="checked"';?> /><strong>No</strong>
                                            </td>
                                            <td><strong>Confirmation Letter Issued:</strong></td>
                                            <td>
                                            	<input type="radio" id="rbconf_letter_issued" name="rbconf_letter_issued"  value="Y"  <?php if($empInfo[0]['conf_letter_issued'] == 'Y') echo 'checked="checked"';?> /><strong>Yes</strong>
												<input type="radio" id="rbconf_letter_issued" name="rbconf_letter_issued"  value="N"  <?php if($empInfo[0]['conf_letter_issued'] == 'N') echo 'checked="checked"';?> /><strong>No</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Resignation Date:</strong></td>
                                            <td><input type="text" id="resign_date" name="resign_date" value="<?php echo ($empInfo[0]['resign_date'] !="" && $empInfo[0]['resign_date'] !="0000-00-00")? date("d-m-Y", strtotime($empInfo[0]['resign_date'])) : "" ;?>" class="datepickerShow form-control" style="width:190px;" /></td>	                                            
                                            <td><strong>Last Working date:</strong></td>
                                            <td>
                                                <input type="text" id="lwd_date" name="lwd_date" value="<?php echo ($empInfo[0]['lwd_date'] !="" && $empInfo[0]['lwd_date'] !="0000-00-00")? date("d-m-Y", strtotime($empInfo[0]['lwd_date'])) : "" ;?>" class="datepickerShow form-control" style="width:190px;" />
                                            </td>																										
                                        </tr>
                                         <tr>
                                            <td><strong>Emp Status Type:</strong></td>
                                            <td><select id="emp_status_type" name="emp_status_type" class="required form-control" style="width:200px;">
                                                          <option value="Normal" <?php if($empInfo[0]['emp_status_type'] == 'Normal') echo 'selected="selected"';?>>Normal</option>                                                                                                                 
                                                          <option value="Resigned" <?php if($empInfo[0]['emp_status_type'] == 'Resigned') echo 'selected="selected"';?>>Resigned</option>
                                                            <option value="Retired" <?php if($empInfo[0]['emp_status_type'] == 'Retired') echo 'selected="selected"';?>>Retired</option>
                                                          <option value="Terminated" <?php if($empInfo[0]['emp_status_type'] == 'Terminated') echo 'selected="selected"';?>>Terminated</option> 
														  <option value="Transferred" <?php if($empInfo[0]['emp_status_type'] == 'Transferred') echo 'selected="selected"';?>>Transferred</option>														  
                                                </select></td>	                                            
                                            <td><strong>FnF Status:</strong></td>
                                            <td><select id="FnF_status" name="FnF_status" class="required form-control" style="width:200px;">
                                                          <option value="Pending" <?php if($empInfo[0]['FnF_status'] == 'Pending') echo 'selected="selected"';?>>Pending</option>
                                                          <option value="Cleared" <?php if($empInfo[0]['FnF_status'] == 'Cleared') echo 'selected="selected"';?>>Cleared</option>
                                                </select>
                                            </td>																										
                                         </tr>   
                                           <tr>
                                        	<td><strong>Last Promotion:</strong></td>
                                            <td>
                                                <input type="text" id="txtlast_promotion" name="txtlast_promotion" value="<?php echo ($empInfo[0]['last_promotion'] !="" && $empInfo[0]['last_promotion'] !="0000-00-00")? date("d-m-Y", strtotime($empInfo[0]['last_promotion'])) : "" ;?>" class="datepickerShow form-control" style="width:190px;" />
                                            </td>
                                            <td><strong>Misconduct Issues :</strong></td>
                                            <td>
                                            	<input type="text" id="txtmiscunduct_issue"  name="txtmiscunduct_issue" value="<?php echo $empInfo[0]['miscunduct_issue'];?>" class="form-control" style="width:190px;" />
                                            </td>
                                        </tr>
                                          <tr>
                                        	<td><strong>F &amp; F Date:</strong></td>
                                            <td>
                                            	<input type="text" id="txtff_date" name="txtff_date" value="<?php echo ($empInfo[0]['ff_date'] !="" && $empInfo[0]['ff_date'] !="0000-00-00")? date("d-m-Y", strtotime($empInfo[0]['ff_date'])) : "" ;?>" class="datepickerShow form-control" style="width:190px;" />
                                            </td>
                                            <td><strong>F &amp; F Amount:</strong></td>
                                            <td>
                                            	<input type="text" id="txtff_amount" name="txtff_amount" value="<?php echo ($empInfo[0]['ff_amount'] > 0)?$empInfo[0]['ff_amount']:'';?>" class="number form-control" style="width:190px;" />
                                            </td>
                                        </tr>
                                          <tr>
                                              <td><strong>F &amp; F Amount Handed Over Date</strong></td>
                                            <td>
                                            	<input type="text" id="txtff_handed_date" name="txtff_handed_date" value="<?php echo ($empInfo[0]['ff_handed_date'] !="" && $empInfo[0]['ff_handed_date'] !="0000-00-00")?  date("d-m-Y", strtotime($empInfo[0]['ff_handed_date'])) : "" ;?>" class="datepickerShow form-control" style="width:190px;" />
                                            </td>
                                            <td><strong>F &amp; F Cheque No & Bank:</strong></td>
                                            <td>
                                            	<input type="text" id="txtff_cheque" name="txtff_cheque" value="<?php echo $empInfo[0]['ff_cheque_bank'];?>" class="form-control" style="width:190px;" />
                                            </td>
                                          
                                        </tr>  
								</tbody>
							</table>
							<div class="row submtSec"  style="margin-bottom: 20px;">
								<div class="msg-sec"></div>
								<div class="col-md-3 pull-right">
									<span class="pull-right">
										<input type="submit" id="btnUpdateComp" name="btnUpdateComp" class="btn btn-sm btn-info" value="Update" />
									</span>
								</div>
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