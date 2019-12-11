<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<?php
$get_id = "";
if (isset($_GET['employee_id']))
{
	$get_id = "?employee_id=".$_GET['employee_id'];
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
					<legend class="pkheader">Probation Assessment Form (<small>Fill the Probation Assessment Form Here</small>)</legend> 
                    <div class="row well">
                        <form class="form-horizontal" id="frmSalaryUpdate" name="frmSalaryUpdate" method="POST" action="" enctype="multipart/form-data">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="name">Probation under Employees</label>  
                                    <div class="col-md-4">
                                        <select id="employee_id" name="employee_id" class="form-control">
                                            <option value="">Select</option>
                                            <?php
                                                $res = count($pdetails);
                                                for($i=0;$i<$res;$i++)
                                                {?>
                                            <?php //echo $pdetails[$i]['login_id'];?>										
                                            <option value="<?php echo $pdetails[$i]['login_id']?>" <?php if($employee_id == $pdetails[$i]['login_id']){ echo 'selected="selected"'; } ?> ><?php echo $pdetails[$i]['loginhandle'].' ('.$pdetails[$i]['full_name'].')'; ?></option>
                                            <?php }
                                                ?>  
                                        </select>
                                    </div>  
									<label class="col-md-2 control-label" for="name">Probation Type</label>  
									<div class="col-md-4">
										<select id="probation_type" name="probation_type" class="form-control">
											<option value="">Select</option>
											<option value="1" <?php if(count($paRow)>0){ if($paRow[0]['probation_type'] == '1'){ echo 'selected="selected"'; } }?> >Junior members</option>
											<option value="2" <?php if(count($paRow)>0){ if($paRow[0]['probation_type'] == '2'){ echo 'selected="selected"'; } }?> >Senior members</option>
										</select>
									</div>
									
								</div> 
								<h4 class="allheader">SECTION A</h4> 
								<p>Section A: for the Evaluator to complete Instructions to Evaluator:  The Supervisor or Direct Reporting Manager of the probationary employee is normally also the evaluator. 
									Only in exceptional circumstances, for example, due to interpersonal conflict or non-availability of the Reporting Manager, should an evaluator (other than the reporting officer) 
									be appointed. Evaluators should refer to the employee's job description when completing this form; the evaluation should focus on the employee's ability to perform the job duties 
									listed in the job description.  Employees should be evaluated at least three times, 4th, 8th & 12th week of their joining the Organization.  Indicate the evaluation of the employee's 
									job performance by writing a number between 1 & 5 on the blank line to the right of each attribute, in the appropriate column. Please use the following scales:
									<br/>
									<div class="btn-group" role="group" aria-label="...">
										  <button type="button" class="btn btn-danger">1:Unacceptable</button>
										  <button type="button" class="btn btn-inverse">2:Needs Improvement</button>
										  <button type="button" class="btn btn-info">3:Average</button>
										  <button type="button" class="btn btn-primary">4:Good</button>
										  <button type="button" class="btn btn-success">5:Very Good</button>
									</div>
									
								</p>
								<table class="table table-striped table-bordered table-condensed">
									<tbody>
										<tr>
											<td style="width:25%;"><strong>&nbsp;</strong></td>
											
											<td style="width:75%;">
												<p class="paf_heading">
													4<sup>th</sup> Weeks
												</p>
												<p class="paf_heading">
													8<sup>th</sup> Weeks
												</p>
												<p class="paf_heading">
													12<sup>th</sup> Weeks
												</p>
											</td>
										</tr>
										<tr>
											<td><strong>Date<span class="red">*</span> </strong></td>
											
											<td>
											<div class="form-row"> 
												<div class="col-md-4">
													<!--<input type="text"  id="fourweek" value="<?php if(count($paRow)>0) if($paRow[0]['4thweek']!='1970-01-01') echo date('d-m-Y',strtotime($paRow[0]['4thweek'])); ?>" name="fourweek" class=" form-control"  />-->
													<input type="text"  id="fourweek" value="<?php if($fourweek!='') echo date('d-m-Y',strtotime($fourweek)); ?>" name="fourweek" class=" form-control"  />
												</div>
												<div class="col-md-4">
													<!--<input type="text"  id="eightweek" value="<?php if(count($paRow)>0) if($paRow[0]['8thweek']!='1970-01-01') echo date('d-m-Y',strtotime($paRow[0]['8thweek'])); ?>" name="eightweek" class=" form-control"  />-->
													<input type="text"  id="eightweek" value="<?php if($eightweek!='') echo date('d-m-Y',strtotime($eightweek)); ?>" name="eightweek" class=" form-control"  />
												</div>
												<div class="col-md-4">
													<!--<input type="text"  id="twelveweek" value="<?php if(count($paRow)>0) if($paRow[0]['12thweek']!='1970-01-01') echo date('d-m-Y',strtotime($paRow[0]['12thweek'])); ?>" name="twelveweek" class=" form-control" />-->
													<input type="text"  id="twelveweek" value="<?php if($twelveweek!='') echo date('d-m-Y',strtotime($twelveweek)); ?>" name="twelveweek" class=" form-control"  />
												</div>
											</div>
											</td>
										</tr>
										<tr id='sr1'>
											<td ><strong>Problem Solving<span class="red">*</span></strong> <a href="#" class="tooltips"> &nbsp;<b>?</b>&nbsp;
												<span> <img class="callout" src="<?php echo base_url('assets/images/callout.gif')?>" /> 
												The extent to which the employee helps resolve staff problems on work-related matters.
												</span> 
												</a>
											</td>
											
											<td>
												<div class="form-row">
													<div class="col-md-4"> <input type="text"  id="problem_solving_4thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['problem_solving_4thweek']; ?>" name="problem_solving_4thweek" class="form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="problem_solving_8thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['problem_solving_8thweek']; ?>" name="problem_solving_8thweek" class="form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="problem_solving_12thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['problem_solving_12thweek']; ?>" name="problem_solving_12thweek" class=" form-control"/> </div>
												</div>
											</td>
										</tr>
										<tr id='sr2'>
											<td><strong>Motivation of Employees<span class="red">*</span> </strong><a href="#" class="tooltips"> &nbsp;<b>?</b>&nbsp;
												<span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
												The extent to which the employee is a positive role model for other employees.
												</span> 
												</a>
											</td>
											
											<td>
												<div class="form-row">
													<div class="col-md-4"> <input type="text"  id="motivation_employees_4thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['motivation_employees_4thweek']; ?>" name="motivation_employees_4thweek" class="form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="motivation_employees_8thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['motivation_employees_8thweek']; ?>" name="motivation_employees_8thweek" class="form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="motivation_employees_12thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['motivation_employees_12thweek']; ?>" name="motivation_employees_12thweek" class=" form-control"/> </div>
												</div>
											</td>
										</tr>
										<tr id='sr3'>
											<td><strong>Responsibility<span class="red">*</span> </strong><a href="#" class="tooltips"> &nbsp;<b>?</b>&nbsp;
												<span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
												The extent to which the employee is trustworthy, responsible and reliable.
												</span> 
												</a>
											</td>
											
											<td>
												<div class="form-row">
													<div class="col-md-4"> <input type="text"  id="responsibility_4thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['responsibility_4thweek']; ?>" name="responsibility_4thweek" class="form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="responsibility_8thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['responsibility_8thweek']; ?>" name="responsibility_8thweek" class="form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="responsibility_12thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['responsibility_12thweek']; ?>" name="responsibility_12thweek" class=" form-control"/> </div>
												</div>
											</td>
										</tr>
										<tr id='jr1'>
											<td><strong>Quantity of Work<span class="red">*</span></strong> <a href="#" class="tooltips"> &nbsp;<b>?</b>&nbsp;
												<span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
												The extent to which the employee accomplishes assigned work of a specified quality within a specified time period.
												</span> 
												</a>
											</td>
											
											<td>
												<div class="form-row">
													<div class="col-md-4"> <input type="text"  id="quantity_work_4thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['quantity_work_4thweek']; ?>" name="quantity_work_4thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="quantity_work_8thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['quantity_work_8thweek']; ?>" name="quantity_work_8thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="quantity_work_12thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['quantity_work_12thweek']; ?>" name="quantity_work_12thweek" class=" number  form-control"/> </div>
												</div>
											</td>
										</tr>
										<tr id='jr2'>
											<td> <strong>Relations With Supervisor<span class="red">*</span> </strong><a href="#" class="tooltips"> &nbsp;<b>?</b>&nbsp;
												<span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
												The extent to which the employee accomplishes assigned work of a specified quality within a specified time period.
												</span> 
												</a>
											</td>
											
											<td>
												<div class="form-row">
													<div class="col-md-4"> <input type="text"  id="relations_supervisor_4thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['relations_supervisor_4thweek']; ?>" name="relations_supervisor_4thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="relations_supervisor_8thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['relations_supervisor_8thweek']; ?>" name="relations_supervisor_8thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="relations_supervisor_12thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['relations_supervisor_12thweek']; ?>" name="relations_supervisor_12thweek" class="number  form-control"/> </div>
												</div>
											</td>
										</tr>
										<tr id='jr3'>
											<td><strong>Attendance And Reliability<span class="red">*</span></strong><a href="#" class="tooltips"> &nbsp;<b>?</b>&nbsp;
												<span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
												The extent to which the employee accomplishes assigned work of a specified quality within a specified time period.
												</span> 
												</a>
											</td>
											
											<td>
												<div class="form-row">
													<div class="col-md-4"> <input type="text"  id="attendance_reliability_4thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['attendance_reliability_4thweek']; ?>" name="attendance_reliability_4thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="attendance_reliability_8thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['attendance_reliability_8thweek']; ?>" name="attendance_reliability_8thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="attendance_reliability_12thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['attendance_reliability_12thweek']; ?>" name="attendance_reliability_12thweek" class="number  form-control"/> </div>
												</div>
											</td>
										</tr>
										<tr id='jr4'>
											<td><strong>Capacity To Develop<span class="red">*</span></strong><a href="#" class="tooltips"> &nbsp;<b>?</b>&nbsp;
												<span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
												The extent to which the employee accomplishes assigned work of a specified quality within a specified time period.
												</span> 
												</a>
											</td>
											
											<td>
												<div class="form-row">
													<div class="col-md-4"> <input type="text"  id="capacity_develop_4thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['capacity_develop_4thweek']; ?>" name="capacity_develop_4thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="capacity_develop_8thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['capacity_develop_8thweek']; ?>" name="capacity_develop_8thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="capacity_develop_12thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['capacity_develop_12thweek']; ?>" name="capacity_develop_12thweek" class="number  form-control"/> </div>
												</div>
											</td>
										</tr>
										<tr>
											<td><strong>Quality of Work<span class="red">*</span></strong><a href="#" class="tooltips"> &nbsp;<b>?</b>&nbsp;
												<span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
												The extent to which the employee's work is well executed, thorough, effective, accurate.
												</span> 
												</a>
											</td>
											
											<td>
												<div class="form-row">
													<div class="col-md-4"> <input type="text"  id="quality_work_4thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['quality_work_4thweek']; ?>" name="quality_work_4thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="quality_work_8thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['quality_work_8thweek']; ?>" name="quality_work_8thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="quality_work_12thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['quality_work_12thweek']; ?>" name="quality_work_12thweek" class="number  form-control"/> </div>
												</div>
											</td>
										</tr>
										<tr>
											<td><strong>Knowledge of Job  <span class="red">*</span></strong><a href="#" class="tooltips"> &nbsp;<b>?</b>&nbsp;
												<span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
												The extent to which the employee knows and demonstrates how and why to do all phases of assigned work, given the employee's length of time in his/her current position.
												</span> 
												</a>
											</td>
											
											<td>
												<div class="form-row">
													<div class="col-md-4"> <input type="text"  id="knowledge_job_4thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['knowledge_job_4thweek']; ?>" name="knowledge_job_4thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="knowledge_job_8thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['knowledge_job_8thweek']; ?>" name="knowledge_job_8thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="knowledge_job_12thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['knowledge_job_12thweek']; ?>" name="knowledge_job_12thweek" class="number  form-control"/> </div>
												</div>
											</td>
										</tr>
										<tr>
											<td><strong>Co-Operation With Others <span class="red">*</span></strong><a href="#" class="tooltips"> &nbsp;<b>?</b>&nbsp;
												<span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
												The extent to which the employee gets along with other individuals.  Consider the employee's tact, courtesy, and effectiveness in dealing with co-workers, subordinates supervisors, and customers.
												</span> 
												</a>
											</td>
											
											<td>
												<div class="form-row">
													<div class="col-md-4"> <input type="text"  id="cooperation_others_4thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['cooperation_others_4thweek']; ?>" name="cooperation_others_4thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="cooperation_others_8thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['cooperation_others_8thweek']; ?>" name="cooperation_others_8thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="cooperation_others_12thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['cooperation_others_12thweek']; ?>" name="cooperation_others_12thweek" class="number  form-control"/> </div>
												</div>
											</td>
										</tr>
										<tr>
											<td><strong>Initiative And Creativity<span class="red">*</span></strong> <a href="#" class="tooltips"> &nbsp;<b>?</b>&nbsp;
												<span> <img class="callout" src="<?php echo base_url(); ?>assets/images/callout.gif" />                                                          
												The extent to which the employee is self-  directed, resourceful and creative in meeting job objectives; consider how well the employee follows through on assignments and modifies or develops new ideas, 
												methods, or procedures to effectively meet changing circumstances.
												</span> 
												</a>
											</td>
											
											<td>
												<div class="form-row">
													<div class="col-md-4"> <input type="text"  id="initiative_creativity_4thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['initiative_creativity_4thweek']; ?>" name="initiative_creativity_4thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="initiative_creativity_8thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['initiative_creativity_8thweek']; ?>" name="initiative_creativity_8thweek" class="number  form-control"/> </div>
													<div class="col-md-4"> <input type="text"  id="initiative_creativity_12thweek" value="<?php if(count($paRow)>0) echo $paRow[0]['initiative_creativity_12thweek']; ?>" name="initiative_creativity_12thweek" class="number  form-control"/> </div>
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="3" class="form_title" align="center"><h4 class="allheader">SECTION B</h4> </td>
										</tr>
										<tr>
											<td colspan="3" class="form_title">Describe your new employee's performance / conduct.<span class="red">*</span> </td>
										</tr>
										<tr>
											<td colspan="3"><textarea name="employee_performance" class=" form-control" rows="3" cols="25"   id="employee_performance"><?php if(count($paRow)>0) echo $paRow[0]['employee_performance']; ?></textarea></td>
										</tr>
										<tr>
											<td colspan="3" class="form_title">Does this employee demonstrate the expertise and general skill level you expected based on the job application and interview?<span class="red">*</span>
												&nbsp;&nbsp;<input type="radio" name="expectations" value="Yes" <?php if(count($paRow)>0) if($paRow[0]['expectations'] =='Yes') echo 'checked';?>>&nbsp;Yes &nbsp; <input type="radio" name="expectations" value="No" <?php if(count($paRow)>0) if($paRow[0]['expectations'] =='No') echo 'checked';?>>&nbsp;No <br/> If no, in what way does this employee's performance differ from your expectations?
											</td>
										</tr>
										<tr>
											<td colspan="3"><textarea name="your_expectations" class=" form-control" rows="3" cols="10"   id="your_expectations"><?php if(count($paRow)>0) echo $paRow[0]['your_expectations']; ?></textarea></td>
										</tr>
										<tr>
											<td colspan="3" class="form_title">Do you consider this employee to be making progress appropriate to their length of employment?<span class="red">*</span>
												<br/><input type="radio" name="improvement" value="Yes" <?php if(count($paRow)>0) if($paRow[0]['improvement'] =='Yes') echo 'checked';?>>&nbsp;Yes &nbsp; <input type="radio" name="improvement" value="No" <?php if(count($paRow)>0) if($paRow[0]['improvement'] =='No') echo 'checked';?>>&nbsp;No <br/> If no, please describe the areas that need improvement?
											</td>
										</tr>
										<tr>
											<td colspan="3"><textarea name="need_improvement" class=" form-control" rows="3" cols="10"   id="need_improvement"><?php if(count($paRow)>0) echo $paRow[0]['need_improvement']; ?></textarea></td>
										</tr>
										<tr>
											<td colspan="3" class="form_title">Have you made arrangements for the employee to receive additional training?<span class="red">*</span>
												&nbsp;&nbsp;<input type="radio" name="training" value="Yes" <?php if(count($paRow)>0) if($paRow[0]['training'] =='Yes') echo 'checked';?>>&nbsp;Yes &nbsp; <input type="radio" name="training" value="No" <?php if(count($paRow)>0) if($paRow[0]['training'] =='No') echo 'checked';?>>&nbsp;No <br/> If yes, what training? Where conducted?
											</td>
										</tr>
										<tr>
											<td colspan="3"><textarea name="additional_training" class=" form-control" rows="3" cols="10"   id="additional_training"><?php if(count($paRow)>0) echo $paRow[0]['additional_training']; ?></textarea></td>
										</tr>
										<tr>
											<td colspan="3" class="form_title">Have you spoken to the employee about areas of concern at any time other than during this probationary review?<span class="red">*</span>
												&nbsp;&nbsp;<input type="radio" name="reaction" value="Yes" <?php if(count($paRow)>0) if($paRow[0]['reaction'] =='Yes') echo 'checked';?>>&nbsp;Yes &nbsp; <input type="radio" name="reaction" value="No" <?php if(count($paRow)>0) if($paRow[0]['reaction'] =='No') echo 'checked';?>>&nbsp;No <br/> If yes, what was the employee's reaction to the discussion?
											</td>
										</tr>
										<tr>
											<td colspan="3"><textarea name="employee_reaction" class=" form-control" rows="3" cols="10"   id="employee_reaction"><?php if(count($paRow)>0) echo $paRow[0]['employee_reaction']; ?></textarea></td>
										</tr>
										<tr>
											<td colspan="3" class="form_title">What goals have you and this employee set for the next few weeks / months on the job?</td>
										</tr>
										<tr>
											<td colspan="3"><textarea name="set_employee_goals" class=" form-control" rows="3" cols="10"   id="set_employee_goals"><?php if(count($paRow)>0) echo $paRow[0]['set_employee_goals']; ?></textarea></td>
										</tr>
										<tr>
											<td colspan="3" class="form_title">Does it seem probable that this employee will satisfactorily complete the probationary period?<span class="red">*</span>
												<br/><input type="radio" name="satisfactorily" value="Yes" <?php if(count($paRow)>0) if($paRow[0]['satisfactorily'] =='Yes') echo 'checked';?>>&nbsp;Yes &nbsp; <input type="radio" name="satisfactorily" value="No" <?php if(count($paRow)>0) if($paRow[0]['satisfactorily'] =='No') echo 'checked';?>>&nbsp;No <br/> If no, please explain?
											</td>
										</tr>
										<tr>
											<td colspan="3"><textarea name="employee_satisfactorily" class=" form-control" rows="3" cols="10"   id="employee_satisfactorily"><?php if(count($paRow)>0) echo $paRow[0]['employee_satisfactorily']; ?></textarea></td>
										</tr>
										<tr>
											<td colspan="3" class="form_title">Any additional Comments or Concern?</td>
										</tr>
										<tr>
											<td colspan="3"><textarea name="additional_comments" class=" form-control" rows="3" cols="10"   id="additional_comments"><?php if(count($paRow)>0) echo $paRow[0]['additional_comments']; ?></textarea></td>
										</tr>
									</tbody>
								</table> 
                                <div class="form-group">
                                    <label class="col-md-2 control-label" for="signup"></label>
                                    <div class="col-md-3 pull-right"> 
                                        <input type="submit" id="btnSaveMessage" name="btnSaveMessage" class="btn btn-info" value="SAVE" <?php if($get_id == ""){ echo "disabled"; } ?> />
                                        <input type="submit" id="btnAddMessage" name="btnAddMessage" class="btn btn-info" value="APPLY" <?php if($get_id == ""){ echo "disabled"; } ?> /> 
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
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
</style>