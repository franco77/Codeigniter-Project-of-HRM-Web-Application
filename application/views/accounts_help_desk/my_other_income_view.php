<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('accounts_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<legend class="pkheader">My Other Income Details</legend>
					<div class="row well"> 
						<div class="table-responsive" ng-show="filteredItems > 0">
							<table class="table table-striped table-hover table-bordered">
								<thead>
									<tr class="info">
										<th>Name</th>
										<th>Employee Code</th>
										<th>Date Of Application</th>
										<th>FY</th>
										<th>Ac Status</th>
										<th>Remark</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$result = count($my_other_income);
										if($result >0)
										{
											for ($i = 0; $i < $result; $i++)
											{?>
												<tr <?php if($i % 2 == 0)echo 'class="odd"'?>>
													<td><?php echo $my_other_income[$i]['full_name'];?></td>
													<td><?php echo $my_other_income[$i]['loginhandle'];?></td>
													<td><?php if($my_other_income[$i]['apply_date']!='0000-00-00 00:00:00')echo date('jS F Y g:ia', strtotime($my_other_income[$i]['apply_date']));?></td> 	
													<td>
													<?php  
														$fadte = date('m',strtotime($my_other_income[$i]['apply_date']));
														if( $fadte >=4 ){
															$fy = date('Y',strtotime($my_other_income[$i]['apply_date']));
														}
														else if( $fadte <= 3 ){
															$fy = date('Y',strtotime($my_other_income[$i]['apply_date'])) - 1;
														}
													?>
													<?php echo $fy.'-'.(string)($fy+1);?></td>	                
													<td nowrap><?php if($my_other_income[$i]['status']==1)  echo '<span class="label label-primary">Approved</span>'; else echo '<span class="label label-primary">Approved</span>';?> </td> 
													<td>&nbsp;</td>
													<td>
														<a onclick="openNewWindow(<?php echo $my_other_income[$i]['id'];?>,<?php echo $my_other_income[$i]['loginID'];?>)"><img alt="Print" style="cursor: pointer;" src="<?= base_url('assets/images/printer_icon.png')?>" /></a> 
													</td> 
												</tr>
												<?php  
											}
										}
										else
										{
										?>
											<tr><td colspan="7" align="center">No records found</td></tr>
										<?php } ?>
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

<script>
function openNewWindow(id,login_id){
	window.open(site_url+'accounts_help_desk/my_other_income_details?id='+login_id+'&tid='+id, '_blank', 'location=yes,width=980,height=600,left=0,top=0,scrollbars=1');
}
</script>