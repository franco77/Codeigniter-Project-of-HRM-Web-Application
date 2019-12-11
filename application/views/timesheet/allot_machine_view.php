<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>  
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('timesheet/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<legend class="pkheader">All Mechine Under You <small>(<?php echo count($machines);?>)</small></legend>
					<div class="row well"> 
						<div class="table-responsive" ng-show="filteredItems > 0">
							<table class="table table-striped table-bordered table-condensed">
								<tr class="info">
									<th width="5%">SL</th>
									<th width="20%">Machine Name</th>
									<th width="20%">Employee(Morning)</th>
									<th width="20%">Employee(General)</th>
									<th width="20%">Employee(Evening)</th>
									<th width="15%">Action</th>  
								</tr> 
								<?php
									$num_rows = count($machines);
									if($num_rows >0)
									{
										$j=0;
										for($i=0; $i<$num_rows; $i++)
										{ $j++;
										?>
										<tr>
											<td><?php echo $j;?></td>
											<td><?php echo $machines[$i]['s_machine_name'];?></td>
											<td align="center">
												<?php if($machines[$i]['emp_morning'] !=""){ echo $machines[$i]['emp_morning'];?> 
												<a href="" data-toggle="modal" data-target="#delete_morning_<?php echo $machines[$i]['ix_machine'];?>">
													<img  class="leaveReject" src="<?php echo base_url(); ?>assets/images/icon/cancel.png" alt="" title="Delete" style="cursor: pointer;"  />
												</a>
												<div class="modal fade" id="delete_morning_<?php echo $machines[$i]['ix_machine'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
															 <button type="button" class="close" data-dismiss="modal">&times;</button>
																<h4 class="modal-title" id="exampleModalLabel">Delete employee from Machine: <?php echo $machines[$i]['s_machine_name'];?> <div id="head" style="display:inline;"></div></h4>
															</div>
																<div class="modal-body">
																	<h5>Are you want to delete <b><?php echo $machines[$i]['emp_morning'];?></b> from Machine <b><?php echo $machines[$i]['s_machine_name'];?></b> ?</h5>
																	<input type="hidden" name="ix_machine" id="ix_machine_morning<?php echo $machines[$i]['ix_machine'];?>" value="<?php echo $machines[$i]['ix_machine'];?>">
																	<input type="hidden" name="emp_morning" id="emp_morning<?php echo $machines[$i]['ix_machine'];?>" value="<?php echo $machines[$i]['ix_emp_id_morning'];?>">
																	<div class="form-group err-oldmsg"></div>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-danger" data-dismiss="modal">No</button> 
																	<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success"  onclick="deleteEmployeeFromMachineMorning(this,'<?php echo $machines[$i]['ix_machine'];?>');" >Yes</button>
																</div>
														</div>
													</div>
												</div>
												<?php } else{ ?> 
												<a href="" data-toggle="modal" data-target="#add_morning_<?php echo $machines[$i]['ix_machine'];?>">
													<img  class="leaveReject" src="<?php echo base_url(); ?>assets/images/icon/assign.png" alt="" title="Delete" style="cursor: pointer;"  />
												</a>
												<div class="modal fade" id="add_morning_<?php echo $machines[$i]['ix_machine'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
															 <button type="button" class="close" data-dismiss="modal">&times;</button>
																<h4 class="modal-title" id="exampleModalLabel">Add employee from Machine: <?php echo $machines[$i]['s_machine_name'];?> <div id="head" style="display:inline;"></div></h4>
															</div>
																<div class="modal-body">
																	<input type="hidden" name="product_id" id="product_id_morning<?php echo $machines[$i]['ix_machine'];?>" value="<?php echo $machines[$i]['ix_machine'];?>">
																	<input type="hidden" name="shift_type" id="shift_type_morning<?php echo $machines[$i]['ix_machine'];?>" value="Morning">
																	<div class="row">
																	<div class="col-md-3">
																		<h5>Employee <span class="red">*</span></h5>
																	</div>
																	<div class="col-md-9">
																		<select  name="source_id" id="source_id_morning<?php echo $machines[$i]['ix_machine'];?>"  class="selectpicker form-control" data-live-search="true" required="required" >
																			<option value=""></option>
																			<?php 
																			for($l=0; $l < count($emp); $l++) 
																			{?>
																				<option value="<?php echo $emp[$l]['ix_emp_id']; ?>"  ><?php echo $emp[$l]['s_emp_name'].'('.$emp[$l]['ix_corp_id'].')'; ?></option>	
																			<?php } ?>
																		</select>
																	</div>
																	</div>
																	<div class="form-group err-oldmsg"></div>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-danger" data-dismiss="modal">No</button> 
																	<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success"  onclick="addEmployeeFromMachine(this,'<?php echo $machines[$i]['ix_machine'];?>','morning');" >Yes</button>
																</div>
														</div>
													</div>
												</div>
													
												<?php }  ?>
											</td>
											<td align="center">
												<?php if($machines[$i]['emp_general'] !=""){ echo $machines[$i]['emp_general'];?> 
												<a href="" data-toggle="modal" data-target="#delete_general_<?php echo $machines[$i]['ix_machine'];?>">
													<img  class="leaveReject" src="<?php echo base_url(); ?>assets/images/icon/cancel.png" alt="" title="Delete" style="cursor: pointer;"  />
												</a>
												<div class="modal fade" id="delete_general_<?php echo $machines[$i]['ix_machine'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
															 <button type="button" class="close" data-dismiss="modal">&times;</button>
																<h4 class="modal-title" id="exampleModalLabel">Delete employee from Machine: <?php echo $machines[$i]['s_machine_name'];?> <div id="head" style="display:inline;"></div></h4>
															</div>
																<div class="modal-body">
																	<h5>Are you want to delete <b><?php echo $machines[$i]['emp_general'];?></b> from Machine <b><?php echo $machines[$i]['s_machine_name'];?></b> ?</h5>
																	<input type="hidden" name="ix_machine" id="ix_machine_general<?php echo $machines[$i]['ix_machine'];?>" value="<?php echo $machines[$i]['ix_machine'];?>">
																	<input type="hidden" name="emp_general" id="emp_general<?php echo $machines[$i]['ix_machine'];?>" value="<?php echo $machines[$i]['ix_emp_id_general'];?>">
																	<div class="form-group err-oldmsg"></div>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-danger" data-dismiss="modal">No</button> 
																	<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success"  onclick="deleteEmployeeFromMachineGeneral(this,'<?php echo $machines[$i]['ix_machine'];?>');" >Yes</button>
																</div>
														</div>
													</div>
												</div>
												<?php } else{ ?> 
												<a href="" data-toggle="modal" data-target="#add_general_<?php echo $machines[$i]['ix_machine'];?>">
													<img  class="leaveReject" src="<?php echo base_url(); ?>assets/images/icon/assign.png" alt="" title="Delete" style="cursor: pointer;"  />
												</a>
												<div class="modal fade" id="add_general_<?php echo $machines[$i]['ix_machine'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
															 <button type="button" class="close" data-dismiss="modal">&times;</button>
																<h4 class="modal-title" id="exampleModalLabel">Add employee from Machine: <?php echo $machines[$i]['s_machine_name'];?> <div id="head" style="display:inline;"></div></h4>
															</div>
																<div class="modal-body">
																	<input type="hidden" name="product_id" id="product_id_general<?php echo $machines[$i]['ix_machine'];?>" value="<?php echo $machines[$i]['ix_machine'];?>">
																	<input type="hidden" name="shift_type" id="shift_type_general<?php echo $machines[$i]['ix_machine'];?>" value="General">
																	<div class="row">
																	<div class="col-md-3">
																		<h5>Employee <span class="red">*</span></h5>
																	</div>
																	<div class="col-md-9">
																		<select  name="source_id" id="source_id_general<?php echo $machines[$i]['ix_machine'];?>"  class="selectpicker form-control" data-live-search="true" required="required" >
																			<option value=""></option>
																			<?php 
																			for($l=0; $l < count($emp); $l++) 
																			{?>
																				<option value="<?php echo $emp[$l]['ix_emp_id']; ?>"  ><?php echo $emp[$l]['s_emp_name'].'('.$emp[$l]['ix_corp_id'].')'; ?></option>	
																			<?php } ?>
																		</select>
																	</div>
																	</div>
																	<div class="form-group err-oldmsg"></div>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-danger" data-dismiss="modal">No</button> 
																	<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success"  onclick="addEmployeeFromMachine(this,'<?php echo $machines[$i]['ix_machine'];?>','general');" >Yes</button>
																</div>
														</div>
													</div>
												</div>
												<?php } ?>
											</td>
											<td align="center">
												<?php if($machines[$i]['emp_evening'] !=""){ echo $machines[$i]['emp_evening'];?> 
												<a href="" data-toggle="modal" data-target="#delete_evening_<?php echo $machines[$i]['ix_machine'];?>">
													<img  class="leaveReject" src="<?php echo base_url(); ?>assets/images/icon/cancel.png" alt="" title="Delete" style="cursor: pointer;"  />
												</a>
												<div class="modal fade" id="delete_evening_<?php echo $machines[$i]['ix_machine'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
															 <button type="button" class="close" data-dismiss="modal">&times;</button>
																<h4 class="modal-title" id="exampleModalLabel">Delete employee from Machine: <?php echo $machines[$i]['s_machine_name'];?> <div id="head" style="display:inline;"></div></h4>
															</div>
																<div class="modal-body">
																	<h5>Are you want to delete <b><?php echo $machines[$i]['emp_evening'];?></b> from Machine <b><?php echo $machines[$i]['s_machine_name'];?></b> ?</h5>
																	<input type="hidden" name="ix_machine" id="ix_machine_evening<?php echo $machines[$i]['ix_machine'];?>" value="<?php echo $machines[$i]['ix_machine'];?>">
																	<input type="hidden" name="emp_evening" id="emp_evening<?php echo $machines[$i]['ix_machine'];?>" value="<?php echo $machines[$i]['ix_emp_id_evening'];?>">
																	<div class="form-group err-oldmsg"></div>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-danger" data-dismiss="modal">No</button> 
																	<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success"  onclick="deleteEmployeeFromMachineEvening(this,'<?php echo $machines[$i]['ix_machine'];?>');" >Yes</button>
																</div>
														</div>
													</div>
												</div>
												<?php } else{ ?> 
												<a href="" data-toggle="modal" data-target="#add_evening_<?php echo $machines[$i]['ix_machine'];?>">
													<img  class="leaveReject" src="<?php echo base_url(); ?>assets/images/icon/assign.png" alt="" title="Delete" style="cursor: pointer;"  />
												</a>
												<div class="modal fade" id="add_evening_<?php echo $machines[$i]['ix_machine'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
															 <button type="button" class="close" data-dismiss="modal">&times;</button>
																<h4 class="modal-title" id="exampleModalLabel">Add employee from Machine: <?php echo $machines[$i]['s_machine_name'];?> <div id="head" style="display:inline;"></div></h4>
															</div>
																<div class="modal-body">
																	<input type="hidden" name="product_id" id="product_id_evening<?php echo $machines[$i]['ix_machine'];?>" value="<?php echo $machines[$i]['ix_machine'];?>">
																	<input type="hidden" name="shift_type" id="shift_type_evening<?php echo $machines[$i]['ix_machine'];?>" value="Evening">
																	<div class="row">
																	<div class="col-md-3">
																		<h5>Employee <span class="red">*</span></h5>
																	</div>
																	<div class="col-md-9">
																		<select  name="source_id" id="source_id_evening<?php echo $machines[$i]['ix_machine'];?>"  class="selectpicker form-control" data-live-search="true" required="required" >
																			<option value=""></option>
																			<?php 
																			for($l=0; $l < count($emp); $l++) 
																			{?>
																				<option value="<?php echo $emp[$l]['ix_emp_id']; ?>"  ><?php echo $emp[$l]['s_emp_name'].'('.$emp[$l]['ix_corp_id'].')'; ?></option>	
																			<?php } ?>
																		</select>
																	</div>
																	</div>
																	<div class="form-group err-oldmsg"></div>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-danger" data-dismiss="modal">No</button> 
																	<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success"  onclick="addEmployeeFromMachine(this,'<?php echo $machines[$i]['ix_machine'];?>','evening');" >Yes</button>
																</div>
														</div>
													</div>
												</div>
												<?php } ?>
											</td>
											<td>
												<?php if($machines[$i]['emp_morning'] =="" && $machines[$i]['emp_general'] =="" && $machines[$i]['emp_evening'] =="") { ?>
												<div class="iCompassTip" >
													<a href="" data-toggle="modal" data-target="#releaseDetails_<?php echo $machines[$i]['ix_machine'];?>">
														Release <img  class="leaveReject" src="<?php echo base_url(); ?>assets/images/icon/cancel.png" alt="" title="Release Machine" style="cursor: pointer;"  />
													</a>
												</div>
												<div class="modal fade" id="releaseDetails_<?php echo $machines[$i]['ix_machine'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
															 <button type="button" class="close" data-dismiss="modal">&times;</button>
																<h4 class="modal-title" id="exampleModalLabel">Release Machine: <?php echo $machines[$i]['s_machine_name'];?> <div id="head" style="display:inline;"></div></h4>
															</div>
																<div class="modal-body">
																	<h5>Are you sure want to release Machine: <b><?php echo $machines[$i]['s_machine_name'];?></b> ?</h5>
																	<input type="hidden" name="ix_machine" id="ix_machine<?php echo $machines[$i]['ix_machine'];?>" value="<?php echo $machines[$i]['ix_machine'];?>">
																	<input type="hidden" name="ownership" id="ownership<?php echo $machines[$i]['ix_machine'];?>" value="<?php echo $this->session->userdata('empCode');?>">
																	<div class="form-group err-oldmsg"></div>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-danger" data-dismiss="modal">No</button> 
																	<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success"  onclick="releaseMachine(this,'<?php echo $machines[$i]['ix_machine'];?>');" >Yes</button>
																</div>
														</div>
													</div>
												</div>
												<?php } ?>
											</td>
										</tr>
									  <?php  
									  }
								}
								else{
								?>
								<tr><td colspan="6" align="center">No records found</td></tr>
								<?php
								}
								?>
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
<script>

function releaseMachine(dis,ix_machine){
	var ix_machine = $('#ix_machine'+ix_machine).val();
	var ownership = $('#ownership'+ix_machine).val();
	$('.err-oldmsg').html("");
	if(ix_machine !="" && ownership !=""){
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/release_machine_rm',
			data: {ix_machine : ix_machine, ownership : ownership}, // serializes the form's elements.
			success: function(data)
			{
				if(data == 0){
					$(dis).parents('.modal-content').find('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please try again</div></div>');
				}
				else{
					$(dis).parents('.modal-content').find('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Updated successfully </div></div>');
					location.reload();
				}
		   }
		});
	}
}

function deleteEmployeeFromMachineMorning(dis,ix_machine){
	var ix_machine = $('#ix_machine_morning'+ix_machine).val();
	var employee = $('#emp_morning'+ix_machine).val();
	$('.err-oldmsg').html("");
	if(ix_machine !="" && employee !=""){
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/delete_employee_from_machine_morning_rm',
			data: {ix_machine : ix_machine, employee : employee}, // serializes the form's elements.
			success: function(data)
			{
				if(data == 0){
					$(dis).parents('.modal-content').find('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please try again</div></div>');
				}
				else{
					$(dis).parents('.modal-content').find('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Updated successfully </div></div>');
					location.reload();
				}
		   }
		});
	}
}

function deleteEmployeeFromMachineGeneral(dis,ix_machine){
	var ix_machine = $('#ix_machine_general'+ix_machine).val();
	var employee = $('#emp_general'+ix_machine).val();
	$('.err-oldmsg').html("");
	if(ix_machine !="" && employee !=""){
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/delete_employee_from_machine_general_rm',
			data: {ix_machine : ix_machine, employee : employee}, // serializes the form's elements.
			success: function(data)
			{
				if(data == 0){
					$(dis).parents('.modal-content').find('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please try again</div></div>');
				}
				else{
					$(dis).parents('.modal-content').find('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Updated successfully </div></div>');
					location.reload();
				}
		   }
		});
	}
}

function deleteEmployeeFromMachineEvening(dis,ix_machine){
	var ix_machine = $('#ix_machine_evening'+ix_machine).val();
	var employee = $('#emp_evening'+ix_machine).val();
	$('.err-oldmsg').html("");
	if(ix_machine !="" && employee !=""){
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/delete_employee_from_machine_evening_rm',
			data: {ix_machine : ix_machine, employee : employee}, // serializes the form's elements.
			success: function(data)
			{
				if(data == 0){
					$(dis).parents('.modal-content').find('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please try again</div></div>');
				}
				else{
					$(dis).parents('.modal-content').find('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Updated successfully </div></div>');
					location.reload();
				}
		   }
		});
	}
}

function addEmployeeFromMachine(dis,ix_machine,type){
	var product_id = $('#product_id_'+type+ix_machine).val();
	var shift_type = $('#shift_type_'+type+ix_machine).val();
	var source_id = $('#source_id_'+type+ix_machine).val();
	$('.err-oldmsg').html(""); 
	if(product_id !="" && shift_type !="" && source_id !=""){
		$.ajax({
			type: "POST",
			url: site_url+'timesheet/add_employee_to_machine_rm',
			data: {product_id : product_id, shift_type : shift_type, source_id : source_id}, // serializes the form's elements.
			success: function(data)
			{
				if(data == 0){
					$(dis).parents('.modal-content').find('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-danger" role="alert"> Please try again</div></div>');
				}
				else{
					$(dis).parents('.modal-content').find('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Updated successfully </div></div>');
					location.reload();
				}
		   }
		});
	}
}


</script>