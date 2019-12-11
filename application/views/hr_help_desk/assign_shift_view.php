<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="assignshift" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content ">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">Assign Shift to all employee under you</legend>
					<div class="row well"> 
						<!--<div class="row pkdsearch">
							<div class="col-md-2">PageSize:
								<select ng-model="entryLimit" class="form-control input-sm">
									<option>10</option>
									<option>20</option>
									<option>30</option>
									<option>40</option>
									<option>50</option>
									<option>70</option>
									<option>90</option>
									<option>120</option>
									<option>150</option>
								</select>
							</div>
							<div class="col-md-3">Search:
								<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
							</div> 
							<div class="col-md-5">
								<h5>Filtered {{ filtered.length }} of {{ totalItems}} total update records</h5>
							</div>
						</div> -->
						<div class="row err-oldmsg"></div>
						<div class="row"> 
							
							<div class="table-responsive" >
								<table class="table table-striped table-hover">
									<thead>
										<tr class="info"> 
											<th width="20%">Name</th>
											<th width="15%">Emp Code</th>
											<th width="20%">Email</th>
											<th width="15%">Designation</th>
											<th width="15%">Shift</th> 
											<th width="15%">Division</th> 
										</tr>
									</thead>
									<tbody>
										<?php if(count($employeeList)>0){
											for($i=0; $i<count($employeeList); $i++){ ?>
										<tr > 
											<td><?php echo $employeeList[$i]['name']; ?></td>
											<td><?php echo $employeeList[$i]['loginhandle']; ?></td>
											<td><?php echo $employeeList[$i]['email']; ?></td>
											<td><?php echo $employeeList[$i]['desg_name']; ?></td>
											<td>
												
												<select class="form-control" name="asignShift" onchange="updateAsignShift(this, <?php echo $employeeList[$i]['login_id']; ?>);" > 
													<option value="GS" <?php if($employeeList[$i]['shift'] == 'GS'){ echo "selected";}  ?>>General Shift</option>  
													<option value="MS" <?php if($employeeList[$i]['shift'] == 'MS'){ echo "selected";}  ?>>Morning Shift</option>
													<option value="ES" <?php if($employeeList[$i]['shift'] == 'ES'){ echo "selected";}  ?>>Evening Shift</option>
													<option value="NS" <?php if($employeeList[$i]['shift'] == 'NS'){ echo "selected";}  ?>>Night Shift</option>    
												</select>
											</td> 
											<td>
												
												<select class="form-control" name="asignShift" onchange="updateDivision(this, <?php echo $employeeList[$i]['login_id']; ?>);" > 
													<option value="UT" <?php if($employeeList[$i]['division'] == 'UT'){ echo "selected";}  ?>>Utility</option>  
													<option value="CA" <?php if($employeeList[$i]['division'] == 'CA'){ echo "selected";}  ?>>CAD</option>
													<option value="GI" <?php if($employeeList[$i]['division'] == 'GI'){ echo "selected";}  ?>>GIS</option>
													<option value="SS" <?php if($employeeList[$i]['division'] == 'SS'){ echo "selected";}  ?>>Software Services</option>    
													<option value="BD" <?php if($employeeList[$i]['division'] == 'BD'){ echo "selected";}  ?>>Business Development</option>    
													<option value="AF" <?php if($employeeList[$i]['division'] == 'AF'){ echo "selected";}  ?>>Admin and Facility</option>    
													<option value="FA" <?php if($employeeList[$i]['division'] == 'FA'){ echo "selected";}  ?>>Finance and Accounts</option>    
													<option value="IT" <?php if($employeeList[$i]['division'] == 'IT'){ echo "selected";}  ?>>IT</option>    
													<option value="HR" <?php if($employeeList[$i]['division'] == 'HR'){ echo "selected";}  ?>>Human Resources</option>    
												</select>
											</td> 
										</tr>
										<?php } } else{ ?>
										<tr><td colspan="5"> No record found</td></tr>
										<?php } ?>
										
									</tbody>
								</table>
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
var site_url = '<?php echo base_url(); ?>';
function updateAsignShift(dis,login_id){
	var shift = $(dis).val();
	$('.err-oldmsg').html('');
	$.ajax({
		type: "POST",
		url: site_url+'hr_help_desk/update_asign_shift',
		data: {shift : shift,login_id : login_id}, // serializes the form's elements.
		success: function(data)
		{
			if(data == 1){
				$('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Shift changed successfully </div></div>');
				setTimeout(function(){ location.reload(); }, 3000);
			}
		}
	});
}
function updateDivision(dis,login_id){
	var division = $(dis).val();
	$('.err-oldmsg').html('');
	$.ajax({
		type: "POST",
		url: site_url+'hr_help_desk/update_emp_division',
		data: {division : division,login_id : login_id}, // serializes the form's elements.
		success: function(data)
		{
			if(data == 1){
				$('.err-oldmsg').html('<div class="col-md-12"><div class="alert alert-success" role="alert"> Division changed successfully </div></div>');
				setTimeout(function(){ location.reload(); }, 3000);
			}
		}
	});
}
</script>