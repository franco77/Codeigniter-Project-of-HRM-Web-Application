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
                        <?php $this->load->view('myprofile/left_sidebar');?>
                    </div>
                </div>
                <div class="col-md-9 center-xs"> 
					<a href="<?= base_url('my_account/job_description_readonly_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View</a>
                    <legend class="pkheader">JD/Goal</legend>
                    <div class="row well">
                        <?php //$activeProfileTab = "Job"; $action = "Edit"; include("../include/profile-sub-header.php");?>		
						<form id="frmJobUpdate" name="frmJobUpdate" method="POST" action="<?php //echo $actionURL;?>" enctype="multipart/form-data">
							<div class="form">
								<div class="form1 multiSelectHolder">
									<table class="table table-striped table-bordered table-condensed">
										<tr>
											<td width="100"><span class="red">&nbsp;</span> <strong> Upload JD:</strong></td>
											<td width="200"><input type="file" id="flJobDesc" name="flJobDesc" class="form-control" style="width: 220px; margin-left: 7px;" /></td>
											<td colspan="2"><?php //if($jd != ""){ echo "<div class='glowingtabs3'><a href='".SITE_BASE_URL."/upload/document/".$jd."' target='_blank'><span>View</span></a></div>";}?>&nbsp;</td>
										</tr>
										<tr>
											<td nowrap="nowrap"><span class="red">&nbsp;</span> <strong> Upload KRA &amp; KPI:</strong></td>
											<td><input type="file" id="flKPI" name="flKPI" class="form-control" style="width: 220px; margin-left: 7px;" /></td>
											<td colspan="2"><?php //if($kpi != ""){ echo "<div class='glowingtabs3'><a href='".SITE_BASE_URL."/upload/document/".$kpi."' target='_blank'><span>View</span></a></div>";}?>&nbsp;</td>
										</tr>
										<tr>
											<td><span class="red">&nbsp;</span> <strong> Skills:</strong></td>
											<td><input type="hidden" name="hdnSkills" id="hdnSkills" value="<?php //echo $empInfo["skills"];?>" />
												<select id="selSkills"  name="selSkills" class="selectpicker" multiple>
												<option>1</option>
												<option>2</option>
												<option>3</option>

												<?php /* if($skillNUM > 0){
													$skillsArray = explode(",", $empInfo["skills"]);
													$prevSkillCat = "";
													while($skillINFO = mysql_fetch_array($skillRES)){
														$skillCat = $skillINFO["skill_category"];
														if($prevSkillCat != $skillCat){
															echo "<optgroup label='".$skillCat."'>";
														}
														if(in_array($skillINFO["skill_id"], $skillsArray)){
															echo "<option value='".$skillINFO["skill_id"]."' selected='selected'>".$skillINFO["skill_name"]."</option>";
														}else{
															echo "<option value='".$skillINFO["skill_id"]."'>".$skillINFO["skill_name"]."</option>";
														}
														if($prevSkillCat != $skillCat){
															echo "</optgroup>";
															$prevSkillCat = $skillCat;
														}
													}
													} */
													?>
												</select>
											</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="4" class="form_title"><input type="submit" id="btnUpdateJD" name="btnUpdateJD" class="btn btn-sm btn-info pull-right" value="Update" /></td>
										</tr>
										<tr  style="display:none;">
											<td colspan="4" class="form_title">Letter Issued</td>
										</tr>
										<tr  style="display:none;">
											<td><input type="file" id="flLetter" name="flLetter" class="form-control" style="width:100px;" /></td>
											<td><input type="text" id="txtLetterTittle" name="txtLetterTittle" placeholder="Letter Tittle" class="form-control" style="width:250px;" /></td>
											<td><input type="text" id="txtIssuedDate" name="txtIssuedDate" placeholder="Issue Date" class="form-control" style="width:130px;" /></td>
											<td colspan="2"><input type="submit" id="btnUploadLetter" name="btnUploadLetter" class="search_sbmt" value="Upload" /></td>
										</tr>
									</table>
								</div>
							</div>
							<?php
								/* if($letterNUM > 0){ $i = 0;
									echo '<div class="form marT_10" style="display:none;"><div class="form1"><table cellpadding="0" cellspacing="0" width="100%">';
									echo '<tr>';
									echo '<th>Sl. No</th>';
									echo '<th>Letter Name</th>';
									echo '<th>Issued Date</th>';
									echo '<th>Action</th>';
									echo '</tr>';
									while($letterINFO = mysql_fetch_array($letterRES)){ $i++;
										echo '<tr>';
										echo '<td>'.$i.'</td>';
										echo '<td>'.$letterINFO["letter_name"].'</td>';
										echo '<td>'.date("d M, Y", strtotime($letterINFO["issued_date"])).'</td>';
										echo '<td>';
										echo "<div class='glowingtabs3'><a href='".SITE_BASE_URL."/upload/document/".$letterINFO["letter_document"]."' target='_blank'><span>View</span></a></div>";
										echo "<div class='glowingtabs3'><a href='job_description_update_emp.php?id=".$loginID."&a=ld&lid=".$letterINFO["letter_id"]."'><span>Delete</span></a></div>";
										echo '</td>';
										echo '</tr>';
									}
									echo '</table></div></div>';
								} */
								?>
							<div class="form1 multiSelectHolder">
								<table class="table table-striped table-bordered table-condensed">
									<tr class="info">
										<td class="form_title" colspan="4" align="center"><strong>Goal Setting</strong></td>
									</tr>
									<tr>
										<td class="form_title" colspan="2" width="50%"><span style="line-height: 3;"><strong>Individual Goals</strong></span></td>
										<td class="form_title" colspan="2">
											<span style="line-height: 3;"><strong>Choose financial year</strong></span>
											<select name="year" id="year" class="form-control" onchange="document.frmJobUpdate.submit();" style="width: 54%;float: right;">
												<?php
													//$yr=date("Y");
													//for ($j=$yr;$j>=2014;$j--){                                         
													?>                                         
												<option value="<?php //echo $j.'-'.($j+1);?>" <?php //if($ydate==($j) || $_REQUEST['year']==($j)) echo "selected";?>><?php //echo $j.'-'.($j+1);?></option>
												<?php 
													//}?>
											</select>
										</td>
									</tr>
								</table>
								<table class="table table-striped table-bordered table-condensed">
									<tbody>
										<tr class="info">
											<td>&nbsp;</td>
											<td><strong>Performance Objectives<strong></td>
											<td><strong>Target<strong></td>
											<td><strong>Weightage<strong></td>
										</tr>
										<?php 
											//$resGoal=mysql_query($qryGoal);
											//$numRow= mysql_num_rows($resGoal);
											//if($numRow < 1){
											?>
										<tr>
											<td><input name="chk" type="checkbox"></td>
											<td><textarea name="objective[]" class="form-control" rows="4" style="width: 200px;"></textarea></td>
											<td><textarea name="target[]" class="form-control" rows="4" style="width: 200px;"></textarea></td>
											<td valign="top">
												<select name="weightage[]" class="form-control weightageBaby" style="width:110px;">
													<option value="">Select</option>
													<?php //for($i=1; $i<=100; $i++){ ?>
													<option value="<?php //echo $i; ?>"><?php echo $i; ?></option>
													<?php //} ?>                                                    
												</select>
											</td>
										</tr>
										<?php //}else{                        
											//while($rowGoal = mysql_fetch_array($resGoal)){
											?>
										<tr>
											<td><input name="chk" type="checkbox"></td>
											<td><textarea name="objective[]" class="form-control" rows="4" style="width: 200px;"><?php //echo $rowGoal['objective']; ?></textarea></td>
											<td><textarea name="target[]" class="form-control" rows="4" style="width: 200px;"><?php //echo $rowGoal['target']; ?></textarea></td>
											<td valign="top">
												<select name="weightage[]" class="form-control weightageBaby" style="width:110px;">
													<option value="">Select</option>
													<?php //for($i=1; $i<=100; $i++){ ?>
													<option value="<?php echo $i; ?>" <?php //if($rowGoal['weightage']==$i) echo "selected"; ?>><?php echo $i; ?></option>
													<?php // } ?>                                                    
												</select>
												<input type="hidden" name="mid[]" value="<?php // echo $rowGoal['mid']; ?>" />
												<input type="hidden" name="id" value="<?php //echo $loginID; ?>" />
											</td>
										</tr>
										<?php 
											//}
											//} ?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-md-4 pull-right">
										<span class="pull-right">
										<input value="Add Row" onclick="addRow('dataTable')" type="button" class="btn btn-sm btn-info"> 
										<input value="Delete Row" onclick="deleteRow('dataTable')" type="button" class="btn btn-sm btn-info">
										<input type="submit" onclick="return(calculateTotalWeightage());" id="btnUpdateGoal" name="btnUpdateGoal" class="btn btn-sm btn-info" value="Update" />
										</span>
									</div>
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