<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php
$get_id = "";
if (isset($_GET['id']))
{
	$get_id = $_GET['id'];
}
?>
<!--<link href='<?php echo base_url();?>assets/dist/frontend/bootstrap.min.css' rel='stylesheet' media='screen' />-->
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">Mid Year Review Form (<small>Fill the Mid Year Review Form Here</small>)</legend>
					<div class="row well">
						<?php //echo $noOfMonths;
							if($this->config->item('midYearReviewFormFreeze') == 'NO') {
								if($noOfMonths >= 3){
						?>
					
					
						<form class="form-horizontal" id="frmSalaryUpdate" name="frmSalaryUpdate" method="POST" action="" enctype="multipart/form-data" onsubmit="return check_form()">
						  <fieldset> 
							<div class="row">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-condensed">
										<tbody> 
											<tr>
												<td colspan="3" class="form_title" style="font-size:16px;" align="center"><strong>To be filled in by the Appraisee</strong></td>
											</tr>
											<tr>
												<td colspan="3" class="form_title">1. Note your accomplishments/progress on a copy of the Performance Objectives page.<span class="red">*</span></td>
											</tr>
											<tr>
												<tr><td colspan="3"><textarea name="accomplishments" class="search_UI form-control" rows="5" cols="25"  id="accomplishments" style="min-width: 789px; max-width: 789px;" required=""><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['accomplishments']; ?></textarea></td>
											</tr>                                          
											<tr>
												<td colspan="3" class="form_title">2. What other special contributions did you make during the Mid- Year performance period? please be more specific on this.<span class="red">*</span>
												 </td>
											</tr>
											<tr>
												<td colspan="3"><textarea name="contributions" class=" search_UI form-control" rows="5" cols="25"   id="contributions" style="min-width: 789px; max-width: 789px;"  required=""><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['contributions']; ?></textarea></td>
											</tr>
											<tr>
												<td colspan="3" class="form_title">3. Comment on any unplanned events and/or significant problems that may have prevented you from fully achieving performance results so far during this evaluation period.<span class="red">*</span>
												</td>
											</tr>
											<tr>
												<td colspan="3"><textarea name="unplanned_events" class=" search_UI form-control" rows="5" cols="25"  id="unplanned_events" style="min-width: 789px; max-width: 789px;"  required=""><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['unplanned_events']; ?></textarea></td>
											</tr> 
											<tr>
												<td colspan="3" class="form_title" align="center"><h4 class="allheader">PERFORMANCE OBJECTIVE</h4></td>
											</tr>
											<tr>
												<td colspan="3">Performance Review -  Insert the performance objectives developed at the beginning of the evaluation period below and note the accomplishments/progress and any comments related to the objective.</td>
											</tr>
											
											<tr>
												<td colspan="3">
													<table width="100%" border="0" cellpadding="10" cellspacing="10" id="dataTable" class="table table-striped table-bordered table-condensed">
														 <tbody>
															<tr class="info">
																<td style="border: solid 1px #000;" width="20%"><strong>Performance Standard & Objectives<strong></td>
																<td style="border: solid 1px #000;" width="19%"><strong>Target<strong></td>
																<td style="border: solid 1px #000;" width="8%"><strong>Weightage<strong></td>
																<td style="border: solid 1px #000;" width="8%"><strong>Progress<strong></td>
																<td style="border: solid 1px #000;" width="30%"> <strong>Accomplishments and Comments<strong> <i>(To be filled by Appraisee)</i></td>
																<td style="border: solid 1px #000;"  width="15%"><strong>Rating <strong><i>(To be filled by Appraiser</i></td>
															</tr> 
															<?php 
															   for($m=0; $m<count($rowGoal); $m++){
															 ?>
															<tr valign="top">
																<td>
																	<input type="hidden" name="mid[]" value="<?php echo $rowGoal[$m]['mid'];?>" />
																	<p> <?php echo $rowGoal[$m]['objective']; ?></p>
																</td>
																
																<td>
																	<p><?php echo $rowGoal[$m]['target']; ?></p>
																</td>
																
																<td>
																	<p><?php echo $rowGoal[$m]['weightage']; ?></p>
																</td>
																
																<td>
																	<p><?php echo $rowGoal[$m]['progress']; ?></p>
																</td>
																   
																<td>
																	<textarea name="comment[]" class="search_UI  form-control" rows="10"  style="min-width: 225px;max-width: 110px;" required=""><?php echo $rowGoal[$m]['comment']; ?></textarea>
																</td>
																
																<td>
																	<input type="checkbox" disabled="" name="rating[]" <?php if($rowGoal[$m]['rating']=='Progressing'){ echo "checked";} ?> value="Progressing" /> Progressing<br/>
																	<input type="checkbox" disabled="" name="rating[]" <?php if($rowGoal[$m]['rating']=='NotProgressing'){ echo "checked"; } ?> value="NotProgressing" /> Not Progressing
																</td>
															</tr> 
															   <?php } ?>
														</tbody>
													</table>
												</td> 
											</tr> 
											<?php // if($get_id==''){ ?>
											<tr style="display:none">
												<td colspan="3">
													 <input value="Add Row" onclick="addRow('dataTable')" type="button"> 
													 <input value="Delete Row" onclick="deleteRow('dataTable')" type="button">
												</td>
											</tr>
											<?php //}else{ ?>
											<tr><td>&nbsp;</td></tr>	
										</tbody> 
									</table>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label" for="signup"></label>
								<div class="col-md-3 pull-right"> 
									<input type="submit" id="btnAddMessage" name="btnAddMessage" class="btn btn-info" value="APPLY" />
									<input type="hidden" name="login_id" id="login_id" value="<?php echo $get_id;?>" />									
								</div>
							</div>  
						</form>
						<?php 
							} else{
						?>
							<div class="col-md-12"> <h4 style="color: red;"> Sorry you are not elligible for Mid year review ...</h4></div>
						
						<?php } 
							}
							else{
						?>
							<div class="col-md-12"> <h4 style="color: red;"> Mid year review not started yet...</h4></div>
						
						<?php } ?>
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div>
</div>
<style>
.form_title{     font-weight: 700; }
</style>

<script language="javascript">
function check_form() {
	if ($('#accomplishments').val().trim() == '') {
		$('#accomplishments').focus();
		return false ;
	}
	if ($('#contributions').val().trim() == '') {
		$('#contributions').focus();
		return false ;
	}
	if ($('#unplanned_events').val().trim() == '') {
		$('#unplanned_events').focus();
		return false ;
	}
    
	return true ;
}
function addRow(tableID) {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var row = table.insertRow(rowCount);
	var colCount = table.rows[0].cells.length;
	var percent = calculateTotalW(); 
	//alert(percent);
	//if(rowCount<=10){ 
	if(parseInt(percent) < 100 ){    
		for (var i = 0; i < colCount; i++) {
			var j=rowCount;
			var newcell = row.insertCell(i);
			newcell.innerHTML = table.rows[1].cells[i].innerHTML;
			switch (newcell.childNodes[0].type) {
				case "text":
					newcell.childNodes[0].value = "";
					break;
				case "textarea":
					newcell.childNodes[0].value = "";
					break;    
				case "checkbox":
					newcell.childNodes[0].checked = false;
					break;
				case "hidden":
					newcell.childNodes[0].value = "";
					break;
				case "radio":                        
					newcell.childNodes[0].name = 'rating_'+j;
					newcell.childNodes[1].name = 'rating_'+j;
					break;
				case "select-one":
					newcell.childNodes[0].selectedIndex = 0;
					break;
			}                
		}
	}else{
		alert('Total Weight must be 100%.');
	}
}	

function deleteRow(tableID) {
	try {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
		//alert(rowCount);
		for (var i = 0; i < rowCount; i++) {
			var row = table.rows[i];
			var chkbox = row.cells[0].childNodes[0];
			if (null != chkbox && true == chkbox.checked) {
				if (rowCount <= 2) {
					alert("Cannot delete all the rows.");
					break;
				}
				table.deleteRow(i);
				rowCount--;
				i--;
			}
		}
	} catch (e) {
		alert(e);
	}
}
    
function calculateTotalW(){
	var twtg = 0;
	$( ".weightageBaby" ).each(function() {
		wtg = $( this );
		twtg = parseInt(twtg) + parseInt(wtg.val());
		
	});
	return parseInt(twtg); 
}
</script>