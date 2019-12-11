<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<legend class="pkheader">My Probation Assessment Detail</legend>
					<div class="row well">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info"> 
										<th>Name</th>
										<th>Employee Code</th>
										<th>Email</th>
										<th>Apply Date</th> 
										<th>Status</th> 
									</tr>
								</thead>
								<tbody>
									<?php 
									$result = count($mypdetails); 
									if($result > 0)
									{
										for($i=0; $i< $result; $i++)
										{?>
										<tr>
											<td><?php echo $mypdetails[$i]['name'];?></td>
											<td><?php echo $mypdetails[$i]['loginhandle'];?></td>
											<td><?php echo $mypdetails[$i]['email'];?></td>
											<td><a style="cursor: pointer;" onclick="openNewWindow(<?php echo $mypdetails[$i]['mid'];?>,<?php echo $mypdetails[$i]['login_id'];?>)"> <?php echo date('d-m-Y',strtotime($mypdetails[$i]['apply_date']));?> </a></td>
											<td nowrap>
											<?php if($this->session->user_id == 7 && $mypdetails[$i]['dh_status']==0)
											{ ?> 
                                                  <img id="<?php echo urlencode($mypdetails[$i]['mid']);?>" class="mrfApprove" title="Approve" style="cursor: pointer;"  alt="" src="../images/icon/approve.png" />&nbsp;&nbsp; &nbsp;&nbsp; 
                                                   <img id="<?php echo urlencode($mypdetails[$i]['mid']);?>" class="mrfReject" title="Reject" style="cursor: pointer;"  alt="" src="../images/icon/reject.png" />
                                            <?php }
											elseif($mypdetails[$i]['dh_status']==1)  
												echo "Approved"; 
											elseif($mypdetails[$i]['dh_status']==2) 
												echo "Reject"; 
											else echo "Pending";?>
                                            </td> 
										</tr> 
										<?php } 
									}
									else
									{
									?>
										<tr>
												<td colspan="5" align="center">No records found</td>
										</tr>
									<?php
									}
									?>
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
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">My Resignation Application</h4>
				</div>
				<div class="modal-body">
				<h5>Do you want to cancel ?</h5>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
					<?php
						$res = count($my_resignation);
						echo $res;
					?>
					<button type="button" class="btn btn-primary">Yes</button>
				</div>
			</div>
		</div>
	</div>
</div>
