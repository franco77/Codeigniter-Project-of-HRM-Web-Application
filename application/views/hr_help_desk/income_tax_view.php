<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
 
<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<h4 class="section-title">
				<span class="c-dark">HR Help Desk</span>
			</h4>
		</div>
	</div>
</div>
 
<div class="section main-section">
    <div class="container page-content"> 
		<div class="form-content ">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content ">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<div class="form-content page-content">
						<div class="box">
							<div class="box-header">
								<h3 class="box-title">Leave Request</h3> 
								<div class="box-tools">
									<div class="input-group input-group-sm" style="width: 150px;">
										<input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

										<div class="input-group-btn">
										<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
										</div>
									</div>
								</div>
							</div>
							<!-- /.box-header -->
							<div class="box-body table-responsive no-padding">
								<table class="table table-hover">
									<tr>  
										<th>Reporting Mgr</th>
										<th>From</th>
										<th>To</th>
										<th>Type</th>
										<th>Reason</th>
										<th>Req. Date</th>
										<th>Status / Action</th>
									</tr>
									<tr>
										<td>rb swain</td>
										<td>11-7-2014</td>
										<td>11-7-2014</td>
										<td>PL</td>
										<td>health problem</td>
										<td>11-7-2014</td>
										<td><span class="label label-success">Approved</span></td>
									</tr>
									<tr>
										<td>rb swain</td>
										<td>11-7-2014</td>
										<td>11-7-2014</td>
										<td>SL</td>
										<td>health problem</td>
										<td>11-7-2014</td>
										<td><span class="label label-warning">Pending</span></td> 
									</tr>
									<tr>
										<td>rb swain</td>
										<td>11-7-2014</td>
										<td>11-7-2014</td>
										<td>PL</td>
										<td>health problem</td>
										<td>11-7-2014</td>
										<td><span class="label label-danger">Denied</span></td> 
									</tr>
									<tr>
										<td>rb swain</td>
										<td>11-7-2014</td>
										<td>11-7-2014</td>
										<td>PL</td>
										<td>health problem</td>
										<td>11-7-2014</td>
										<td><span class="label label-danger">Denied</span></td>
									</tr>
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