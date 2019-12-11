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
					<legend class="pkheader">My Final Income Tax Declaration Details</legend>
					<div class="row well"> 
						<div class="table-responsive">
							<table class="table table-striped table-hover table-bordered">
								<thead>
									<tr class="info">
										<th>Name</th>
										<th>Employee Code</th>
										<th>Date Of Application</th>
										<th>FY</th>
										<th>Ac Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									 
									<?php
										$result = count($get_my_estimate);
										if($result >0)
										{
											for ($i = 0; $i < $result; $i++)
											{?>
												<tr <?php if($i % 2 == 0)echo 'class="odd"'?>>
													<td><?php echo $get_my_estimate[$i]['name'];?></td>
													<td><?php echo $get_my_estimate[$i]['loginhandle'];?></td>
													<td><?php if($get_my_estimate[$i]['apply_date']!='0000-00-00 00:00:00')echo date('jS F Y g:ia', strtotime($get_my_estimate[$i]['apply_date']));?></td> 	
													<td><?php echo $get_my_estimate[$i]['fyear'].'-'.(string)($get_my_estimate[$i]['fyear']+1);?></td>		                
													<td><?php if($get_my_estimate[$i]['ac_status']==1){  echo '<span class="label label-primary">Approved</span>'; } else if($get_my_estimate[$i]['ac_status']==2){ echo '<span class="label label-danger">Rejected</span>'; } else { echo '<span class="label label-warning">Pending</span>'; }?> </td> 

													<td>
													<?php if($get_my_estimate[$i]['ac_status']==1 || $get_my_estimate[$i]['ac_status']==2 ){ ?>
														<a onclick="openNewWindow(<?php echo $get_my_estimate[$i]['tid'];?>,<?php echo $get_my_estimate[$i]['login_id'];?>)"><img alt="Print" style="cursor: pointer;" src="<?= base_url('assets/images/printer_icon.png')?>" /></a>  
													<?php }
													else
													{?>
														<a href="<?= base_url('en/accounts_help_desk/final_delcaration_form?id='.$get_my_estimate[$i]['tid'])?>"><img alt="Edit" style="cursor: pointer;" src="<?= base_url('assets/images/icon/edit.png')?>" /></a> 
													<?php } ?>
													</td>
												</tr>
												<?php  
											}
										}
										else
										{
										?>
											<tr><td colspan="6" align="center">No records found</td></tr>
										<?php } ?>
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
</div>
<script>
function openNewWindow(tid,login_id){
	window.open(site_url+'accounts_help_desk/my_estimated_declaration_print?id='+login_id+'&tid='+tid, '_blank', 'location=yes,width=980,height=600,left=0,top=0,scrollbars=1');
}
</script>