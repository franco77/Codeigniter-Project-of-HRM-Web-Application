<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>  
<?php
$get_id = "";
if (isset($_GET['id']))
{
	$get_id = "?id=".$_GET['id'];
}
?>
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('myprofile/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">  
					<a href="<?= base_url('my_account'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View</a>
					<legend class="pkheader">General Update</legend>
					<div class="row well">
						<div class="table-responsive">
							<form id="frmProfileUpdate" name="fromProfileUpdate" method="POST" action="" enctype="multipart/form-data"> 
								<table class="table table-striped table-bordered table-condensed">
									<tbody>
										<tr>
											<td width="20%" valign="top"><label class="control-label"><strong>Full Name</strong></label></td>
											<td width="30%">
												<input type="text" id="txtFullName" name="txtFullName" value="<?php echo $empInfo[0]['full_name']?>" class="form-control" readonly="readonly" style="width:190px; margin-bottom: 10px;" />
												<div class="clear"></div>
												<input type="text" id="txtFirstName" name="txtFirstName" value="<?php echo $empInfo[0]['name_first']?>" class="required form-control" style="width: 102px; margin-right: 2px; float: left;" onkeyup="javascript:showFullName();" />
	                                            <input type="text" id="txtMiddleName" name="txtMiddleName" value="<?php echo $empInfo[0]['name_middle']?>" class="form-control" style="width: 55px; margin-right: 2px; float: left;" onkeyup="javascript:showFullName();" />
	                                            <input type="text" id="txtLastName" name="txtLastName" value="<?php echo $empInfo[0]['name_last']?>" class="required form-control" style="width:55px; float:left;" onkeyup="javascript:showFullName();" />
												<div class="clear"></div>
												<label style="margin-left: 13px; margin-right: 21px;">(First<span class="red">*</span>)</label>
												<label style="margin-right: 27px;">(Middle)</label>
												<label>(Last<span class="red">*</span>)</label>
											</td>
											<td width="50%" colspan="2" rowspan="3" align="center">
											<?php if($empInfo[0]['user_photo_name'] != ''){
												echo '<img src="'.base_url().'assets/upload/profile/'.$empInfo[0]['user_photo_name'].'" alt="" width="130" height="150" class="form_img" />';
												}else{
													echo '<img src="'.base_url().'assets/images/no-image.jpg" width="130" height="150" alt="" class="form_img" />';
												}
												if($empInfo[0]['user_sign_name'] != ''){
												echo '<br/><img src="'.base_url().'assets/upload/profile/'.$empInfo[0]['user_sign_name'].'" alt="" width="225" height="40" class="form_img" />';
												}
												?>
											</td>
										</tr>
										<tr style="display: none;">
											<td width="30%" valign="top"><span class="red">&nbsp;</span> <strong>Father's Name:</strong></td>
											<td width="20%" valign="top">
												<input type="text" id="txtFatherName" name="txtFatherName" value="<?php echo $empInfo[0]['father_name']?>" class="form-control" style="width:190px; margin-bottom: 10px;" /></td>
										</tr>
										<tr>
											<td><strong>Employee Code:</strong></td>
											<td><?php echo $empInfo[0]['loginhandle']?></td>
										</tr>
										<tr>
											<td><span class="red">&nbsp;</span><strong>DOB:</strong></td>
											<td>
												<?php if(isset($_REQUEST['dob']) ==''){
													echo '<input type="text" id="txtdob" name="txtdob"  value="'. date("d-m-Y", strtotime($empInfo[0]['dob'])).'" class="required form-control" style="width:190px;" />';
												}else{
													echo '<input type="text" id="txtdobreadonly" name="txtdobreadonly"  value="'. date("d-m-Y", strtotime($empInfo[0]['dob'])).'" class="form-control" readonly style="width:190px;" />';
													echo '<input type="hidden" id="txtdob" name="txtdob"  value="'. date("d-m-Y", strtotime($empInfo[0]['dob'])).'" />';
												} 
												?>
											</td>
										</tr>
										<tr>
											<td><strong>Highest Qualif:</strong></td>
											<td>
												<select id="selHgstEdu" name="selHgstEdu" class="form-control" style="width: 190px;">
													<option value="0">Select Qualification</option>
													<?php 
														$qualif = count($qualification);
														for($i=0; $i<$qualif; $i++)
														{?>
															<option <?php if($empInfo[0]['highest_qual'] == $qualification[$i]['course_name']) { echo 'selected="selected"'; }?> value="<?php echo $qualification[$i]['course_name'];?>"><?php echo $qualification[$i]['course_name'];?></option>
														<?php }
													?>
													
												</select>
											</td>
											<td><strong>Location of Highest Qualif:</strong></td>
											<td>
												<select id="perLoc" name="perLoc" class="required  form-control" style="width: 190px;">
													<?php 
														$qualification_result = count($location_of_highest_qualification);
														for($i=0; $i<$qualification_result; $i++)
														{?>
															<option <?php if($empInfo[0]['loc_highest_qual'] == $location_of_highest_qualification[$i]['state_name']) { echo 'selected="selected"'; }?> value="<?php echo $location_of_highest_qualification[$i]['state_name'];?>"><?php echo $location_of_highest_qualification[$i]['state_name'];?></option>
														<?php }
													?>
													<?php //echo(db_listbox($mysql_state,$empInfo['loc_highest_qual'],''))?>
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
											<td><strong>Personal Email:</strong></td>
											<td><input type="text" id="txtPEmailID" name="txtPEmailID" value="<?php echo $empInfo[0]['per_email'];?>" class="email form-control" style="width:190px;" /></td>
											<td><strong>Official Mobile No:</strong></td>
											<td><input type="text" id="txtofficial_mobile" name="txtofficial_mobile" value="<?php echo $empInfo[0]['official_mobile'];?>" class="form-control" style="width:190px;" /></td>
										</tr>
										<tr>
											<td><strong>Gender:</strong></td> 
											<td>
													<select id="rdGender" name="rdGender" class="required form-control" style="width:190px;">
														<option value="M" selected="selected">Male</option>
														<option value="F" <?php if($empInfo[0]['gender'] == 'F') echo 'selected="selected"';?>>Female</option>
													</select>
												</td>                                            
											<td><strong>Marital Status:</strong></td>
											<td>
												<select id="rdMStatus" name="rdMStatus" class="form-control" style="width:190px;">
													<option value="S" selected="selected">Single</option>
													<option value="M" <?php if($empInfo[0]['marital_status'] == 'M') echo 'selected="selected"';?>>Married</option>
												</select>
											 </td>
										</tr>
										<tr>
											<td colspan="2"><strong>&nbsp;Show Mobile No on AABSyS Staff Directory :</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="showMobileOnSearch" name="showMobileOnSearch" value="Y" <?php if($empInfo[0]['isShowOnSearch'] == 'Y')echo 'checked="checked"';?> /></td>
											<td><strong>Blood Group:</strong></td>
											<td>
												<select name="txtBGroup"  class="required form-control" style="width:190px;">
													<option value="">Select</option>
													<option value="A+" <?php if($empInfo[0]['blood_group'] == 'A+') echo 'selected="selected"';?>>A+</option>
													<option value="A-" <?php if($empInfo[0]['blood_group'] == 'A-') echo 'selected="selected"';?>>A-</option>
													<option value="B+" <?php if($empInfo[0]['blood_group'] == 'B+') echo 'selected="selected"';?>>B+</option>
													<option value="B-" <?php if($empInfo[0]['blood_group'] == 'B-') echo 'selected="selected"';?>>B-</option>
													<option value="AB+" <?php if($empInfo[0]['blood_group'] == 'AB+') echo 'selected="selected"';?>>AB+</option>
													<option value="AB-" <?php if($empInfo[0]['blood_group'] == 'AB-') echo 'selected="selected"';?>>AB-</option>
													<option value="O+" <?php if($empInfo[0]['blood_group'] == 'O+') echo 'selected="selected"';?>>O+</option>
													<option value="O-" <?php if($empInfo[0]['blood_group'] == 'O-') echo 'selected="selected"';?>>O-</option>
												</select>
											</td>
											<td style="display: none;"><strong>Total Year of Experince:</strong></td>
											<td  style="display: none;" >
												<input type="text" id="txtTotalExp" name="txtTotalExp" value="<?php echo $empInfo[0]['total_exp']?>" class="form-control" style="width:190px; margin-bottom: 10px;" />
											</td>
										</tr>
										<!--<tr>
											
											<td><strong>Mobile:</strong></td>
											<td colspan=""><input type="text" id="txtContNo" name="txtContNo" value="<?php echo $empInfo[0]['mobile']?>" class="form-control" style="width:190px;" /></td>
										
										</tr>-->
										<?php //if($empInfo[0]['user_photo_name'] == ''){?>
										<tr>
											<td valign="top"><strong>Photo:</strong></td>
											<td colspan="3">
											<input type="file" id="txtPhoto" name="txtPhoto" class="form-control" style="width:300px;" />
											<div class="clear"></div><strong>(Please upload a passport color photograph of dimension 130 X 150)</strong>
											</td>
										</tr>
										<?php //} ?> 
										<tr>
											<td valign="top"><strong>Signature:</strong></td>
											<td colspan="3">
											<input type="file" id="txtSign" name="txtSign" class="form-control" style="width:300px;" />
											<div class="clear"></div><strong>(Please upload a scanned (digital) image of his/her Signature of dimension 225 x 40)</strong>
											</td>
										</tr>  
										<tr>
											<td valign="top" ><span class="red">*</span><strong>Email Signature:</strong></td>
											<td colspan="3"><textarea id="emailSign" name="emailSign" class="required form-control" rows="5"><?php echo $empInfo[0]['email_signature'];?></textarea></td>	   
										</tr>
										<tr>
											<td colspan="4" class="form_title">Permanent Address</td>
										</tr>							
										<tr>
											<td valign="top"><span class="red">*</span><strong>Address:</strong></td>
											<td><textarea id="perAddr" name="perAddr" class="required form-control" style="width:190px;"><?php echo $empInfo[0]['address1'];?></textarea></td>
											<!--
											<td><span class="red"></span><strong>Landline No:</strong></td>
											<td><input type="text" id="txtper_landline_no" name="txtper_landline_no" value="<?php echo $empInfo[0]['per_landline_no']?>" class="form-control" style="width:190px;" /></td>--> 
										</tr>
										<tr>
											<td><span class="red">*</span><strong>City / District:</strong></td>
											<td><input type="text" id="perDist" name="perDist" value="<?php echo $empInfo[0]['city_district1']?>" class="required form-control" style="width:190px;" /></td>
											<td><span class="red">*</span><strong>State / Region:</strong></td>
											<td>
												<select id="perState" name="perState" class="required  form-control" style="width: 190px;">
													<?php 
														$state_count = count($state);
														for($i=0; $i<$state_count; $i++)
														{?>
															<option><?php echo $state[$i]['state_name'];?></option>
														<?php }
													?> 
												</select>
											</td>
										</tr>
										<tr>
											<td><span class="red">*</span><strong>Country:</strong></td>
											<td>
												<select id="perCountry" name="perCountry" class="required  form-control" style="width: 190px;">
													<?php 
														$country_count = count($country);
														for($i=0; $i<$country_count; $i++)
														{?>
															<option><?php echo $country[$i]['country_name'];?></option>
														<?php }
													?> 
												</select>
											</td>
											<td><span class="red">*</span><strong>Pin / Zip:</strong></td>
											<td><input type="text" id="perPin" name="perPin" value="<?php echo $empInfo[0]['pin_zip1']?>" class="required form-control" style="width:190px;" /></td>
										</tr>
										<tr>
											<td><span class="red"></span><strong>Landline No:</strong></td>
											<td><input type="text" id="txtper_landline_no" name="txtper_landline_no" value="<?php echo $empInfo[0]['phone1']?>" class="form-control" style="width:190px;" /></td>
										  
											<td><strong>Mobile:</strong></td>
											<td colspan=""><input type="text" id="txt_mobile1" name="txt_mobile1" value="<?php echo $empInfo[0]['mobile1']?>" class="form-control number" style="width:190px;" /></td>
										</tr>
									  
										<tr>
											<td colspan="4" class="form_title">Correspondence Address</td>
										</tr>							
										<tr>
											<td valign="top"><span class="red">*</span><strong>Address:</strong></td>
											<td><textarea id="txtaddress2" name="txtaddress2" class="required form-control" style="width:190px;"><?php echo $empInfo[0]['address2'];?></textarea></td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td><span class="red">*</span><strong>City / District:</strong></td>
											<td><input type="text" id="txtcity_district2" name="txtcity_district2" value="<?php echo $empInfo[0]['city_district2']?>" class="required form-control" style="width:190px;" /></td>
											<td><span class="red">*</span><strong>State / Region:</strong></td>
											<td>
												<select id="ddstate_region2" name="ddstate_region2" class="required  form-control" style="width: 190px;">
													<?php 
														$state_count = count($state);
														for($i=0; $i<$state_count; $i++)
														{?>
															<option><?php echo $state[$i]['state_name'];?></option>
														<?php }
													?> 
													<?php //echo(db_listbox($mysql_state,$empInfo["state_region2"],''))?>
												</select>
											</td>
										</tr>
										<tr>
											<td><span class="red">*</span><strong>Country:</strong></td>
											<td>
												<select id="ddcountry2" name="ddcountry2" class="required  form-control" style="width: 190px;">
													<?php 
														$country_count = count($country);
														for($i=0; $i<$country_count; $i++)
														{?>
															<option><?php echo $country[$i]['country_name'];?></option>
														<?php }
													?> 
													<?php //echo(db_listbox($mysql_country,$empInfo['country2'],''))?>
												</select>
											</td>
											<td><span class="red">*</span><strong>Pin / Zip:</strong></td>
											<td><input type="text" id="txtpin_zip2" name="txtpin_zip2" value="<?php echo $empInfo[0]['pin_zip2']?>" class="required form-control number" style="width:190px;" /></td>
										</tr>
										<tr>
											<td><span class="red"></span><strong>Landline No:</strong></td>
											<td><input type="text" id="txtcorsp_landline_no" name="txtcorsp_landline_no" value="<?php echo $empInfo[0]['phone2']?>" class="form-control number" style="width:190px;" /></td>
											<td><span class="red">*</span><strong>Mobile No:</strong></td>
											<td><input type="text" id="txtcorsp_mobile_no" name="txtcorsp_mobile_no" value="<?php echo $empInfo[0]['mobile']?>" class="required form-control number" style="width:190px;" /></td>
										  
										</tr>
										<tr>
											<td colspan="4" class="form_title">Identification Number(s)</td>
										</tr>
										<tr>
											<td><strong>Passport No:</strong></td>
											<td><input type="text" id="txtPassportNo" name="txtPassportNo" value="<?php echo $empInfo[0]['passport_no'];?>" class="form-control" style="width:190px;" /></td>
											<td><strong>PAN Card No:</strong></td>
											<td><input type="text" id="txtPanNo" name="txtPanNo" value="<?php echo $empInfo[0]['pan_card_no'];?>" class="form-control" style="width:190px;" /></td>
										 </tr>
										<tr>
											<td><strong>Voter ID:</strong></td>
											<td><input type="text" id="txtVoterID" name="txtVoterID" value="<?php echo $empInfo[0]['voter_id'];?>" class="form-control" style="width:190px;" /></td>
											<td><strong>D. License:</strong></td>
											<td><input type="text" id="txtDLicense" name="txtDLicense" value="<?php echo $empInfo[0]['drl_no'];?>" class="form-control" style="width:190px;" /></td>
										</tr>
										<tr>
											<td><span class="red">*</span><strong>Aadhar Card No:</strong></td>
											<td><input type="text" id="txtadharcard_no" name="txtadharcard_no" value="<?php echo $empInfo[0]['adharcard_no'];?>" class="form-control required number" style="width:190px;" /></td>
											<td><strong>IFS CODE</strong></td>
											<td><input type="text" id="ifsc_code" name="ifsc_code" value="<?php echo $empInfo[0]['ifsc_code'];?>" class="form-control" style="width:190px;" /></td>
										</tr>
									</tbody>
								</table>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10"> 
										  <input type="submit" id="btnUpdateProfile" name="btnUpdateProfile" class="btn btn-info pull-right" value="Update" /> 
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