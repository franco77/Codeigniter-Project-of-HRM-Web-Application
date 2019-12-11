<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<legend class="pkheader">Probation Assessment Detail</legend>
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Probation Assessment Filter </legend>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Department </label>
									<div class="col-sm-8">
										<select name="searchDepartment" id="searchDepartment" class="form-control input-sm" onchange="getDesgnation(this)"> 
											<option value="" >Select</option>
											<?php 
											for($l=0; $l < count($department); $l++) 
											{?>
												<option value="<?php echo $department[$l]['dept_id']; ?>" ><?php echo $department[$l]['dept_name']; ?></option>	
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Name </label>
									<div class="col-sm-8">
										<input type="text" name="searchName"  id="searchName" class="form-control input-sm"> 
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Designation </label>
									<div class="col-sm-8">
										<select name="searchDesignation" id="searchDesignation" class="form-control input-sm"> 
											<option value="" >Select</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Emp Code </label>
									<div class="col-sm-8">
										<input type="text" name="searchEmpCode"  id="searchEmpCode" class="form-control input-sm"> 
									</div>
								</div>
							</div>
							 <div class="col-md-2">
								<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary" value="Search" onclick="advanceFilter(this);" /> 
							 </div>
						</div>
					<div class="row well">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info"> 
										<th>Name</th>
										<th>Employee Code</th>
										<th>Email</th>
										<th>Apply Date</th> 
										<!--<th>Status</th>-->
										<th>Action</th> 
									</tr>
								</thead>
								<tbody id="filterData">
									<?php 
									$result = count($probation_details);
									if($result >0)
									{
										for($i=0; $i< $result; $i++)
										{?>
										<tr>
											<td><?php echo $probation_details[$i]['name'];?></td>
											<td><?php echo $probation_details[$i]['loginhandle'];?></td>
											<td><?php echo $probation_details[$i]['email'];?></td>
											<td><?php echo date('d-m-Y',strtotime($probation_details[$i]['apply_date']));?></td>  
											<!--<td><?php if($probation_details[$i]['dh_status'] == 0){ ?>
												<img id="<?php echo urlencode($probation_details[$i]['mid']);?>" class="mrfApprove" title="Approve" style="cursor: pointer;" onclick="updateDHStatus(<?php echo $probation_details[$i]['mid'];?>,<?php echo $probation_details[$i]['login_id'];?>,'1')"  alt="" src="<?php echo base_url(); ?>assets/images/icon/approve.png" />&nbsp;&nbsp; &nbsp;&nbsp; 
                                                <img id="<?php echo urlencode($probation_details[$i]['mid']);?>" class="mrfReject" title="Reject" style="cursor: pointer;"  onclick="updateDHStatus(<?php echo $probation_details[$i]['mid'];?>,<?php echo $probation_details[$i]['login_id'];?>,'2')" alt="" src="<?php echo base_url(); ?>assets/images/icon/reject.png" />
											<?php } else if($probation_details[$i]['dh_status'] == 1){ echo  "approved"; } else{ echo "Reject";}?></td>-->
											<!--<td align="center"><a href="id=<?php echo urlencode($probation_details[$i]['mid']);?>" data-toggle="modal" data-target="#myModal"><img alt="Print" style="cursor: pointer;" src="<?php echo base_url(); ?>assets/images/printer_icon.png" /></a></td>--> 
											<td align="center"><a onclick="openNewWindow(<?php echo $probation_details[$i]['mid'];?>,<?php echo $probation_details[$i]['employee_id'];?>)"><img alt="Print" style="cursor: pointer;" src="<?php echo base_url(); ?>assets/images/printer_icon.png" /></a></td> 
										</tr> 
										<?php } 
									}
									else{
									?>
									<tr><td colspan="6" align="center">No records found</td></tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
						<!-- /.table-responsive -->
					</div> 
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div>
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">Probation Assessment Details</h4>
				</div>
				<div class="modal-body">
					<table>
						<tr>
							<td><strong>Probation Type</strong><span class="red">*</span> </td>
							<td width="10%">:</td>
							<td><?php if($rowPA['probation_type']=='1') echo "Junior members";else echo "Senior members"; ?> </td>
						</tr>
						<tr>
							<td><strong>Reporting Manager</strong><span class="red">*</span> </td>
							<td width="10%">:</td>
							<td> <?php echo $rowPA['rmName'].' ('.$rowPA['rmEmpid'].')'; ?></td>
						</tr>
						<tr>
							<td><strong>Probation under Employees</strong><span class="red">*</span> </td>
							<td width="10%">:</td>
							<td> <?php echo $rowPA['full_name'].' ('.$rowPA['loginhandle'].')'; ?></td>
						</tr>
						<tr>
							<td colspan="5" class="form_title" align="center"><strong>SECTION A</strong></td>
						</tr>
						<tr>
							<td colspan="5" class="form_title">
								Section A: for the Evaluator to complete Instructions to Evaluator:  The Supervisor or Direct Reporting Manager of the probationary employee is normally also the evaluator. 
								Only in exceptional circumstances, for example, due to interpersonal conflict or non-availability of the Reporting Manager, should an evaluator (other than the reporting officer) 
								be appointed. Evaluators should refer to the employee's job description when completing this form; the evaluation should focus on the employee's ability to perform the job duties 
								listed in the job description.  Employees should be evaluated at least three times, 4th, 8th & 12th week of their joining the Organization.  Indicate the evaluation of the employee's 
								job performance by writing a number between 1 & 5 on the blank line to the right of each attribute, in the appropriate column. Please use the following scales:
								<br/>
								1 = Unacceptable;    2 = Needs Improvement;     3 = Average;    4 = Good;      5 = Very Good
							</td>
						</tr>
						<tr>
							<td><strong>&nbsp;</strong></td>
							<td width="10%">&nbsp;</td>
							<td><input type="text" value="4th Weeks" readonly="" style="width:100px; font-weight: bold; margin-left: 30px;" />
								<input type="text" value="8th Weeks" readonly="" style="width:100px; font-weight: bold; margin-left: 30px;" />
								<input type="text" value="12th Weeks" readonly="" style="width:100px; font-weight: bold; margin-left: 30px;" />
							</td>
						</tr>
						<tr>
							<td><strong>DATE</strong><span class="red">*</span> </td>
							<td width="10%">:</td>
							<td><input type="text" readonly="readonly"  id="4thweek" value="<?php echo $rowPA['fourthweek']; ?>" name="4thweek" class="required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="8thweek" value="<?php echo $rowPA['eightthweek']; ?>" name="8thweek" class="required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="12thweek" value="<?php echo $rowPA['tweththweek']; ?>" name="12thweek" class="required form_ui" style="width:100px; margin: 10px;" />
							</td>
						</tr>
						<?php if($rowPA['probation_type']=='2'){ ?>
						<tr id='sr1'>
							<td ><strong>Problem Solving</strong><span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
								<span> <img class="callout" src="../images/callout.gif" />                                                          
								The extent to which the employee helps resolve staff problems on work-related matters.
								</span> 
								</a>
							</td>
							<td width="10%">:</td>
							<td><input type="text" readonly="readonly"  id="problem_solving_4thweek" value="<?php echo $rowPA['problem_solving_4thweek']; ?>" name="problem_solving_4thweek" class="form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="problem_solving_8thweek" value="<?php echo $rowPA['problem_solving_8thweek']; ?>" name="problem_solving_8thweek" class="form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="problem_solving_12thweek" value="<?php echo $rowPA['problem_solving_12thweek']; ?>" name="problem_solving_12thweek" class=" form_ui" style="width:100px; margin: 10px;" />
							</td>
						</tr>
						<tr id='sr2'>
							<td><strong>Motivation of Employees</strong><span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
								<span> <img class="callout" src="../images/callout.gif" />                                                          
								The extent to which the employee is a positive role model for other employees.
								</span> 
								</a>
							</td>
							<td width="10%">:</td>
							<td><input type="text" readonly="readonly" id="motivation_employees_4thweek" value="<?php echo $rowPA['motivation_employees_4thweek']; ?>" name="motivation_employees_4thweek" class="form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="motivation_employees_8thweek" value="<?php echo $rowPA['motivation_employees_8thweek']; ?>" name="motivation_employees_8thweek" class="form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="motivation_employees_12thweek" value="<?php echo $rowPA['motivation_employees_12thweek']; ?>" name="motivation_employees_12thweek" class=" form_ui" style="width:100px; margin: 10px;" />
							</td>
						</tr>
						<tr id='sr3'>
							<td><strong>Responsibility</strong><span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
								<span> <img class="callout" src="../images/callout.gif" />                                                          
								The extent to which the employee is trustworthy, responsible and reliable.
								</span> 
								</a>
							</td>
							<td width="10%">:</td>
							<td><input type="text" readonly="readonly" id="responsibility_4thweek" value="<?php echo $rowPA['responsibility_4thweek']; ?>" name="responsibility_4thweek" class="form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="responsibility_8thweek" value="<?php echo $rowPA['responsibility_8thweek']; ?>" name="responsibility_8thweek" class="form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="responsibility_12thweek" value="<?php echo $rowPA['responsibility_12thweek']; ?>" name="responsibility_12thweek" class=" form_ui" style="width:100px; margin: 10px;" />
							</td>
						</tr>
						<?php } if($rowPA['probation_type']=='1'){ ?>    
						<tr id='jr1'>
							<td><strong>Quantity of Work</strong><span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
								<span> <img class="callout" src="../images/callout.gif" />                                                          
								The extent to which the employee accomplishes assigned work of a specified quality within a specified time period.
								</span> 
								</a>
							</td>
							<td width="10%">:</td>
							<td><input type="text" readonly="readonly" id="quantity_work_4thweek" value="<?php echo $rowPA['quantity_work_4thweek']; ?>" name="quantity_work_4thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="quantity_work_8thweek" value="<?php echo $rowPA['quantity_work_8thweek']; ?>" name="quantity_work_8thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="quantity_work_12thweek" value="<?php echo $rowPA['quantity_work_12thweek']; ?>" name="quantity_work_12thweek" class=" number required form_ui" style="width:100px; margin: 10px;" />
							</td>
						</tr>
						<tr id='jr2'>
							<td> <strong>Relations With Supervisor </strong> <span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
								<span> <img class="callout" src="../images/callout.gif" />                                                          
								The extent to which the employee accomplishes assigned work of a specified quality within a specified time period.
								</span> 
								</a>
							</td>
							<td width="10%">:</td>
							<td><input type="text" readonly="readonly" id="relations_supervisor_4thweek" value="<?php echo $rowPA['relations_supervisor_4thweek']; ?>" name="relations_supervisor_4thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="relations_supervisor_8thweek" value="<?php echo $rowPA['relations_supervisor_8thweek']; ?>" name="relations_supervisor_8thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="relations_supervisor_12thweek" value="<?php echo $rowPA['relations_supervisor_12thweek']; ?>" name="relations_supervisor_12thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
							</td>
						</tr>
						<tr id='jr3'>
							<td> <strong>Attendance And Reliability</strong><span class="red">*</span><a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
								<span> <img class="callout" src="../images/callout.gif" />                                                          
								The extent to which the employee accomplishes assigned work of a specified quality within a specified time period.
								</span> 
								</a>
							</td>
							<td width="10%">:</td>
							<td><input type="text" readonly="readonly" id="attendance_reliability_4thweek" value="<?php echo $rowPA['attendance_reliability_4thweek']; ?>" name="attendance_reliability_4thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="attendance_reliability_8thweek" value="<?php echo $rowPA['attendance_reliability_8thweek']; ?>" name="attendance_reliability_8thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="attendance_reliability_12thweek" value="<?php echo $rowPA['attendance_reliability_12thweek']; ?>" name="attendance_reliability_12thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
							</td>
						</tr>
						<tr id='jr4'>
							<td> <strong>Capacity To Develop</strong><span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
								<span> <img class="callout" src="../images/callout.gif" />                                                          
								The extent to which the employee accomplishes assigned work of a specified quality within a specified time period.
								</span> 
								</a>
							</td>
							<td width="10%">:</td>
							<td><input type="text" readonly="readonly" id="capacity_develop_4thweek" value="<?php echo $rowPA['capacity_develop_4thweek']; ?>" name="capacity_develop_4thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="capacity_develop_8thweek" value="<?php echo $rowPA['capacity_develop_8thweek']; ?>" name="capacity_develop_8thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="capacity_develop_12thweek" value="<?php echo $rowPA['capacity_develop_12thweek']; ?>" name="capacity_develop_12thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
							</td>
						</tr>
						<?php } ?>
						<tr>
							<td><strong>Quality of Work</strong><span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
								<span> <img class="callout" src="../images/callout.gif" />                                                          
								The extent to which the employee's work is well executed, thorough, effective, accurate.
								</span> 
								</a>
							</td>
							<td width="10%">:</td>
							<td><input type="text" readonly="readonly" id="quality_work_4thweek" value="<?php echo $rowPA['quality_work_4thweek']; ?>" name="quality_work_4thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="quality_work_8thweek" value="<?php echo $rowPA['quality_work_8thweek']; ?>" name="quality_work_8thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="quality_work_12thweek" value="<?php echo $rowPA['quality_work_12thweek']; ?>" name="quality_work_12thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
							</td>
						</tr>
						<tr>
							<td> <strong>Knowledge of Job </strong> <span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
								<span> <img class="callout" src="../images/callout.gif" />                                                          
								The extent to which the employee knows and demonstrates how and why to do all phases of assigned work, given the employee's length of time in his/her current position.
								</span> 
								</a>
							</td>
							<td width="10%">:</td>
							<td><input type="text" readonly="readonly" id="knowledge_job_4thweek" value="<?php echo $rowPA['knowledge_job_4thweek']; ?>" name="knowledge_job_4thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="knowledge_job_8thweek" value="<?php echo $rowPA['knowledge_job_8thweek']; ?>" name="knowledge_job_8thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="knowledge_job_12thweek" value="<?php echo $rowPA['knowledge_job_12thweek']; ?>" name="knowledge_job_12thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
							</td>
						</tr>
						<tr>
							<td> <strong>Co-Operation With Others </strong><span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
								<span> <img class="callout" src="../images/callout.gif" />                                                          
								The extent to which the employee gets along with other individuals.  Consider the employee's tact, courtesy, and effectiveness in dealing with co-workers, subordinates supervisors, and customers.
								</span> 
								</a>
							</td>
							<td width="10%">:</td>
							<td><input type="text" readonly="readonly" id="cooperation_others_4thweek" value="<?php echo $rowPA['cooperation_others_4thweek']; ?>" name="cooperation_others_4thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="cooperation_others_8thweek" value="<?php echo $rowPA['cooperation_others_8thweek']; ?>" name="cooperation_others_8thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="cooperation_others_12thweek" value="<?php echo $rowPA['cooperation_others_12thweek']; ?>" name="cooperation_others_12thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
							</td>
						</tr>
						<tr>
							<td> <strong>Initiative And Creativity </strong> <span class="red">*</span> <a href="#" class="tooltip"> &nbsp;<b>?</b>&nbsp;
								<span> <img class="callout" src="../images/callout.gif" />                                                          
								The extent to which the employee is self-  directed, resourceful and creative in meeting job objectives; consider how well the employee follows through on assignments and modifies or develops new ideas, 
								methods, or procedures to effectively meet changing circumstances.
								</span> 
								</a>
							</td>
							<td width="10%">:</td>
							<td><input type="text" readonly="readonly" id="initiative_creativity_4thweek" value="<?php echo $rowPA['initiative_creativity_4thweek']; ?>" name="initiative_creativity_4thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="initiative_creativity_8thweek" value="<?php echo $rowPA['initiative_creativity_8thweek']; ?>" name="initiative_creativity_8thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
								<input type="text" readonly="readonly" id="initiative_creativity_12thweek" value="<?php echo $rowPA['initiative_creativity_12thweek']; ?>" name="initiative_creativity_12thweek" class="number required form_ui" style="width:100px; margin: 10px;" />
							</td>
						</tr>
						<tr>
							<td colspan="3" class="form_title" align="center"><strong>SECTION B</strong></td>
						</tr>
						<tr>
							<td colspan="3" class="form_title">Describe your new employee's performance / conduct.<span class="red">*</span> </td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						<tr>
							<td colspan="3"><?php echo $rowPA['employee_performance']; ?></td>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3" class="form_title">Does this employee demonstrate the expertise and general skill level you expected based on the job application and interview?<span class="red">*</span>
								&nbsp;&nbsp;<input type="radio" name="expectations" value="Yes" <?php if($rowPA['expectations'] =='Yes') echo 'checked';?>>&nbsp;Yes &nbsp; <input type="radio" name="expectations" value="No" <?php if($rowPA['expectations'] =='No') echo 'checked';?>>&nbsp;No <br/> If no, in what way does this employee's performance differ from your expectations?
							</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						<tr>
							<td colspan="3"><?php echo $rowPA['your_expectations']; ?></td>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3" class="form_title">Do you consider this employee to be making progress appropriate to their length of employment?<span class="red">*</span>
								<br/><input type="radio" name="improvement" value="Yes" <?php if($rowPA['improvement'] =='Yes') echo 'checked';?>>&nbsp;Yes &nbsp; <input type="radio" name="improvement" value="No" <?php if($rowPA['improvement'] =='No') echo 'checked';?>>&nbsp;No <br/> If no, please describe the areas that need improvement?
							</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						<tr>
							<td colspan="3"><?php echo $rowPA['need_improvement']; ?></td>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3" class="form_title">Have you made arrangements for the employee to receive additional training?<span class="red">*</span>
								&nbsp;&nbsp;<input type="radio" name="training" value="Yes" <?php if($rowPA['training'] =='Yes') echo 'checked';?>>&nbsp;Yes &nbsp; <input type="radio" name="training" value="No" <?php if($rowPA['training'] =='No') echo 'checked';?>>&nbsp;No <br/> If yes, what training? Where conducted?
							</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						<tr>
							<td colspan="3"><?php echo $rowPA['additional_training']; ?></td>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3" class="form_title">Have you spoken to the employee about areas of concern at any time other than during this probationary review?<span class="red">*</span>
								&nbsp;&nbsp;<input type="radio" name="reaction" value="Yes" <?php if($rowPA['reaction'] =='Yes') echo 'checked';?>>&nbsp;Yes &nbsp; <input type="radio" name="reaction" value="No" <?php if($rowPA['reaction'] =='No') echo 'checked';?>>&nbsp;No <br/> If yes, what was the employee's reaction to the discussion?
							</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						<tr>
							<td colspan="3"><?php echo $rowPA['employee_reaction']; ?></td>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3" class="form_title">What goals have you and this employee set for the next few weeks / months on the job?</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						<tr>
							<td colspan="3"><?php echo $rowPA['set_employee_goals']; ?></td>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3" class="form_title">Does it seem probable that this employee will satisfactorily complete the probationary period?<span class="red">*</span>
								<br/><input type="radio" name="satisfactorily" value="Yes" <?php if($rowPA['satisfactorily'] =='Yes') echo 'checked';?>>&nbsp;Yes &nbsp; <input type="radio" name="satisfactorily" value="No" <?php if($rowPA['satisfactorily'] =='No') echo 'checked';?>>&nbsp;No <br/> If no, please explain?
							</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						<tr>
							<td colspan="3"><?php echo $rowPA['employee_satisfactorily']; ?></td>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3" class="form_title">Any additional Comments or Concern?</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						<tr>
							<td colspan="3"><?php echo $rowPA['additional_comments']; ?></td>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" >Yes</button>
				</div>
			</div>
		</div>
	</div>
</div>
