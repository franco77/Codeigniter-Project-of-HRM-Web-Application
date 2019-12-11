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
					<legend class="pkheader">Leave Status <small>(Details of PL & SL for the year)</small></legend>
					<div class="row well"> 
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<tr class="info"> 
									<th>Leave Type</th>
									<th>Available Leave</th>
									<th>Leave Taken</th>
									<th>Balance Leave</th>
								</tr>
								<tr class="odd">
									<td>Planned Leave [PL]</td>
									<td align="center"><?php echo $tot_pl; ?></td>
									<td align="center"><?php echo $ob_pl; ?></td>
									<td align="center"><?php echo $bal_pl; ?></td>
								</tr>
								<?php if($userTypeInfo[0]['emp_type'] == 'F') {?>
								<tr>
									<td>Sick Leave [SL]</td>
									<td align="center"><?php echo $tot_sl; ?></td>
									<td align="center"><?php echo $ob_sl; ?></td>
									<td align="center"><?php echo $bal_sl; ?></td>
								</tr>
								<?php }?>
								<tr class="odd">
									<th>Total</th>
									<td align="center"><?php echo $tot_leave; ?></td>
									<td align="center"><?php echo $tot_leave_taken; ?></td>
									<td align="center"><?php echo $tot_leave_bal; ?></td>
								</tr>
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