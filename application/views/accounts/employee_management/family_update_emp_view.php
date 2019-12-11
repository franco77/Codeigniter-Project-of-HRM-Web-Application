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
					<a href="<?= base_url('en/accounts_admin/family_profile_readonly_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View</a>
					<legend class="pkheader">Family</legend>
					<div class="row well">
						<div class="table-responsive">
							<form id="frmFamilyUpdate" name="frmFamilyUpdate" method="POST" action="" enctype="multipart/form-data">
								<table class="table table-striped table-bordered table-condensed">
									<tr>
										<td width="20%" valign="top"><span class="red">*</span> <strong>Father's Name:</strong></td>
										<td width="20%" valign="top"><input type="text" id="txtfather_name" name="txtfather_name" value="<?php echo $empInfo[0]['fathers_name']?>" class="required form-control" style="width:190px; margin-bottom: 10px;" /></td>
										<td width="20%" valign="top"><span class="red">*</span> <strong>Father's DOB:</strong></td>
										<td width="20%" valign="top"><input type="text" id="txtfather_dob" readonly name="txtfather_dob" value="<?php echo $fatherdob?>" class="required cal form-control" style="width:190px; margin-bottom: 10px;" /></td>
                                    </tr>
                                    <tr>
	                                    <td width="20%" valign="top"><span class="red">*</span> <strong>Mother's Name:</strong></td>
										<td width="20%" valign="top"><input type="text" id="txtmother_name" name="txtmother_name" value="<?php echo $empInfo[0]['mother_name']?>" class="required form-control" style="width:190px; margin-bottom: 10px;" /></td>
										<td width="20%" valign="top"><span class="red">*</span> <strong>Mother's DOB:</strong></td>
										<td width="20%" valign="top"><input type="text" id="txtmother_dob" readonly name="txtmother_dob" value="<?php echo $motherdob?>" class="required cal form-control" style="width:190px; margin-bottom: 10px;" /></td>
                                    </tr>
                                    <?php if($empInfo[0]["marital_status"]=='M'){ ?>
                                    <tr>
										<td width="30%" valign="top"><span class="red">&nbsp;</span> <strong>Spouse Name:</strong></td>
										<td width="20%" valign="top"><input type="text" id="txtspouse_name" name="txtspouse_name"  value="<?php echo $empInfo[0]['spouse_name']?>" class="form-control" style="width:190px; margin-bottom: 10px;" /></td>
										<td width="30%" valign="top"><span class="red">&nbsp;</span> <strong>Spouse DOB:</strong></td>
										<td width="20%" valign="top"><input type="text" id="txtspouse_dob" name="txtspouse_dob" readonly  value="<?php echo $spousedob?>" class="cal form-control" style="width:190px; margin-bottom: 10px;" /></td>
									</tr>
									<tr>
										<td width="30%" valign="top"><span class="red">&nbsp;</span> <strong>Anniversary Date:</strong></td>
										<td width="20%" valign="top"><input type="text" id="txtanniversary_date" name="txtanniversary_date" readonly value="<?php echo $anniversarydate?>" class="cal form-control" style="width:190px; margin-bottom: 10px;" /></td>
										<td colspan="2">&nbsp;</td>
									</tr>
                                    <?php } ?>
								</table>
								<div class="row">
                                    <input type="submit" id="btnUpdateFamily" name="btnUpdateFamily" class="btn btn-info pull-right" value="Update" />
                                    <div class="clear"></div>
                                </div>
							</form>
						</div>
						<br>
						<div class="table-responsive">
							<?php if($empInfo[0]["marital_status"]=='M'){ ?>
							<form id="frmChildAdd" name="frmChildAdd" method="POST" action="" enctype="multipart/form-data">
								<table class="table table-striped table-bordered table-condensed">
									<tr><td colspan="6" class="form_title"><strong>Children Info</strong></td></tr>
                                             <tr>
                                                 <td><span class="red">*</span> <strong>Name:</strong></td>
                                                 <td><input type="text" id="txtchild_name" name="txtchild_name" value="" class="required form-control" style="width:130px;" /></td>
                                                 <td><span class="red">*</span> <strong>Gender:</strong></td>
                                                 <td>
                                                     <select id="ddl_childgender" name="ddl_childgender" class="required form-control" style="width:140px;">
                                                         <option value="M">Male</option>
                                                         <option value="F">Female</option>
                                                     </select>
                                                 </td>
                                                 <td><span class="red">*</span> <strong>DOB:</strong></td>
                                                 <td><input type="text" id="txtchild_dob" name="txtchild_dob" value="" class="required form-control cal" style="width:130px;" readonly /></td>
                                             </tr>
								</table>
								<div class="link_section">
                                    <input type="submit" id="btnAddChild" name="btnAddChild" class="btn btn-info pull-right" value="Add" />
                                    <div class="clear"></div>
                                </div>
							</form>
							<?php } ?>
						</div>
						<?php 
						if($childRows > 0)
						{   
							$i=0;
                            foreach($childInfo_arr as $childInfo)
                            {
								$i++;
								$childdob =  "";
								if($childInfo["child_dob"] != "" && $childInfo["child_dob"] != "0000-00-00")
								$childdob = date("d-m-Y", strtotime($childInfo["child_dob"]));
                                    ?> </br>
										<div class="table-responsive">
											<form id="frmChildUpdate" name="frmChildUpdate" method="POST" action="" enctype="multipart/form-data">
											<table class="table table-striped table-bordered table-condensed">
												<tr>
													<td colspan="6" class="form_title"><strong>Children Info <?php echo $i;?></strong></td>
												</tr>
												<tr>
													<td><span class="red">&nbsp;</span> <strong>Name:</strong></td>
													<td><input type="text" id="txtchild_name_<?php echo $childInfo['child_id']?>" name="txtchild_name_<?php echo $childInfo['child_id']?>" value="<?php echo $childInfo['child_name']?>" class="form-control" style="width:130px;" /></td>
													<td><span class="red">&nbsp;</span> <strong>Gender:</strong></td>
													<td>
													<select id="ddl_childgender_<?php echo $childInfo['child_id']?>" name="ddl_childgender_<?php echo $childInfo['child_id']?>" class="required form-control" style="width:140px;">
													<option value="M" <?php if($childInfo['child_gender'] == "M"){echo "selected='selected'";}?>>Male</option>
													<option value="F" <?php if($childInfo['child_gender'] == "F"){echo "selected='selected'";}?>>Female</option>
													</select>
													</td>
													<td><span class="red">&nbsp;</span> <strong>DOB:</strong></td>
													<td><input type="text"  id="txtchild_dob_<?php echo $childInfo['child_id']?>" name="txtchild_dob_<?php echo $childInfo['child_id']?>"  value="<?php echo $childdob?>" class="cal form-control datepickerShow" style="width:130px;" /></td>
												</tr>
											</table>
											<div class="row submtSec"  style="margin-bottom: 20px;">
												<div class="msg-sec"></div>
												<div class="col-md-3 pull-right">
													<span class="pull-right">
														<input type="button" id="btnUpdateEdu_<?php echo $childInfo['child_id']?>" name="btnUpdateEdu_<?php echo $childInfo['child_id']?>" class="btn btn-sm btn-info" value="Update" onclick="UpdateFamily(this,'update',<?php echo $childInfo['child_id']?>)" />
														<input type="button" id="btnDeleteEdu_<?php echo $childInfo['child_id'];?>" name="btnDeleteEdu_<?php echo $childInfo['child_id'];?>" class="btn btn-sm btn-danger" value="Delete" onclick="UpdateFamily(this,'delete',<?php echo $childInfo['child_id'];?>)" />
													</span>
												</div>
											</div>
											</form>
										</div> 
						 <?php } }?>
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div> 
</div>