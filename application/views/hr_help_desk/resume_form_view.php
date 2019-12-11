<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php
$get_id = "";
if (isset($_GET['id']))
{
	$get_id = $_GET['id'];
}
?>
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
					<legend class="pkheader">Resume Format (<small>Fill Resume Format Here</small>)</legend>
					<div class="row well">
						<form class="form-horizontal" id="frmSalaryUpdate" name="frmSalaryUpdate" method="POST" action="<?php echo base_url('hr_help_desk/resume_form_submit'); ?>" enctype="multipart/form-data">
							<fieldset> 
								<table cellpadding="0" cellspacing="0" width="100%" class="form1 table table-striped table-bordered table-condensed">
                                        <tr><td colspan="3" class="form_title"><strong>Introduction:</strong><span class="red">*</span> </td></tr>
                                         <tr><td colspan="3"><textarea name="introduction" class="ckeditor form-control"  style="min-width: 789px; max-width: 789px;" id="introduction"><?php if(count($rowResume)>0){ echo $rowResume[0]['introduction']; } ?></textarea></td>
                                        </tr> 
                                        <tr><td colspan="3" class="form_title allheader" align="center"><strong>Technical Skills</strong></td></tr>
                                        <tr><td colspan="3" class="form_title"><strong>CAD Softwares:</strong><span class="red">*</span> </td></tr>
                                         <tr><td colspan="3"><textarea name="cad_software" class="required search_UI form-control"  style="min-width: 789px; max-width: 789px;" id="cad_software"><?php if(count($rowResume)>0){ echo $rowResume[0]['cad_software']; } ?></textarea></td>
                                        </tr> 
                                        <tr><td colspan="3" class="form_title"><strong>GIS Softwares:</strong><span class="red">*</span> </td></tr>
                                         <tr><td colspan="3"><textarea name="gis_software" class="required search_UI form-control"  style="min-width: 789px; max-width: 789px;" id="gis_software"><?php if(count($rowResume)>0){ echo $rowResume[0]['gis_software']; } ?></textarea></td>
                                        </tr> 
                                        <tr><td colspan="3" class="form_title"><strong>Technical Languages:</strong><span class="red">*</span> </td></tr>
                                         <tr><td colspan="3"><textarea name="tech_languages" class="required search_UI form-control"  style="min-width: 789px; max-width: 789px;" id="tech_languages"><?php if(count($rowResume)>0){ echo $rowResume[0]['tech_languages']; } ?></textarea></td>
                                        </tr>
                                        <tr><td colspan="3" class="form_title"><strong>Operating System:</strong><span class="red">*</span> </td></tr>
                                         <tr><td colspan="3"><textarea name="operating_system" class="required search_UI form-control"  style="min-width: 789px; max-width: 789px;" id="operating_system"><?php if(count($rowResume)>0){ echo $rowResume[0]['operating_system']; } ?></textarea></td>
                                        </tr> 
                                        <tr><td colspan="3" class="form_title"><strong>Others</strong> (please specify):</td></tr>
                                         <tr><td colspan="3"><textarea name="tech_others" class="search_UI form-control"  style="min-width: 789px; max-width: 789px;" id="tech_others"><?php if(count($rowResume)>0){ echo $rowResume[0]['tech_others'];} ?></textarea></td>
                                        </tr> 
                                        <tr><td colspan="3" class="form_title"><strong>Major Functional Areas:</strong> </td></tr>
                                         <tr><td colspan="3"><textarea name="functional_areas" class="ckeditor form-control"  style="min-width: 789px; max-width: 789px;" id="functional_areas"><?php if(count($rowResume)>0){ echo $rowResume[0]['functional_areas']; } ?></textarea></td>
                                        </tr> 
                                        <tr><td colspan="3" class="form_title"><strong>Professional Skills and Responsibilities:</strong></td></tr>
                                         <tr><td colspan="3"><textarea name="professional_skills_resp" class="ckeditor form-control"  style="min-width: 789px; max-width: 789px;" id="professional_skills_resp"><?php if(count($rowResume)>0){ echo $rowResume[0]['professional_skills_resp']; } ?></textarea></td>
                                        </tr> 
                                        <tr><td colspan="3" class="form_title allheader" align="center"><strong>Professional experience</strong></td></tr>
                                        <?php
										if(count($rowComp)>0){
                                        for($j=0; $j<count($rowComp); $j++){                                         
                                        ?>
                                         <tr><td colspan="3" class="form_title"><strong>Name of Company:</strong><span class="red">*</span> </td></tr>
                                         <tr>
                                             <td colspan="3">
                                                 <textarea name="company_name[]" class="required search_UI form-control"  style="min-width: 789px; max-width: 789px;" id="company_name"><?php echo $rowComp[$j]['comp_name']; ?></textarea>
                                                 <input type="hidden" name="cid[]" value="<?php   echo $rowComp[$j]['cid']; ?>" />
                                             </td>
                                        </tr> 
                                         <tr><td colspan="3" class="form_title"><strong>Designation:</strong><span class="red">*</span> </td></tr>
                                         <tr><td colspan="3"><textarea name="designation[]" class="required search_UI form-control"  style="min-width: 789px; max-width: 789px;" id="designation"><?php  echo $rowComp[$j]['designation']; ?></textarea></td>
                                        </tr> 
                                        <tr><td colspan="3" class="form_title"><strong>Year of Experience</strong><i>(Example:1st March, 2011 - 28th February, 2015)</i>:<span class="red">*</span> </td></tr>
                                         <tr><td colspan="3"><textarea name="year_exp[]" class="required search_UI form-control"  style="min-width: 789px; max-width: 789px;" id="year_exp"><?php  echo $rowComp[$j]['year_exp']; ?></textarea></td>
                                        </tr>
                                        <tr><td colspan="3" class="form_title allheader" align="center"><strong>Some important projects are as under:</strong></td></tr>      
                                        
                                        <tr>
                                            <td colspan="3">
                                                <table width="100%" border="0" id="dataTable<?php echo $j;?>" class="table table-striped table-bordered table-condensed">
                                                     <tbody>
                                                    <tr valign="top">
                                                        <th>&nbsp;</th>
                                                        <th style=""><strong>Project Name</strong></th>
                                                        <th style=""><strong>Project Description</strong></th>
                                                        <th style=""><strong>Final Product & Usage</strong></th>
                                                        <th style=""><strong>Duration</strong></th>
                                                        <th style=""><strong>Role</strong></th>
                                                        <th style=""><strong>Team Size </strong></th>
                                                    </tr>  
                                                    
                                                    <?php    
														$rowCompProject = $rowComp[$j]['rowCompProject'];
                                                        for($k=0; $k<count($rowCompProject); $k++){ 
                                                    ?>
                                                    <tr>
                                                        <td><input name="chk" type="checkbox"><input type="hidden" name="pid_<?php echo $j;?>[]" value="<?php echo $rowCompProject[$k]['pid']; ?>" /></td>                                                        
                                                        <td><textarea name="pro_name_<?php echo $j;?>[]" class="search_UI form-control"  style="min-width: 100px; max-width: 100px;"><?php echo $rowCompProject[$k]['pro_name']; ?></textarea></td>
                                                        <td><textarea name="pro_desc_<?php echo $j;?>[]" class="search_UI form-control"  style="min-width: 100px; max-width: 100px;"><?php echo $rowCompProject[$k]['pro_desc']; ?></textarea></td>
                                                        <td><textarea name="product_usage_<?php echo $j;?>[]" class="search_UI form-control"  style="min-width: 100px; max-width: 100px;"><?php echo $rowCompProject[$k]['product_usage']; ?></textarea></td>
                                                        <td><textarea name="duration_<?php echo $j;?>[]" class="search_UI form-control"  style="min-width: 50px; max-width: 50px;"><?php echo $rowCompProject[$k]['duration']; ?></textarea></td>
                                                        <td><textarea name="role_<?php echo $j;?>[]" class="search_UI form-control"  style="min-width: 100px; max-width: 100px;"><?php echo $rowCompProject[$k]['role']; ?></textarea></td>
                                                        <td><textarea name="team_size_<?php echo $j;?>[]" class="search_UI form-control"  style="min-width: 50px; max-width: 50px;"><?php echo $rowCompProject[$k]['team_size']; ?></textarea></td>
                                                    </tr> 
                                                    <?php } ?>   
                                                    </tbody>
                                                </table>
                                            </td>                                       
                                        </tr>                                        
                                        <tr>
                                            <td colspan="3">
                                                 <input value="Add Row" onclick="addRow('dataTable<?php echo $j;?>')" type="button"> 
                                                 <input value="Delete Row" onclick="deleteRow('dataTable<?php echo $j;?>')" type="button">
                                            </td>
                                        </tr>
                                        <?php } }else { ?>
                                        <tr><td colspan="3" class="form_title"><strong>Name of Company:</strong><span class="red">*</span> </td></tr>
                                         <tr>
                                             <td colspan="3">
                                                 <textarea name="company_name[]" class="required search_UI form-control"  style="min-width: 789px; max-width: 789px;" id="company_name"><?php //echo $rowComp['comp_name']; ?></textarea>
                                                 <input type="hidden" name="cid[]" value="<?php //echo $rowComp['cid']; ?>" />
                                             </td>
                                        </tr> 
                                         <tr><td colspan="3" class="form_title"><strong>Designation:</strong><span class="red">*</span> </td></tr>
                                         <tr><td colspan="3"><textarea name="designation[]" class="required search_UI form-control"  style="min-width: 789px; max-width: 789px;" id="designation"><?php //echo $rowComp['designation']; ?></textarea></td>
                                        </tr> 
                                        <tr><td colspan="3" class="form_title"><strong>Year of Experience</strong><i>(Example:1st March, 2011 - 28th February, 2015)</i>:<span class="red">*</span> </td></tr>
                                         <tr><td colspan="3"><textarea name="year_exp[]" class="required search_UI form-control"  style="min-width: 789px; max-width: 789px;" id="year_exp"><?php //echo $rowComp['year_exp']; ?></textarea></td>
                                        </tr>
                                        <tr><td colspan="3" class="form_title allheader" align="center"><strong>Some important projects are as under:</strong></td></tr>      
                                        
                                        <tr>
                                            <td colspan="3">
                                                <table width="100%" border="0" id="dataTable<?php //echo $j;?>" class="table table-striped table-bordered table-condensed">
                                                     <tbody>
                                                    <tr valign="top">
                                                        <th>&nbsp;</th>
                                                        <th style=""><strong>Project Name</strong></th>
                                                        <th style=""><strong>Project Description</strong></th>
                                                        <th style=""><strong>Final Product & Usage</strong></th>
                                                        <th style=""><strong>Duration</strong></th>
                                                        <th style=""><strong>Role</strong></th>
                                                        <th style=""><strong>Team Size </strong></th>
                                                    </tr>  
                                                    <tr> 
                                                        <td><input name="chk" type="checkbox"><input type="hidden" name="pid_0[]" value="" /></td>
                                                        <td><textarea name="pro_name_0[]" class="search_UI form-control"  style="min-width: 100px; max-width: 100px;"></textarea></td>
                                                        <td><textarea name="pro_desc_0[]" class="search_UI form-control"  style="min-width: 100px; max-width: 100px;"></textarea></td>
                                                        <td><textarea name="product_usage_0[]" class="search_UI form-control"  style="min-width: 100px; max-width: 100px;"></textarea></td>
                                                        <td><textarea name="duration_0[]" class="search_UI form-control"  style="min-width: 50px; max-width: 50px;"></textarea></td>
                                                        <td><textarea name="role_0[]" class="search_UI form-control"  style="min-width: 100px; max-width: 100px;"></textarea></td>
                                                        <td><textarea name="team_size_0[]" class="search_UI form-control"  style="min-width: 50px; max-width: 50px;"></textarea></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>                                       
                                        </tr>                                        
                                        <tr>
                                            <td colspan="3">
                                                 <input value="Add Row" onclick="addRow('dataTable<?php //echo $j;?>')" type="button"> 
                                                 <input value="Delete Row" onclick="deleteRow('dataTable<?php //echo $j;?>')" type="button">
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tr class="div_quotes">                                            
                                        </tr> 
                                        <tr>
                                            <td colspan="3"> 
                                                <input type="button" value="Addmore Company" id="Addmore">
                                            </td>
                                        </tr> 
                                        <tr><td colspan="3" class="form_title"><strong>Education : </strong><span class="red">*</span> </td></tr>
                                         <tr><td colspan="3"><textarea name="education" class="ckeditor form-control"  style="min-width: 789px; max-width: 789px;" id="education"><?php if(count($rowResume)>0) echo $rowResume[0]['education']; ?></textarea></td>
                                        </tr> 
                                        <tr><td colspan="3" class="form_title"><strong>Workshops: </strong></td></tr>
                                         <tr><td colspan="3"><textarea name="workshops" class="ckeditor form-control"  style="min-width: 789px; max-width: 789px;" id="workshops"><?php if(count($rowResume)>0) echo $rowResume[0]['workshops']; ?></textarea></td>
                                        </tr> 
                                        <tr><td colspan="3" class="form_title"><strong>Awards and Excellence: </strong> </td></tr>
                                         <tr><td colspan="3"><textarea name="awards_excellence" class="ckeditor form-control"  style="min-width: 789px; max-width: 789px;" id="discussion"><?php if(count($rowResume)>0) echo $rowResume[0]['awards_excellence']; ?></textarea></td>
                                        </tr> 
                                        <tr><td colspan="3" class="form_title allheader"><strong>Languages you know : </strong><span class="red">*</span> </td></tr>
                                        <tr>
                                            <td colspan="3">                                      
                                            <table width="97%" border="0" id="langTable" class="table table-striped table-bordered table-condensed">
                                                     <tbody>
                                                    <tr valign="top">
                                                        <th>&nbsp;</th>
                                                        <th style="width: 175px; "><strong>Languages<strong></th>
                                                        <th style=""><strong>Read<strong></th>
                                                        <th style=""><strong>Write<strong></th>
                                                        <th style=""><strong>Speak<strong></th>                                                        
                                                    </tr>
                                                    <?php 
                                                        if(count($rowLang) > 0){
                                                        for($l=0; $l<count($rowLang); $l++){
                                                    ?>
                                                    <tr>
                                                        <td><input name="chk" type="checkbox"><input type="hidden" name="lid[]" value="<?php echo $rowLang[$l]['lid']; ?>" /></td>                                                                                                              
                                                        <td><input type="text" name="languages[]" class="search_UI" rows="1" style="width: 165px;" value="<?php echo $rowLang[$l]['lname']; ?>" /></td>                                                         
                                                        <td><input type="checkbox" name="read[]" <?php if($rowLang[$l]['lread']=='reading') echo "checked"; ?> value="reading" /></td>
                                                        <td><input type="checkbox" name="write[]" <?php if($rowLang[$l]['lwrite']=='writing') echo "checked"; ?> value="writing" /></td>
                                                        <td><input type="checkbox" name="speak[]" <?php if($rowLang[$l]['lspeak']=='speaking') echo "checked"; ?> value="speaking" /></td>                                                                                                              
                                                    </tr> 
                                                    <?php } }else{ ?>   
                                                    <tr>
                                                        <td><input name="chk" type="checkbox"></td>                                                                                                              
                                                        <td><input type="text" name="languages[]" class="search_UI" rows="1" style="width: 165px;" /></td>                                                         
                                                        <td><input type="checkbox" name="read[]"  value="reading" /></td>
                                                        <td><input type="checkbox" name="write[]" value="writing" /></td>
                                                        <td><input type="checkbox" name="speak[]" value="speaking" /></td>                                                                                                              
                                                    </tr> 
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                                </td>
                                       
                                        </tr>
                                         <tr>
                                            <td colspan="3">
                                                 <input value="Add Row" onclick="addRow('langTable')" type="button"/> 
                                                 <input value="Delete Row" onclick="deleteRow('langTable')" type="button"/>
                                            </td>
                                        </tr> 
                                        <tr><td>&nbsp;</td></tr>	
							
								</table>
							<fieldset> 
							<div class="form-group">
								<label class="col-md-2 control-label" for="signup"></label>
								<div class="col-md-10"> 
									<?php if(count($rowComp) > 0){ ?>
                                    <input type="hidden" name="numComp" id="numComp" value="<?php echo ($j-1);?>" />
                                    <?php }else{ ?>
                                    <input type="hidden" name="numComp" id="numComp" value="0" />
                                    <?php } ?>
                                    <input type="submit" id="btnAddMessage" name="btnAddMessage" class="search_sbmt btn btn-info pull-right" value="SUBMIT" />
                                    <div class="clear"></div>
								</div>
							</div> 
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
<!--<script src="<?php echo base_url(); ?>assets/dist/frontend/ckeditor.js"></script>-->
<script src="https://cdn.ckeditor.com/4.9.0/standard/ckeditor.js"></script>
<script language="javascript">
$( function() {
	$('#total_score_a').show();
	//$('#section_a').hide();
	$('#total_score_b').show();
	//$('#section_b').hide();
	 <?php /* if($successMsg){?>
        $("#successMessage").html("Information has been saved successfully.").slideDown().delay(4000).slideUp();
    <?php } */ ?>          
    $('#time_period').datepicker({
        dateFormat: "dd-mm-yy"
	});
	$("#department").change(function(){
		//Get Desgination            
		deptid = this.value;
		$k.ajax({
		type: "POST",
		url: 'ajax/getEmpDesignation.php',
		data: "did="+deptid,
		success: function(data) {
			$("#desg_name").html(data);               
		},
		error: function(e) {
				alert("There is somme error in the network. Please try later.");
		}
		});
	});
    $("#Addmore").click(function(e)//on add input button click
    {    
		var content;
		var numComp = $('#numComp').val();
		numComp++;
		var tabID ='dataTable'+numComp;
		var tabInd= '"'+tabID+'"';
		content += "<tr><td colspan='3' class='form_title'><strong>Name of Company:</strong><span class='red'>*</span> </td></tr>";
		content +="<tr><td colspan='3'><textarea name='company_name[]' class='required search_UI form-control'  style='min-width: 789px; max-width: 789px;' id='company_name'></textarea><input type='hidden' name='cid[]' value='' /></td></tr>"; 
		content +="<tr><td colspan='3' class='form_title'><strong>Designation:</strong><span class='red'>*</span> </td></tr>"; 
		content +="<tr><td colspan='3'><textarea name='designation[]' class='required search_UI form-control'  style='min-width: 789px; max-width: 789px;'  id='designation'></textarea></td></tr>";  
		content +="<tr><td colspan='3' class='form_title'><strong>Year of Experience</strong><i>(Example:1st March, 2011 - 28th February, 2015)</i>:<span class='red'>*</span> </td></tr>"; 
		content +="<tr><td colspan='3'><textarea name='year_exp[]' class='required search_UI form-control'  style='min-width: 789px; max-width: 789px;'  id='year_exp'></textarea></td></tr>"; 
		content +="<tr><td colspan='3' class='form_title' align='center'><strong>Some important projects are as under:</strong></td></tr>";                                         
		content +="<tr>";
		content +="<td colspan='3'>";
		content +="<table width='100%' border='0' class='table table-striped table-bordered table-condensed' id="+tabID+">";
		content +="<tbody>";
		content +="<tr valign='top'>";
		content +="<th>&nbsp;</th>";
		content +="<th ><strong>Project Name<strong></th>";
		content +="<th ><strong>Project Description<strong></th>";
		content +="<th ><strong>Final Product & Usage<strong></th>";
		content +="<th ><strong>Duration<strong></th>";
		content +="<th ><strong>Role<strong></th>";
		content +="<th ><strong>Team Size <strong></th>";
		content +="</tr>";                  
		content +="<tr>";
		content +="<td><input name='chk' type='checkbox'><input type='hidden' name='pid_"+numComp+"[]' value='' /></td>";
		content +="<td><textarea name='pro_name_"+numComp+"[]' class='search_UI' rows='2' style='width: 100px;'></textarea></td>";
		content +="<td><textarea name='pro_desc_"+numComp+"[]' class='search_UI' rows='2' style='width: 100px;'></textarea></td>";
		content +="<td><textarea name='product_usage_"+numComp+"[]' class='search_UI' rows='2' style='width: 100px;'></textarea></td>";
		content +="<td><textarea name='duration_"+numComp+"[]' class='search_UI' rows='2' style='width: 50px;'></textarea></td>";
		content +="<td><textarea name='role_"+numComp+"[]' class='search_UI' rows='2' style='width: 100px;'></textarea></td>";
		content +="<td><textarea name='team_size_"+numComp+"[]' class='search_UI' rows='2' style='width: 40px;'></textarea></td>";
		content +="</tr>";              
		content +="</tbody>";
		content +="</table>";
		content +="</td>";  
		content +="</tr>";   
		content +="<tr>";
		content +="<td colspan='3'>";
		content +="<input value='Add Row' onclick='addRow("+tabInd+")' type='button' /> ";
		content +="<input value='Delete Row' onclick='deleteRow("+tabInd+")' type='button'/>";
		content +="</td>";
		content +="</tr>  ";
		// alert(content);
		$(".div_quotes").append(content);            
		$('#numComp').val(numComp);
	});
});

function calutaion_section_a(tot_object){        
	var total_score_a=0;
	for(var j=1; j <= tot_object ; j++){            
		var temp_score = $('#score_'+j).val();
		//alert(temp_score);
		total_score_a= +total_score_a + +temp_score;
	}
	//document.getElementById('section_a').value = total_score_a;
	$("#section_a").val(total_score_a);
	$('#section_a').show();
	$('#total_score_a').hide();
}

function calutaion_section_b(total_len){
	var total_score_b=0;
	//alert (total_len);
		var temp_score_1 = 0;  
		var temp_score_2 = 0; 
		var temp_score_3 = 0; 
		var temp_score_4 = 0; 
		var temp_score_5 = 0;
		var temp_score_6 = 0; 
		var temp_score_7 = 0; 
		var temp_score_8 = 0; 
		var temp_score_9 = 0; 
		if(total_len == 5){
			var temp_score_1 = $('#knowledge_job_fin_rating').val();            
			var temp_score_2 = $('#quality_work_fin_rating').val();
			var temp_score_3 = $('#quantity_work_fin_rating').val();
			var temp_score_4 = $('#work_attitude_fin_rating').val();
			var temp_score_5 = $('#teamwork_fin_rating').val();
		}else{
			var temp_score_1 = $('#knowledge_job_fin_rating').val();            
			var temp_score_2 = $('#quality_work_fin_rating').val();
			var temp_score_3 = $('#quantity_work_fin_rating').val();
			var temp_score_4 = $('#work_attitude_fin_rating').val();
			var temp_score_5 = $('#teamwork_fin_rating').val();
			var temp_score_6 = $('#problem_solving_fin_rating').val();            
			var temp_score_7 = $('#responsibility_fin_rating').val();
			var temp_score_8 = $('#motivation_fin_rating').val();
			var temp_score_9 = $('#delegation_work_fin_rating').val();
	}            
	total_score_b = +temp_score_1 + +temp_score_2 + +temp_score_3 + +temp_score_4 + +temp_score_5 + +temp_score_6 + +temp_score_7 + +temp_score_8 + +temp_score_9;
	var score_b = total_score_b/total_len ;  
	$("#section_b").val(score_b);
	$('#section_b').show();
	$('#total_score_b').hide();
}

function addRow(tableID) {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var row = table.insertRow(rowCount);
	var colCount = table.rows[0].cells.length;
	if(rowCount<=10){
		for (var i = 0; i < colCount; i++) {
			var newcell = row.insertCell(i);
			newcell.innerHTML = table.rows[1].cells[i].innerHTML;
			switch (newcell.childNodes[0].type) {
				case "text":
					newcell.childNodes[0].value = "";
					break;
				case "checkbox":
					newcell.childNodes[0].checked = false;
					break;
				case "select-one":
					newcell.childNodes[0].selectedIndex = 0;
					break;
			}
		}
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
</script>

<script type="text/javascript">
    CKEDITOR.replace( 'description',
    {
        toolbar : 'Basic', /* this does the magic */
        uiColor : '#9AB8F3'
    });
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
