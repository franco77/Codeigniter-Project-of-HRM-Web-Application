<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<form method="POST" action="">
						<legend class="pkheader">Employee F&F Details<small>(F&F application of <?php echo $ff_cur_monthStr; ?>)</small></legend>
						<div class="row well">
							<div class="row"> 
								<div class="col-md-2">Month:
									<select name="dd_month" class="form-control input-sm">
										<?php                                         
										for ($i=1;$i<=12;$i++) 
										{
											if ($i == $ff_cur_month)
											{
											?>
											<option value="<?php echo($i)?>" selected><?php echo(date('F',mktime(0,0,0,$i,1,2000)))?></option>
											<?php }else{?>
											<option value="<?php echo($i)?>"><?php echo(date('F',mktime(0,0,0,$i,1,2000)))?></option>
											<?php }
										} ?>
									</select>
								</div>
								<div class="col-md-3">Year:
									<select name="dd_year" class="form-control input-sm">
											  <?php
											  $yr=date("Y");
											  for ($j=$yr;$j>=2011;$j--){
													if ($j == $ff_cur_year){
											 ?>
													<option value="<?php echo($j)?>" selected><?php echo($j)?></option>
											 <?php }else{?>
											 <option value="<?php echo($j)?>"><?php echo($j)?></option>
											 <?php }
											 }?>
									</select> 
								</div>
								<div class="col-md-3">
									<input type="submit" id="searchRegularize" name="searchRegularize" value="Find" class="btn btn-primary"/> 
								</div>
							</div>
						</div> 
						<div class="row well"> 
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-condensed">
									<thead>
										<tr class="info">
											<th width="25%">Name</th>                                        
											<th width="25%">Notice Period</th>
											<th width="25%">Notice Served</th>
											<th width="25%">Notice Waiver</th>
											<th width="25%">Notice Shortfall</th>
											<th width="25%">Balance PL</th>
											<th width="25%">Notice Paid days</th>
											<th width="25%">Basic Monthly</th>
											<th width="25%">Leave Encash.</th>                                     
											<th width="25%">Salary Payble</th>
											<th width="25%">Extra Hours</th>
											<th width="25%">Grand Total</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$count = count($result); 
										if($count > 0)
										{
											for($i=0; $i<$count; $i++)
											{   
												?>
												<tr>
													<td><?php echo $result[$i]['name'];?></td>
													<td><?php echo $result[$i]['notice_period'];?></td>
													<td><?php echo $result[$i]['notice_served'];?></td>
													<td><?php echo $result[$i]['notice_waiver'];?></td>
													<td>
													<?php
														//$notice_shortfall =$result[$i]['notice_period'] - $result[$i]['notice_served'];
														//echo $notice_shortfall;
														echo $notice_shortfall = $result[$i]['notice_shortfall'];
													?>
													</td>
													<td><?php echo $avlPL = $result[$i]['avlPL'];?></td>
													<td><?php echo $notice_pay_days=$avlPL+$result[$i]['notice_waiver']-$notice_shortfall; ?></td>  
													<td><?php echo $basic=$result[$i]['basic'];?></td>
													<td><?php echo $leave_encash=round(($result[$i]['basic']/30*$notice_pay_days));?></td>
													<td><?php echo $result[$i]['net_salary'];?></td>
													<td><?php echo $extra_hour = $result[$i]['extra_hour']; ?></td> 
													<td><?php echo round(($result[$i]['net_salary'] + $leave_encash + $extra_hour)); ?></td> 
												</tr>
											<?php } 
										 }
										else{
										?>
											<tr><td colspan="12" align="center">No records found</td></tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div> 
						</form>
					</div> 
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
	</div> 
</div>