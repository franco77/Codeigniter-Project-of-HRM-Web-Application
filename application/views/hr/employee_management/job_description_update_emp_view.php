<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>  
<?php
$get_id = "";
if (isset($_GET['id']))
{
	$get_id = "?id=".$_GET['id'];
}
echo '<input type="hidden" id="getURLIdVal" name="getURLIdVal" class="btn btn-sm btn-info" value="'.$get_id.'" />';
//print_r($skillInfo); exit();
?> 
<div class="section main-section">
    <div class="container">
        <div class="form-content page-content">
            <div class="mt mb">
                <div class="col-md-3 center-xs">
                    <div class="form-content page-content">
                        <?php $this->load->view('hr/left_sidebar');?>
                    </div>
                </div>
                <div class="col-md-9 center-xs"> 
				<?php $this->load->view('hr/top_view');?>
					<a href="<?= base_url('en/hr/job_description_readonly_emp'.$get_id);?>" class="btn btn-primary pull-right" role="button" style="margin-right: 10px; margin-top: 4px;"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View</a>
                    <legend class="pkheader">JD/Goal</legend>
                    <div class="row well">
                        <?php //$activeProfileTab = "Job"; $action = "Edit"; include("../include/profile-sub-header.php");?>		
						<form id="frmJobUpdate" name="frmJobUpdate" method="POST" action="" enctype="multipart/form-data">
							<?php
							if($success_msg != '') { ?>
							<div class="col-md-12"><div class="alert alert-success" role="alert"> <?php echo $success_msg; ?> </div>
							<?php } else if($error_msg != '') { ?>
							<div class="col-md-12"><div class="alert alert-danger" role="alert"> <?php echo $error_msg; ?> </div>
							<?php } ?>
							
							<div class="form">
								<div class="form1 multiSelectHolder">
									<table class="table table-striped table-bordered table-condensed">
										<tr>
											<td width="100"><span class="red">&nbsp;</span> <strong> Upload JD:</strong>
											<a href="#" class="tooltips">
												&nbsp;<b>?</b>&nbsp;
												<span>
													<img class="callout" src="<?= base_url() ?>/assets/images/callout.gif" />
													<ol>
														<li>PDF & Docx file is allowed</li>
													</ol>
												</span>
											</a>
											</td>
											<td width="200"><input type="file" id="flJobDesc" name="flJobDesc" class="form-control" style="width: 220px; margin-left: 7px;" /></td>
											<td colspan="2"><?php if($empInfo[0]["jd_document"] != ""){ echo "<div class='glowingtabs3'><a href='".base_url()."assets/upload/jd_goal/".$empInfo[0]["jd_document"]."' target='_blank'><span>View</span></a></div>";}?>&nbsp;</td>
										</tr>
										<tr>
											<td nowrap="nowrap"><span class="red">&nbsp;</span> <strong> Upload KRA &amp; KPI:</strong>
											<a href="#" class="tooltips">
												&nbsp;<b>?</b>&nbsp;
												<span>
													<img class="callout" src="<?= base_url() ?>/assets/images/callout.gif" />
													<ol>
														<li>PDF & Docx file is allowed</li>
													</ol>
												</span>
											</a>
											</td>
											<td><input type="file" id="flKPI" name="flKPI" class="form-control" style="width: 220px; margin-left: 7px;" /></td>
											<td colspan="2"><?php if($empInfo[0]["kpi_document"] != ""){ echo "<div class='glowingtabs3'><a href='".base_url()."assets/upload/jd_goal/".$empInfo[0]["kpi_document"]."' target='_blank'><span>View</span></a></div>";}?>&nbsp;</td>
										</tr>
										
										<tr>
											<td><span class="red">&nbsp;</span> <strong> Skills:</strong></td>
											<td>
												<select id="selSkills"  name="selSkills[]" class="selectpicker" multiple>
													<?php 
													$myArray = explode(',',$empInfo[0]['skills']);
													$skillSQL_count = COUNT($skillInfo); 
													for($i=0; $i<$skillSQL_count; $i++) {	?>
														<option value="<?php echo $skillInfo[$i]['skill_id'];?>" <?php if(in_array($skillInfo[$i]['skill_id'], $myArray)) { echo "selected"; }  ?>><?php echo $skillInfo[$i]['skill_name'];?></option>
													<?php }  ?>
												</select>
											</td>
											<td colspan="2">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="4" class="form_title"><input type="submit" id="btnUpdateJD" name="btnUpdateJD" class="btn btn-sm btn-info pull-right" value="Update" /></td>
										</tr>
										<!-- <tr  style="display:none;">
											<td colspan="4" class="form_title">Letter Issued</td>
										</tr>
										<tr  style="display:none;">
											<td><input type="file" id="flLetter" name="flLetter" class="form-control" style="width:100px;" /></td>
											<td><input type="text" id="txtLetterTittle" name="txtLetterTittle" placeholder="Letter Tittle" class="form-control" style="width:250px;" /></td>
											<td><input type="text" id="txtIssuedDate" name="txtIssuedDate" placeholder="Issue Date" class="form-control" style="width:130px;" /></td>
											<td colspan="2"><input type="submit" id="btnUploadLetter" name="btnUploadLetter" class="search_sbmt" value="Upload" /></td>
										</tr> -->
									</table>
								</div>
							</div>
						
							<div class="form1 multiSelectHolder">
								<table class="table table-striped table-bordered table-condensed">
									<tr class="info">
										<td class="form_title" colspan="4" align="center"><strong>Goal Setting</strong></td>
									</tr>
									<tr>
										<td class="form_title" colspan="2" width="50%"><span style="line-height: 3;"><strong>Individual Goals</strong></span></td>
										<td class="form_title" colspan="2">
											<span style="line-height: 3;"><strong>Choose financial year</strong></span>
											<?php if(empty($qryInfo[0]['annualdate'])) $yrrr = date("Y"); else $yrrr = date_parse($qryInfo[0]['annualdate'])['year']; ?>
											<select name="year" id="year" class="form-control" onchange="document.frmJobUpdate.submit();" style="width: 54%;float: right;">
												<?php
													$yr=date("Y");
													$ydate = $yr;
													for ($j=$yr;$j>=2014;$j--){                                         
													?>                                         
												<option value="<?php echo $j+1?>" <?php  if($dyear == $j+1) echo "selected"; ?> ><?php echo $j.'-'.($j+1);?></option>
												<?php 
													}?>
												
											</select>
										</td>
									</tr>
								</table>
								<table class="table table-striped table-bordered table-condensed" id="dataTable">
									<tbody>
										<tr class="info">
											<td>&nbsp;</td>
											<td><strong>Performance Objectives<strong></td>
											<td><strong>Target<strong></td>
											<td><strong>Weightage<strong></td>
										</tr>
										
										<?php $qryInfo_count = COUNT($qryInfo);   
										if($qryInfo_count != 0) {
										for($i=0; $i<$qryInfo_count; $i++) { ?>
										<tr>
											<td><input name="chk" type="checkbox"></td>
											<td><textarea name="objective[]" class="form-control" rows="4" style="width: 200px;"><?php echo $qryInfo[$i]['objective']; ?></textarea></td>
											<td><textarea name="target[]" class="form-control" rows="4" style="width: 200px;"><?php echo $qryInfo[$i]['target']; ?></textarea></td>
											<td valign="top"><select name="weightage[]" class="form-control weightageBaby" style="width:110px;">
																					<option value="">Select</option>       
																					<?php for($j=1; $j<=100; $j++){ ?>
																					<option value="<?php echo $j; ?>" <?php if($qryInfo[$i]['weightage']==$j) echo "selected"; ?>><?php echo $j; ?></option>
																					<?php } ?>                                                    
															</select> <input  name="mid[]" type="hidden" value = "<?php echo $qryInfo[$i]['mid']; ?>">
											
											</td>
											
										 </tr> 
										 
										<?php } ?> <tr id='add_tr'></tr> <?php } else {?>
										<tr>
											<td><input name="chk" class="search_UI" type="checkbox"></td>
											<td><textarea name="objective[]" class="form-control" rows="4" style="width: 200px;"></textarea></td>
											<td><textarea name="target[]" class="form-control" rows="4" style="width: 200px;"></textarea></td>
											<td valign="top">
												<select name="weightage[]" class="form-control weightageBaby" style="width:110px;">
													<option value="">Select</option>
													<?php for($i=1; $i<=100; $i++){ ?><option value="<?php echo $i + 1; ?>"><?php echo $i; ?></option><?php } ?>                                                    
												</select>
												<input type="hidden" name="mid[]" value="" />
											</td>
										</tr>
										<?php } ?>
										<tr id='add_tr'></tr>
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
					</form>				
							</div>
						
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
</div>


<script>
function addRow(tableID) {
			$('#add_tr').replaceWith('<tr><td><input name="chk" class="search_UI" type="checkbox"></td><td><textarea name="objective[]" class="form-control" rows="4" ></textarea></td><td><textarea name="target[]" class="form-control" rows="4"></textarea></td><td><select name="weightage[]" class="form-control" ><option value="0">Select</option><?php for($i=1; $i<=100; $i++){ ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?><?php for($i=1; $i<=100; $i++){ ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?></select> <input  name="mid[]" type="hidden" value = ""></td><td><select name="progress[]" class="form-control" ><option value="0">Select</option><?php for($i=1; $i<=100; $i++){ ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?><?php for($i=1; $i<=100; $i++){ ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?></select></td></tr><tr id="add_tr"></tr>');
			return false;	
    }
	
	function deleteRow(tableID) {
        try {
            var table = document.getElementById(tableID);
			//alert(table);
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
            //alert(e);
        }
    }
function calculateTotalWeightage(){
     //alert('ass');
        var twtg = 0;
        $k( ".weightageBaby" ).each(function() {
            wtg = $k( this );
            twtg = parseInt(twtg) + parseInt(wtg.val());
            
          });
          if(parseInt(twtg) != 100){              
              alert('Total Weight must be 100%.');
              return false;
          }               
          return( true );
    }
</script>
<style>
    a.tooltips {outline:none; text-decoration: none;
    background: none repeat scroll 0 0 #06c;
    border-radius: 50%;
    box-shadow: 1px 1px 2px rgba(255, 255, 255, 0.6) inset, -1px -1px 2px rgba(0, 0, 0, 0.6) inset;
    color: #fff;
    cursor: pointer;
    display: inline-block;
    font-size: 13px;
    font-weight: bold;
    height: 15px;
    line-height: 15px;    
    text-align: left;
    vertical-align: middle;
    width: 15px;
    }
    a.tooltips strong {line-height:30px;} 
    a.tooltips:hover {text-decoration:none;font-weight: normal;} 
    a.tooltips span { z-index:10;display:none; padding:14px 20px; margin-top:-30px; margin-left:15px; width:300px; line-height:16px; }
    a.tooltips:hover span{ display:inline; position:absolute; color:#111; border:1px solid #DCA; background:#fffAF0;} 
    .callout {z-index:20;position:absolute;top:30px;border:0;left:-12px;} 
    /*CSS3 extras*/ 
    a.tooltips span { border-radius:4px; box-shadow: 5px 5px 8px #CCC; }
    #dataTable td{
        padding: 4px 7px !important;
    }
</style>