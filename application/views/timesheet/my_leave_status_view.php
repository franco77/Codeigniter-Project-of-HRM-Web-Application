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
					<legend class="pkheader">My Leave Status <small>(Details of PL & SL for the year)</small></legend>
					<div class="row well"> 
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<tr class="info">
									<th>Leave Type</th>
									<th>Available Leave</th>
									<th>Leave Taken</th>
									<th>Balance Leave</th> 
								</tr>
								<?php
									$ob_pl = $leaveINFO['ob_pl'];
									$bal_pl = $tot_pl - $ob_pl; 

									$ob_sl = $leaveINFO['ob_sl'];
									$bal_sl = $tot_sl - $ob_sl;

									$tot_leave = $tot_pl + $tot_sl;
									$tot_leave_taken = $ob_pl + $ob_sl;
									$tot_leave_bal = $bal_pl + $bal_sl;
								?>
								<tr>
									<td>Planned leave(PL)</td>
									<td><?php echo $tot_pl; ?></td>
									<td><?php echo $ob_pl; ?></td>
									<td><?php echo $bal_pl;?></td> 
								</tr>
								<tr>
									<td>Sick leave(SL)</td>
									<td><?php echo $tot_sl; ?></td>
									<td><?php echo $ob_sl; ?></td>
									<td><?php echo $bal_sl; ?></td>  
								</tr>
								<tr>
									<td>Total</td>
									<td><?php echo $tot_leave; ?></td>
									<td><?php echo  $tot_leave_taken; ?></td>
									<td><?php echo $tot_leave_bal; ?></td>  
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