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
				<form method="POST" action="">
				<div class="col-md-9 center-xs"> 
					<legend class="pkheader">Vintage Employee Details <small>(Resource of our Organisation)</small></legend> 
					<div class="row well">
						<div class="row pkdsearch">
							<div class="col-md-2">
								<select name="dd_vintage_type"  class="form-control">
									<option value="3" <?php if($dd_vintage_type == '3') echo 'selected="selected"';?>>3 Month</option>
									<option value="6" <?php if($dd_vintage_type == '6') echo 'selected="selected"';?>>6 Month</option>
									<option value="12" <?php if($dd_vintage_type == '12') echo 'selected="selected"';?>>1 Year</option>
									<option value="24" <?php if($dd_vintage_type == '24') echo 'selected="selected"';?>>2 Year</option>
									<option value="36" <?php if($dd_vintage_type == '36') echo 'selected="selected"';?>>3 Year</option>
									<option value="60" <?php if($dd_vintage_type == '60') echo 'selected="selected"';?>>5 Year</option>
									<option value="C" <?php if($dd_vintage_type == 'C') echo 'selected="selected"';?>>Contractual Employee</option>
								</select>
							</div>  
							<div class="col-md-3">
								<input type="submit" name="searchVintageEmployee" value="Find" class="btn btn-info pull-left" /> 
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed table-hover">
								<thead>
									<tr class="info">
										<th>Name</th>
										<th>Employee Code</th> 
										<th>Designation</th>
										<th>Join Date</th>
										<th>Completion Date</th> 
									</tr>
								</thead>
								<tbody>
									<?php
										$count = count($num_rows);
										//var_dump($num_rows);
										if($count > 0)
										{
											for($i=0; $i < $count; $i++)
											{
												if($vType == '3')
												{
													$completionDate = strtotime('+3 month', strtotime($num_rows[$i]['join_date'])) ;
												}
												elseif($vType == '6')
												{
													$completionDate = strtotime('+6 month', strtotime($num_rows[$i]['join_date'])) ;
												}
												elseif($vType == '12')
												{
													$completionDate = strtotime('+1 year', strtotime($num_rows[$i]['join_date'])) ;
												}
												elseif($vType == '24')
												{
													$completionDate = strtotime('+2 year', strtotime($num_rows[$i]['join_date'])) ;
												}
												elseif($vType == '36')
												{
													$completionDate = strtotime('+3 year', strtotime($num_rows[$i]['join_date'])) ;
												}
												elseif($vType == '60')
												{
													$completionDate = strtotime('+5 year', strtotime($num_rows[$i]['join_date'])) ;
												}
												elseif($vType == 'C')
												{
													$completionDate = strtotime($num_rows[$i]['employee_conform']) ;
												}
												$completionDate = date("jS M, Y", $completionDate); 
												?>
												<tr>
													<td><a class="link" href="<?= base_url('en/hr/general_readonly') ?>?id=<?php echo $num_rows[$i]['login_id'];?>"><?php echo $num_rows[$i]['full_name'];?></a></td>
													<td><?php echo $num_rows[$i]['loginhandle'];?></td>
													<td><?php echo $num_rows[$i]['desg_name'];?></td>
													<td><?php echo date("jS M, Y", strtotime($num_rows[$i]['join_date']));?></td>
													<td><?php echo $completionDate;?></td>
												</tr>
										<?php }}
										else
										{
										?>
											<tr><td colspan="5"><center>No records found</center></td></tr>
										<?php
										}
									?>
								</tbody>
							</table>
						</div> 
					</div> 
				</div>
				</form>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>