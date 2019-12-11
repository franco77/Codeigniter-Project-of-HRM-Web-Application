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
					<legend class="pkheader">All Employee Under You <small>(<?php echo $totNoofRec;?>)</small></legend>
					<div class="row well"> 
						<div class="table-responsive" ng-show="filteredItems > 0">
							<table class="table table-striped table-bordered table-condensed">
								<tr class="info">
									<th width="20%">Name</th>
									<th width="15%">Employee Code</th>
									<th width="25%">Email</th>
									<th width="25%">Designation</th> 
									<th width="15%">Action</th>  
								</tr> 
								<?php
									$num_rows = count($result_arr);
									if($num_rows >0)
									{
										for($i=0; $i<$num_rows; $i++)
										{
										?>
										<tr>
											<td><?php echo $result_arr[$i]['name'];?></td>
											<td><?php echo $result_arr[$i]['loginhandle'];?></td>
											<td><?php echo $result_arr[$i]['email'];?></td>
											<td><?php echo $result_arr[$i]['desg_name'];?></td>
											<td><a class="link" href="<?php echo base_url(); ?>timesheet/employee_timesheet?reqEmpid=<?php echo urlencode($result_arr[$i]['login_id']);?>">Timesheet</a> || 
											<br/><a class="link" href="<?php echo base_url(); ?>timesheet/leave_status?reqEmpid=<?php echo urlencode($result_arr[$i]['login_id']);?>">Leave Status</a> || 
											<br/><a id="changeReporting" class="openColorBox link" href="<?php echo base_url(); ?>timesheet/change_reporting?from=leave&user_id=<?php echo urlencode($result_arr[$i]['login_id']);?>"><span>Change Reporting</span></a></td>
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