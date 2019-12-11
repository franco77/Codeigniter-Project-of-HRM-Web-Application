<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>  
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
					<legend class="pkheader">Company</legend>
					<div class="row well">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed" cellpadding="0" cellspacing="0" width="100%">
								<tbody>
									<tr>
										<td><strong>Employee Type:</strong></td>
										<td>
											<?php if($empInfo[0]['emp_type'] == "F"){
												echo "Full Time";
											}elseif($empInfo[0]['emp_type'] == "C"){
												echo "Contractual";
											}elseif($empInfo[0]['emp_type'] == "I"){
												echo "Interns";
											} 
											?>
										</td>
										<td><strong>Shift:</strong></td>
										<td>
											<?php if($empInfo[0]['shift'] == "NS"){
												echo "Night Shift";
											}elseif($empInfo[0]['shift'] == "MS"){
												echo "Morning Shift";
											}elseif($empInfo[0]['shift'] == "ES"){
												echo "Evenning Shift";
											}elseif($empInfo[0]['shift'] == "GS"){
												echo "General Shift";
											} 
											?>
										</td>
									</tr>
									<tr>
										<td><strong>Department:</strong></td>
										<td>
											<?php echo $empInfo[0]['dept_name'];?>
										</td>
										<td><strong>Designation:</strong></td>
										<td>
											<?php echo $empInfo[0]['desg_name'];?>
										
										</td>
									</tr>
									<tr>
										<td><strong>Grade:</strong></td>
										<td>
											<?php echo $empInfo[0]['grade_name'];?>
										</td>
										<td><strong>Level:</strong></td>
										<td>
											<?php echo $empInfo[0]['level_name'];?>
										</td>
									</tr>
									<tr>
										<td><strong>DOJ:</strong></td>
										<td>
											<?php echo date("d-m-Y", strtotime($empInfo[0]['join_date']))?>
										</td>
										<td><strong>Location:</strong></td>
										<td>
											<?php echo $empInfo[0]['branch_name'];?>
										</td>
									</tr>
									<tr>
										<td><strong>Corporate Email:</strong></td>
										<td><?php echo $empInfo[0]['email'];?></td>
										<td><strong>Skype ID:</strong></td>
										<td><?php echo $empInfo[0]['skype'];?></td>
									</tr>
									<tr>
										<td><strong>Perfom. Eligibility:</strong></td>
										<td>
											<?php echo ($empInfo[0]['isPerfomAllowance']=='Y')?'Yes':'No' ; ?>
										</td>
										<td><strong>Attnd. Eligibility:</strong></td>
										<td>
											<?php echo ($empInfo[0]['isAttndAllowance']=='Y') ?'Yes':'No' ;  ?>
									  
										</td>
									</tr>
									<tr>
										<td><strong>Reporting Officer:</strong></td>
										<td><?php echo $empInfo[0]['rmName'];?></td>	
										<?php if($empInfo[0]['emp_type'] == 'F') { ?>
												<td><strong> Confirmation:</strong></td>
										<?php }else {?>
												<td><strong>Contract end date:</strong></td>
										<?php }?>
										<td>
											<?php if($empInfo[0]['employee_conform'] != NULL) {echo date("d-m-Y", strtotime($empInfo[0]['employee_conform']));} else{ echo "";} ?>
										</td>																										
										</tr>
										
										<tr>
											<td><strong>Source of Hire:</strong></td>
											<td><?php echo $empInfo[0]['source_hire_name'];?></td>
											<td><strong>Confirmation Status</strong></td>
											<td><?php echo ($empInfo[0]['confirm_status']=='Confirmed') ?'Yes':'No' ;?></td>
										</tr>
										<tr>
										<td><strong>Remote access:</strong></td>
										<td>
											 <?php echo ($empInfo[0]['remote_access']=='Y') ?'Yes':'No' ;?>
										
										</td>
										<td><strong>Reviewing Officer:</strong></td>
										<td><?php echo $revInfo[0]['full_name'];?></td>
									</tr>
									<tr>
										<td><strong>HOD:</strong></td>
										<td><?php echo $empInfo[0]['hod'];?></td>
										<td><strong>Offer Letter Issued:</strong></td>
										<td>
											 <?php echo ($empInfo[0]['offer_letter_issued']=='Y') ?'Yes':'No' ;?>
											
										</td>
									</tr>
									<tr>
										<td><strong>Appointment Letter Issued:</strong></td>
										<td>
											 <?php echo ($empInfo[0]['appoint_letter_issued']=='Y') ?'Yes':'No' ;?>
										
										</td>
										<td><strong>Confirmation Letter Issued:</strong></td>
										<td>
											 <?php echo ($empInfo[0]['conf_letter_issued']=='Y') ?'Yes':'No' ;?>
										
										</td>
									</tr>
									<tr>
										<td><strong>Resignation Date:</strong></td>
										<td><?php if($empInfo[0]['resign_date'] != '0000-00-00') {echo date("d-m-Y", strtotime($empInfo[0]['resign_date']));} else{ echo "";} ?></td>	                                            
										<td><strong>Last Working date:</strong></td>
										<td>
												<?php if($empInfo[0]['lwd_date'] != '0000-00-00') {echo date("d-m-Y", strtotime($empInfo[0]['lwd_date']));} else{ echo "";} ?>
										</td>																										
									</tr>
									 <tr>
										<td><strong>Emp Status Type:</strong></td>
										<td><?php if($empInfo[0]['emp_status_type'] != '') {echo $empInfo[0]['emp_status_type'];} else{ echo "";} ?></td>	                                            
										<td><strong>FnF Status:</strong></td>
										<td>
												<?php if($empInfo[0]['FnF_status'] != '') {echo $empInfo[0]['FnF_status'];} else{ echo "";} ?>
										</td>																										
									 </tr>    
									   <tr>
										<td><strong>Last Promotion:</strong></td>
										<td>
										<?php echo $empInfo[0]['last_promotion'];?>
										</td>
										<td><strong>Misconduct Issued:</strong></td>
										<td>
											<?php echo $empInfo[0]['miscunduct_issue'];?>
										</td>
									</tr>
									<tr>
										<td><strong>F &amp; F Date:</strong></td>
										<td>
											<?php echo ($empInfo[0]['ff_date'] !="" && $empInfo[0]['ff_date'] != "0000-00-00")? date("d-m-Y", strtotime($empInfo[0]['ff_date'])) : "" ;?>
										</td>
										<td><strong>F &amp; F Amount:</strong></td>
										<td>
											<?php echo ($empInfo[0]['ff_amount'] > 0)?$empInfo[0]['ff_amount']:'';?>
										</td>
									</tr>
									<tr>
										<td><strong>F &amp; F Amount Handed Over Date:</strong></td>
										<td>
											<?php echo ($empInfo[0]['ff_handed_date'] !="" && $empInfo[0]['ff_handed_date'] != "0000-00-00")?  date("d-m-Y", strtotime($empInfo[0]['ff_handed_date'])) : "" ;?>
										</td>
										<td><strong>F &amp; F Cheque No & Bank:</strong></td>
										<td>
											<?php echo $empInfo[0]['ff_cheque_bank'];?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div> 
</div>