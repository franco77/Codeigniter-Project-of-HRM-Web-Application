<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php
$get_id = "";
if (isset($_GET['id']))
{
	$get_id = $_GET['id'];
}
?>
<style>
.competency_assessment textarea.form-control {
    height: 170px;
    resize: none;
    width: 160px !important;
}
.competency_assessment .form-control, output {
    font-size: 13px;
    line-height: 1.45;
}

textarea {
	float: left;
	overflow-y: auto;
}



/*
* STYLE 3
*/
textarea::-webkit-scrollbar-track
{
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
	background-color: #F5F5F5;
}

textarea::-webkit-scrollbar
{
	width: 6px;
	background-color: #F5F5F5;
}

textarea::-webkit-scrollbar-thumb
{
	background-color: #5bc0de;
}
</style>
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
					<legend class="pkheader">Annual Appraisal Form (<small>Fill the Annual Appraisal Form Here</small>)</legend>
					<div class="row well">
						<?php //echo $noOfMonths;
							if($this->config->item('annualAppraisalFormFreeze') == 'NO') {
								if($noOfMonths >= 2 && $this->session->userdata('emp_type') == 'F'){
								$applied=0;
								if(count($rowAppraisal)>0){
									if($rowAppraisal[0]['unique_pin'] !=""){
										$applied++;
									}
								}
								if($applied == 0){
						?>
						<form class="form-horizontal" id="frmSalaryUpdate" name="frmSalaryUpdate" method="POST" action="" enctype="multipart/form-data">
							<fieldset> 
								<table class="table table-striped table-bordered table-condensed">
									<h4 class="allheader">General Guidelines:</h4>
									<tr>
										<td>
											<p>The Appraisal Form has two sections: </p>
											<br/>
											<ol style="margin-left: 25px;">
												<li>Section A (Performance Objectives) </li>
												<li>Section B (Competency Assessment) </li>
											</ol>
											<br/>                                     
											<p>Performance for different category is graded into the following: </p>
											<table class="table">
												<tr>
													<td width="12%" valign="top" style="border: 1px solid #000;">
														<p>Rating </p>
													</td>
													<td width="88%" valign="top" style="border: 1px solid #000;">
														<p> </p>
													</td>
												</tr>
												<tr>
													<td width="53" valign="top" style="border: 1px solid #000;">
														<p>
															<i class="fa fa-star" aria-hidden="true"></i>
															<i class="fa fa-star" aria-hidden="true"></i>
															<i class="fa fa-star" aria-hidden="true"></i>
															<i class="fa fa-star" aria-hidden="true"></i>
															<i class="fa fa-star" aria-hidden="true"></i>
														</p>
													</td>
													<td width="439" valign="top" style="border: 1px solid #000;">
														<p>Performance is exceptional and far exceeds the standards </p>
													</td>
												</tr>
												<tr>
													<td width="53" valign="top" style="border: 1px solid #000;">
														<p>
															<i class="fa fa-star" aria-hidden="true"></i>
															<i class="fa fa-star" aria-hidden="true"></i>
															<i class="fa fa-star" aria-hidden="true"></i>
															<i class="fa fa-star" aria-hidden="true"></i>
														</p>
													</td>
													<td width="439" valign="top" style="border: 1px solid #000;">
														<p>Performance is above standards </p>
													</td>
												</tr>
												<tr>
													<td width="53" valign="top" style="border: 1px solid #000;">
														<p>
															<i class="fa fa-star" aria-hidden="true"></i>
															<i class="fa fa-star" aria-hidden="true"></i>
															<i class="fa fa-star" aria-hidden="true"></i>
														</p>
													</td>
													<td width="439" valign="top" style="border: 1px solid #000;">
														<p>Performance is consistent and clearly meets the standards. </p>
													</td>
												</tr>
												<tr>
													<td width="53" valign="top" style="border: 1px solid #000;">
														<p>
															<i class="fa fa-star" aria-hidden="true"></i>
															<i class="fa fa-star" aria-hidden="true"></i>
														</p>
													</td>
													<td width="439" valign="top" style="border: 1px solid #000;">
														<p>Performance is short of standards but can be improved. </p>
													</td>
												</tr>
												<tr>
													<td width="53" valign="top" style="border: 1px solid #000;">
														<p>
															<i class="fa fa-star" aria-hidden="true"></i>
														</p>
													</td>
													<td width="439" valign="top" style="border: 1px solid #000;">
														<p>Performance does not meet the standards. </p>
													</td>
												</tr>
											</table>
											<br/>                                  
											<p >Any specific achievement and outstanding contribution may be supported by your remarks and justification in a separate sheet.  
												For statements that do not apply to the person being evaluated,<span style="color: red;"> please mark &#8220;Not Applicable&#8221; (NA)</span>.  
												Comments should be specific (including examples) and explanatory. 
											</p>
										</td>
									</tr>
									<tr>
										<td class="form_title" align="center"><h4 class="allheader">SECTION A - Performance Objectives</h4></td>
									</tr>
									<tr>
										<td> 
										</td>
									</tr>
									<tr>
										<td>
											<table width="100%" border="0" cellpadding="10" cellspacing="10" id="dataTable" class="table table-striped table-bordered table-condensed">
												<tbody>
													<tr valign="top"  class="info">
														<td style="border: solid 1px #000; width: 20px;">Sl No</td>
														<td style="border: solid 1px #000;  width: 50px;"><strong>Performance Objectives<strong></td>
														<td style="border: solid 1px #000;  width: 50px;"><strong>Target<strong></td>
														<td style="border: solid 1px #000;  width: 50px;"><strong>Weightage<strong></td>
														<td style="border: solid 1px #000;  width: 50px;"> <strong>Actual Achievement (by appraiser)</strong></td>
														<td style="border: solid 1px #000;  width: 50px;"><strong>Achievement in % (by appraiser) </strong></td>
														<td style="border: solid 1px #000;  width: 50px;"><strong>Score (Weightage * Achievement %) </strong></td>
													</tr>
													
													<?php 
													$i=0;
													   for($m=0; $m<count($rowGoal); $m++){
														    $i++;
													 ?>
													
                                                     <tr valign="top">
                                                        <td width="5%"><?php echo $i; ?></td>
                                                        <td width="20%">
															<textarea name="objective[]" readonly class="form-control" rows="8" ><?php echo $rowGoal[$m]['objective']; ?></textarea>
														</td>
                                                        <td width="20%">
															<textarea name="target[]" readonly class="form-control" rows="8" ><?php echo $rowGoal[$m]['target']; ?></textarea>
														</td>
                                                        <td width="10%">
															<input type="text" id="weightage_<?php echo $i; ?>" name="weightage[]" readonly class="form-control" rows="8" value="<?php echo $rowGoal[$m]['weightage']; ?>" />
														</td>
                                                        <td width="15%">
															<input type="text" onkeyup="calculatePercent_<?php echo $i; ?>(this.value);" id="act_achievement_<?php echo $i; ?>" name="act_achievement[]" class="number form-control" rows="8" value="<?php echo $rowGoal[$m]['act_achievement']; ?>" readonly />
														</td>
                                                        <td width="10%">
															<input type="text" id="achievement_per_<?php echo $i; ?>" name="achievement_per[]" readonly class="form-control" rows="8" value="<?php echo $rowGoal[$m]['achievement_per']; ?>" />
														</td>
                                                        <td width="10%">
															<input type="text" id="score_<?php echo $i; ?>" name="score[]" readonly class="form-control" rows="8" value="<?php echo $rowGoal[$m]['score']; ?>" />
														</td>
                                                        <input type="hidden" name="mid[]" value="<?php echo $rowGoal[$m]['mid']; ?>" />
                                                        <script type="text/javascript" lang="javascript">
                                                         function calculatePercent_<?php echo $i; ?>(act_achievement){
                                                             //alert(act_achievement);
                                                             var weight = $("#weightage_<?php echo $i; ?>").val();
                                                             var achievement_per = (act_achievement/weight)*100;
                                                             var score = (weight*achievement_per)/100;
                                                             $("#achievement_per_<?php echo $i; ?>").val(parseFloat((achievement_per).toFixed(2)));
                                                             $("#score_<?php echo $i; ?>").val(parseFloat((score).toFixed(2)));                                                             
                                                         }   
                                                        </script>
                                                     </tr> 
													<?php } ?>
													
												</tbody>
											</table>
										</td>
									</tr>
									<tr style="display: none;">
										<td>
											<input value="Add Row" onclick="addRow('dataTable')" type="button"> 
											<input value="Delete Row" onclick="deleteRow('dataTable')" type="button">
										</td>
									</tr>
			
									<tr>
									     <td>
											<h4 class="allheader">Total score in section A: &nbsp; 
														<input type="button"  name="total_score_a" id="total_score_a" value="Click to Show Score" onclick="calutaion_section_a(<?php echo $i; ?>);" class="btn btn-info" type="button">
														<input type="text" value="" name="section_a" id="section_a" readonly class="form-control1" style="width:150px; padding: 5px;"/> </h4> 
										 </td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>
											<div class="competency_assessment table-responsive">
											<table width="100%" border="1" id="dataTable" class="table">
												<tr>
													<td colspan="7"><h4 class="allheader">Section B- Competency Assessment </h4> </td>
												</tr> 
												<tr>
													<td colspan="7">
														<p><span style="color: red;">Please rate in the 5 POINT RATING SCALE.</span></p>
													</td>
												</tr>
												<tr class="info">
													<th>Competencies</th>
													<th>Ind. Comment</th>
													<th>Ind. Rating</th>
													<th>Sup. Comment</th>
													<th>Sup. Rating</th>
													<th>Final rating</th>
												</tr>
												<tr valign="top">
													<td>
														<strong>Job Knowledge :</strong> <span class="red">*</span> 
														<a class="tooltips">
															&nbsp;<b>?</b>&nbsp;
															<span>
																<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
																<ol>
																	<li>Possesses knowledge of work procedures and requirements of job.</li>
																	<li>Shows technical competence/skill in area of specialization.</li>
																</ol>
															</span>
														</a>
													</td>
													<td><textarea id="knowledge_job_ind_comment" name="knowledge_job_ind_comment" class="form-control" style="width:100px; margin: 5px;" ><?php if(count($rowAppraisal)>0){ echo $rowAppraisal[0]['knowledge_job_ind_comment']; } ?></textarea></td>
													
													<td><input type="text" id="knowledge_job_ind_rating" name="knowledge_job_ind_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['knowledge_job_ind_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" /></td>
													
													<td><textarea id="knowledge_job_sup_comment" name="knowledge_job_sup_comment"  class="form-control" style="width:100px; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?>> <?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['knowledge_job_sup_comment']; ?></textarea></td>
													
													<td><input type="text"  id="knowledge_job_sup_rating" name="knowledge_job_sup_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['knowledge_job_sup_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> /></td>
													
													<td>   <input type="text"  id="knowledge_job_fin_rating" name="knowledge_job_fin_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['knowledge_job_fin_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> />
													</td>
												</tr>
												<tr valign="top">
													<td>
														<strong>Quality of Work :</strong><span class="red">*</span> 
														<a class="tooltips">
															&nbsp;<b>?</b>&nbsp;
															<span>
																<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
																<ol>
																	<li>Is accurate, thorough and careful with work performed.</li>
																</ol>
															</span>
														</a>
													</td>
													<td><textarea  id="quality_work_ind_comment" name="quality_work_ind_comment" class="form-control" style="width:100px; margin: 5px;" ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['quality_work_ind_comment']; ?></textarea></td>
													
													<td>   <input type="text"  id="quality_work_ind_rating" name="quality_work_ind_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['quality_work_ind_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;"  /></td>
													
													<td>    <textarea id="quality_work_sup_comment" name="quality_work_sup_comment" class="form-control" style="width:100px; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?>><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['quality_work_sup_comment']; ?></textarea></td>
													
													<td>    <input type="text"  id="quality_work_sup_rating" name="quality_work_sup_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['quality_work_sup_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> /></td>
													
													<td>   <input type="text"  id="quality_work_fin_rating" name="quality_work_fin_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['quality_work_fin_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> />
													</td>
												</tr>
												<tr valign="top">
													<td>
														<strong>Quantity of Work :</strong><span class="red">*</span> 
														<a class="tooltips">
															&nbsp;<b>?</b>&nbsp;
															<span>
																<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
																<ol>
																	<li>Is able to handle a reasonable volume of work.</li>
																</ol>
															</span>
														</a>
													</td>
													<td valign="top"><textarea  id="quantity_work_ind_comment" name="quantity_work_ind_comment" class="form-control" style="width:100px; margin: 5px;"  ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['quantity_work_ind_comment']; ?></textarea></td>
													
													<td>   <input type="text"  id="quantity_work_ind_rating" name="quantity_work_ind_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['quantity_work_ind_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;"  /></td>
													
													<td>   <textarea  id="quantity_work_sup_comment" name="quantity_work_sup_comment" class="form-control" style="width:100px; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['quantity_work_sup_comment']; ?></textarea></td>
													
													<td>    <input type="text"  id="quantity_work_sup_rating" name="quantity_work_sup_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['quantity_work_sup_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> /></td>
													
													<td>   <input type="text"  id="quantity_work_fin_rating" name="quantity_work_fin_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['quantity_work_fin_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> />
													</td>
												</tr>
												<tr valign="top">
													<td>
														<strong>Work Attitude : </strong><span class="red">*</span> 
														<a class="tooltips">
															&nbsp;<b>?</b>&nbsp;
															<span>
																<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
																<ol>
																	<li>Displays commitment to work.</li>
																	<li>Is proactive and displays initiative.</li>
																	<li>Displays a willingness to learn.</li>
																</ol>
															</span>
														</a>
													</td>
													<td><textarea  id="work_attitude_ind_comment" name="work_attitude_ind_comment" class="form-control" style="width:100px; margin: 5px;" ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['work_attitude_ind_comment']; ?></textarea></td>
													
													<td>   <input type="text"  id="work_attitude_ind_rating" name="work_attitude_ind_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['work_attitude_ind_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;"  /></td>
													
													<td>    <textarea  id="work_attitude_sup_comment" name="work_attitude_sup_comment" class="form-control" style="width:100px; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['work_attitude_sup_comment']; ?></textarea></td>
													
													<td>    <input type="text"  id="work_attitude_sup_rating" name="work_attitude_sup_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['work_attitude_sup_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> /></td>
													
													<td>   <input type="text"  id="work_attitude_fin_rating" name="work_attitude_fin_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['work_attitude_fin_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> />
													</td>
												</tr>
												<tr valign="top">
													<td>
														<strong>Teamwork : </strong><span class="red">*</span> 
														<a class="tooltips">
															&nbsp;<b>?</b>&nbsp;
															<span>
																<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
																<ol>
																	<li>Able and willing to work effectively with others in a team.</li>
																</ol>
															</span>
														</a>
													</td>
													<td><textarea  id="teamwork_ind_comment" name="teamwork_ind_comment" class="form-control" style="width:100px; margin: 5px;" ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['teamwork_ind_comment']; ?></textarea></td>
													
													<td>    <input type="text"  id="teamwork_ind_rating" name="teamwork_ind_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['teamwork_ind_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;"   /></td>
													
													<td>   <textarea  id="teamwork_sup_comment" name="teamwork_sup_comment" class="form-control" style="width:100px; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['teamwork_sup_comment']; ?></textarea></td>
													
													<td>   <input type="text"  id="teamwork_sup_rating" name="teamwork_sup_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['teamwork_sup_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> /></td>
													
													<td>   <input type="text"  id="teamwork_fin_rating" name="teamwork_fin_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['teamwork_fin_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> />
													</td>
												</tr>
												<?php if($this->session->userdata('isAReportingManager') == 'YES') { ?>
												<tr>
													<td class="form_title" colspan="7"><h4 class="allheader">For Employees in Leadership role</h4> </td>
												</tr>
												<tr valign="top">
													<td >
														<strong>Problem Solving</strong><span class="red">*</span> 
														<a class="tooltips">
															&nbsp;<b>?</b>&nbsp;
															<span>
																<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
																<ol>
																	<li>Helps resolve staff problems on work-related matters.</li>
																	<li>Handles problem situations effectively .</li>
																</ol>
															</span>
														</a>
													</td>
													<td><textarea  id="problem_solving_ind_comment" name="problem_solving_ind_comment" class="form-control" style="width:100px; margin: 5px;"  ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['problem_solving_ind_comment']; ?></textarea></td>
													
													<td>  <input type="text"  id="problem_solving_ind_rating" name="problem_solving_ind_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['problem_solving_ind_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;"  /></td>
													
													<td>  <textarea  id="problem_solving_sup_comment" name="problem_solving_sup_comment" class="form-control" style="width:100px; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['problem_solving_sup_comment']; ?></textarea></td>
													
													<td>  <input type="text"  id="problem_solving_sup_rating" name="problem_solving_sup_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['problem_solving_sup_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> /></td>
													
													<td>   <input type="text"  id="problem_solving_fin_rating" name="problem_solving_fin_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['problem_solving_fin_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> />
													</td>
												</tr>
												<tr valign="top">
													<td >
														<strong>Responsibility</strong><span class="red">*</span> 
														<a class="tooltips">
															&nbsp;<b>?</b>&nbsp;
															<span>
																<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
																<ol>
																	<li>Is trustworthy, responsible and reliable.</li>
																</ol>
															</span>
														</a>
													</td>
													<td><textarea  id="responsibility_ind_comment" name="responsibility_ind_comment" class="form-control" style="width:100px; margin: 5px;" ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['responsibility_ind_comment']; ?></textarea></td>
													
													<td><input type="text"  id="responsibility_ind_rating" name="responsibility_ind_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['responsibility_ind_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;"  /></td>
													
													<td> <textarea  id="responsibility_sup_comment" name="responsibility_sup_comment" class="form-control" style="width:100px; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['responsibility_sup_comment']; ?></textarea></td>
													
													<td>  <input type="text"  id="responsibility_sup_rating" name="responsibility_sup_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['responsibility_sup_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> /></td>
													
													<td> <input type="text"  id="responsibility_fin_rating" name="responsibility_fin_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['responsibility_fin_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> />
													</td>
												</tr>
												<tr valign="top">
													<td >
														<strong>Motivation of Staff</strong><span class="red">*</span> 
														<a class="tooltips">
															&nbsp;<b>?</b>&nbsp;
															<span>
																<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
																<ol>
																	<li>Is a positive role model for other staff.</li>
																</ol>
															</span>
														</a>
													</td>
													<td><textarea  id="motivation_ind_comment" name="motivation_ind_comment" class="form-control" style="width:100px; margin: 5px;"  ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['motivation_ind_comment']; ?></textarea></td>
													
													<td> <input type="text"  id="motivation_ind_rating" name="motivation_ind_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['motivation_ind_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;"  /></td>
													
													<td> <textarea  id="motivation_sup_comment" name="motivation_sup_comment" class="form-control" style="width:100px; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['motivation_sup_comment']; ?></textarea></td>
													
													<td> <input type="text"  id="motivation_sup_rating" name="motivation_sup_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['motivation_sup_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> /></td>
													
													<td> <input type="text"  id="motivation_fin_rating" name="motivation_fin_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['motivation_fin_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> />
													</td>
												</tr>
												<tr valign="top">
													<td >
														<strong>Delegation of work</strong><span class="red">*</span> 
														<a class="tooltips">
															&nbsp;<b>?</b>&nbsp;
															<span>
																<img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
																<ol>
																	<li>Empowers and encourages subordinates to handle the task independently.</li>
																</ol>
															</span>
														</a>
													</td>
													<td><textarea  id="delegation_work_ind_comment" name="delegation_work_ind_comment" class="form-control" style="width:100px; margin: 5px;" ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['delegation_work_ind_comment']; ?></textarea></td>
													
													<td> <input type="text"  id="delegation_work_ind_rating" name="delegation_work_ind_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['delegation_work_ind_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" /></td>
													
													<td> <textarea  id="delegation_work_sup_comment" name="delegation_work_sup_comment" class="form-control" style="width:100px; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['delegation_work_sup_comment']; ?></textarea></td>
													
													<td> <input type="text"  id="delegation_work_sup_rating" name="delegation_work_sup_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['delegation_work_sup_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> /></td>
													
													<td>  <input type="text"  id="delegation_work_fin_rating" name="delegation_work_fin_rating" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['delegation_work_fin_rating']; ?>" class="number form-control" style="width:60px; vertical-align:top; margin: 5px;" readonly <?php if(count($rowAppraisal)>0) if(($rowAppraisal[0]['rm_status'] =='1' )){echo "readonly";} ?> />
													</td>
												</tr>
												<?php } ?>
												<tr>
													<td colspan="7">&nbsp;</td>
												</tr>
												
												<tr>
													 <td colspan="7"> 
														<h4 class="allheader">Total score in section B: &nbsp; 
														<input type="button"  name="total_score_b" id="total_score_b" value="Click to Show Score" onclick="calutaion_section_b(<?php  if(count($rowAppraisal)>0){ if(($rowAppraisal[0]['user_role'] < 5) && ($this->session->userdata('user_role') < 5) ){ echo '9'; }} else { echo '5'; }?>);" class="btn btn-info" type="button">
														<input type="text" value="" name="section_b" id="section_b" readonly class="form-control1" style="width:150px; padding: 5px;"/> </h4> 
															
													 </td>
												</tr>
										</td>
									</tr> 
											</table>
											</div>
									<tr>
										<td class="form_title" align="center"><h4 class="allheader">To be filled in by Appraisee:</h4> </td>
									</tr>
									<tr>
										<td>I. Other assignments/tasks that you would like to mention other than those stated in section- A.<span class="red">*</span> </td>
									</tr>
									<tr>
										<td><textarea name="assignments_other" class="form-control" rows="5" cols="25"  style="width: 790px;" id="assignments_other" ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['assignments_other']; ?></textarea></td>
									</tr>
									<tr>
										<td >II. Specify the key focus areas of your improvement.<span class="red">*</span> </td>
									</tr>
									<tr>
										<td><textarea name="key_improvement" class="form-control" rows="5" cols="25"  style="width: 790px;" id="key_improvement" ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['key_improvement']; ?></textarea></td>
									</tr>
									<tr>
										<td >III. Suggest the ways of improvement.<span class="red">*</span> </td>
									</tr>
									<tr>
										<td><textarea name="way_improvement" class="form-control" rows="5" cols="25"  style="width: 790px;" id="way_improvement" ><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['way_improvement']; ?></textarea></td>
									</tr>
									
									<?php if($get_id!='' && $revInfo[0]['login_id'] == $this->session->userdata('user_id')){ ?>
									<tr>
										<td class="form_title" align="center"><h4 class="allheader">To be filled in by Appraiser:</h4> </td>
									</tr>
									<tr>
										<td >I. Describe the appraisee's areas of additional responsibilities and/or other work-related achievements <span class="red">*</span> </td>
									</tr>
									<tr>
										<td><textarea name="additional_responsibilities" class="form-control" rows="5" cols="25"  style="width: 790px;" id="additional_responsibilities" <?php if(count($rowAppraisal)>0 && count($revInfo)>0){ if($rowAppraisal[0]['rm_status'] =='1' && $revInfo[0]['login_id']==$this->session->userdata('user_id')){echo "readonly";} } ?>><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['additional_responsibilities']; ?></textarea></td>
									</tr>
									<tr>
										<td >II. List the appraisee's strengths.<span class="red">*</span> </td>
									</tr>
									<tr>
										<td><textarea name="appraisee_strengths" class="form-control" rows="5" cols="25"  style="width: 790px;" id="appraisee_strengths" <?php if(count($rowAppraisal)>0 && count($revInfo)>0){ if($rowAppraisal[0]['rm_status'] =='1' && $revInfo[0]['login_id']==$this->session->userdata('user_id')){echo "readonly";} } ?>><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['appraisee_strengths']; ?></textarea></td>
									</tr>
									<tr>
										<td >III. List the areas for improvement<span class="red">*</span> </td>
									</tr>
									<tr>
										<td><textarea name="areas_improvement" class="form-control" rows="5" cols="25"  style="width: 790px;" id="areas_improvement" <?php if(count($rowAppraisal)>0 && count($revInfo)>0){ if($rowAppraisal[0]['rm_status'] =='1' && $revInfo[0]['login_id']==$this->session->userdata('user_id')){echo "readonly";} } ?>><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['areas_improvement']; ?></textarea></td>
									</tr>
									<tr>
										<td >IV. What specific plans of action, including training, will be taken to help the appraisee in their current job or for possible advancement in the company?<span class="red">*</span> </td>
									</tr>
									<tr>
										<td><textarea name="action_plans" class="form-control" rows="5" cols="25"  style="width: 790px;" id="action_plans" <?php if(count($rowAppraisal)>0 && count($revInfo)>0){ if($rowAppraisal[0]['rm_status'] =='1' && $revInfo[0]['login_id']==$this->session->userdata('user_id')){echo "readonly";} } ?>><?php if(count($rowAppraisal)>0) echo $rowAppraisal[0]['action_plans']; ?></textarea></td>
									</tr>
									
									<?php if(count($rowAppraisal)>0) if($rowAppraisal[0]['rm_status'] =='1'){ ?>
									<tr>
										<td class="form_title"><strong>Promotion Recommendation:</strong>&nbsp;&nbsp;<input type="radio" name="promotion" value="<?php if(count($rowAppraisal)>0) echo $rowAppraisal['promotion']; ?>" checked="" />fdgd</td>
									</tr>
									<tr>
										<td class="form_title"><strong>To be filled in by the Reviewing manager (Feedback/Recommendation): </strong> </td>
									</tr>
									<tr>
										<td><textarea name="recommendation" class="form-control" rows="5" cols="25"  style="width: 790px;" id="recommendation"><?php if(count($rowAppraisal)>0) echo $rowAppraisal['recommendation']; ?></textarea></td>
									</tr>
									<?php }else{ ?>
									<tr>
										<td class="form_title"><strong>Promotion Recommendation:</strong>&nbsp;&nbsp;<input type="radio" name="promotion" value="Yes" <?php if(count($rowAppraisal)>0) if($rowAppraisal[0]['promotion']=='Yes')echo "checked"; ?> /> Yes &nbsp;<input type="radio" checked="" name="promotion"  value="No" <?php if(count($rowAppraisal)>0) if($rowAppraisal[0]['promotion']=='No')echo "checked"; ?> /> No</td>
									</tr>
									<tr>
										<td><strong>Enter Unique Pin :</strong>&nbsp;&nbsp;<input type="text" placeholder="Type Here" name="unique_pin" class="form-control" rows="5" id="unique_pin" /> &nbsp;<span id="upin" style="color: red;"></span></td>
									</tr>
									<?php } ?>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<?php } ?>
								</table>
								</table>
							<fieldset> 
							<div class="form-group">
								<label class="col-md-2 control-label" for="signup"></label>
								<div class="col-md-4 pull-right"> 
                                    <input type="submit" id="btnSaveMessage" name="btnSaveMessage" class="search_sbmt btn btn-info" value="SAVE" />
									<?php if(count($rowAppraisal)>0){
										if(($rowAppraisal[0]['rm_status'] =='1') && ($rowAppraisal[0]['reporting_to'] == $this->session->userdata('user_id')) && ($get_id!='')){ ?>
											<input type="submit" id="btnRejectMessageReview" name="btnRejectMessageReview" class="search_sbmt btn btn-info" value="Reject" />
											<input type="submit" id="btnAddMessageReview" name="btnAddMessageReview" onclick="return(formValidateReview());" class="search_sbmt btn btn-info" value="<?php if($get_id!='') echo 'APPROVE'; else echo "APPLY"; ?>" />
										<?php }  else if(($rowAppraisal[0]['reporting_to'] == $this->session->userdata('user_id')) && ($get_id!='')){ ?>
											<input type="submit" id="btnRejectMessageRM" name="btnRejectMessageRM" class="search_sbmt btn btn-info" value="Reject" />
											<input type="submit" id="btnAddMessageRM" name="btnAddMessageRM" onclick="return(formValidateRM());" class="search_sbmt btn btn-info" value="<?php if($get_id!='') echo 'APPROVE'; else echo "APPLY"; ?>" />
                                    <?php } else{ ?>
											<input type="submit" id="btnAddMessage" name="btnAddMessage" onclick="return(formValidate());" class="search_sbmt btn btn-info" value="<?php  echo "APPLY"; ?>" />
                                    <?php }  } else{ ?>
											<input type="submit" id="btnAddMessage" name="btnAddMessage" onclick="return(formValidate());" class="search_sbmt btn btn-info" value="<?php  echo "APPLY"; ?>" />
                                    <?php } ?> 
									
                                    <input type="hidden" name="login_id" id="login_id" value="<?php echo $get_id;?>" />
								</div>
							</div> 
						</form>
						<?php
							} else{
						?>
							<div class="col-md-12"> <h4 style="color: red;"> Already applied  ...</h4></div>
						<?php }
							} else{
						?>
							<div class="col-md-12"> <h4 style="color: red;"> Sorry you are not elligible for Annual appraisal review ...</h4></div>
						
						<?php } 
							}
							else{
						?>
							<div class="col-md-12"> <h4 style="color: red;"> Annual appraisal review not started yet...</h4></div>
						
						<?php } ?>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
 
<script language="javascript">
$( function() {
	$('#total_score_a').show();
	//$('#section_a').hide();
	$('#total_score_b').show();
	//$('#section_b').hide();
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
	total_score_b = parseFloat(temp_score_1) + parseFloat(temp_score_2) + parseFloat(temp_score_3) + parseFloat(temp_score_4) + parseFloat(temp_score_5) + parseFloat(temp_score_6) + parseFloat(temp_score_7) + parseFloat(temp_score_8) + parseFloat(temp_score_9);
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

function formValidate(){

    var form = document.frmSalaryUpdate; 
         
    if (form.knowledge_job_ind_comment.value=='') 
	{ 	               
	    form.knowledge_job_ind_comment.focus();
        alert('Knowledge Job ind Comment');
        return false; 
    }
    if (form.knowledge_job_ind_rating.value=='') 
	{ 	                
	    form.knowledge_job_ind_rating.focus();
        alert('Knowledge Job ind Rating');
        return false; 
    }
	if (form.quality_work_ind_comment.value=='') 
	{
	    form.quality_work_ind_comment.focus();
        alert('Quality Work ind Comment');
        return false; 
    }
	
    if (form.quality_work_ind_rating.value=='') 
	{
	    form.quality_work_ind_rating.focus();
        alert('QualityWork ind Rating');
        return false; 
    }
    if (form.quantity_work_ind_comment.value=='') 
	{
	    form.quantity_work_ind_comment.focus();
		alert('Quantity Work ind Comment');
		return false; 
    }
	if (form.quantity_work_ind_rating.value=='') 
	{
		form.quantity_work_ind_rating.focus();
		alert('Quantity Work ind Rating');
		return false; 
	}
	if (form.work_attitude_ind_comment.value=='') 
	{
		form.work_attitude_ind_comment.focus();
		alert('Work Attitude ind Comment');
		return false; 
	}
	if (form.work_attitude_ind_rating.value=='') 
	{
		form.work_attitude_ind_rating.focus();
		alert('Work Attitude ind Rating');
		return false; 
	}
	if (form.teamwork_ind_comment.value=='') 
	{
		form.teamwork_ind_comment.focus();
		alert('Team Work ind Comment');
		return false; 
	}
	if (form.teamwork_ind_rating.value=='') 
	{
		form.teamwork_ind_rating.focus();
		alert('Team Work ind Rating');
		return false; 
	}
	
	/* if (form.problem_solving_ind_comment.value=='') 
	{
		form.problem_solving_ind_comment.focus();
		alert('Problem Solving ind Comment');
		return false; 
	}
	if (form.problem_solving_ind_rating.value=='') 
	{
		form.problem_solving_ind_rating.focus();
		alert('Problem Solving ind Rating');
		return false; 
	}
	if (form.responsibility_ind_comment.value=='') 
	{
		form.responsibility_ind_comment.focus();
		alert('Responsibility ind Comment');
		return false; 
	}
	if (form.responsibility_ind_rating.value=='') 
	{
		form.responsibility_ind_rating.focus();
		alert('Responsibility ind Rating');
		return false; 
	}
	if (form.motivation_ind_comment.value=='') 
	{
		form.motivation_ind_comment.focus();
		alert('Motivation ind Comment');
		return false;
	}
	if (form.motivation_ind_rating.value=='') 
	{
		form.motivation_ind_rating.focus();
		alert('Motivation ind Rating');
		return false;
	}
	if (form.delegation_work_ind_comment.value=='') 
	{
		form.delegation_work_ind_comment.focus();
		alert('Delegation Work ind Comment');
		return false;
	}
	if (form.delegation_work_ind_rating.value=='') 
	{
		form.delegation_work_ind_rating.focus();
		alert('Delegation Work ind Rating');
		return false;
	} */

	if (form.assignments_other.value=='') 
	{
		form.assignments_other.focus();
		alert('Assignments Other');
		return false;
	}
	if (form.key_improvement.value=='') 
	{
		form.key_improvement.focus();
		alert('Key Improvement');
		return false;
	}
	if (form.way_improvement.value=='') 
	{
		form.way_improvement.focus();
		alert('Way Improvement');
		return false;
	}
	else{
		return( true );
	}
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
