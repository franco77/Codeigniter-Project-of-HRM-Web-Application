<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('admin_settings/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">  
					<legend class="pkheader">Hall of Fame View</legend>
					<form id="searchForm" name="searchForm" method="POST" action="">
							<div class="well"> 
								<h4 class="box-title">Search</h4>
								<div class="row pkdsearch">
									<div class="col-md-2">
										<span class="pull-right">Choose Month :</span>
									</div>
									<div class="col-md-3">
										<select id="month" name="month" class="form-control">
												<?php 
												$curmonth = $monthh;
												for($i = 1 ; $i <= 12; $i++)
												{
												   $allmonth = date("F",mktime(0,0,0,$i,1,date("Y"))); ?>
												   <option value="<?php echo $i;?>" <?php if($curmonth==$i){echo 'selected';}?> ><?php echo $allmonth;?></option>
												<?php } ?>
										</select>
										 
									</div>
									<div class="col-md-2">
										<span class="pull-right">Choose Year :</span>
									</div>
									<div class="col-md-3">
										<select name="year" class="form-control input-sm">
										  <?php
										  $yr=date("Y");
										  for ($j=$yr;$j>=2009;$j--){
											if ($j == $yearr){
										 ?>
											<option value="<?php echo($j)?>" selected><?php echo($j)?></option>
										 <?php }else{?>
										 <option value="<?php echo($j)?>"><?php echo($j)?></option>
										 <?php }
										 }?>
										</select> 
									</div>
									<div class="col-md-2">
										<input type="submit" id="btnSearch" name="btnSearch" class="btn btn-info" value="Search" /> 
									</div>
								</div>
							</div>
					</form>
					<div class="row well">
						<div class="table-responsive" ng-show="filteredItems > 0">
							<table class="table table-striped">
								<thead>
									<tr class="info">
										<th>SL No</th>
										<th>EMP Name</th> 
										<th>Login Handler</th>
										<th>Title</th>
										<th>Description</th>
										<th>Month</th> 
										<th>Year</th> 
										<th>Status</th> 
										<th>Edit</th>
									</tr>
								</thead>
								<tbody>
								<?php $i = 1; if($famess != "") { foreach($famess as $v1):?>
									<tr>
										<td><?= $i ?></td>
										<td><?= $v1['full_name'] ?></td>
										<td><?= $v1['loginhandle'] ?></td>
										<td><?= $v1['title'] ?></td>
										<td><?= substr($v1['description'], 0, 15);  ?></td>
										<td><?php $monthName = date("F", mktime(0, 0, 0, $v1['month'], 10)); echo $monthName;  ?></td>
										<td><?= $v1['year'] ?></td>
										<td><?php if($v1['status']==1) echo "Active"; else echo "Disable" ?></td>
										<td><a href="<?= base_url('admin_settings/hall_of_fame'.'?id='.$v1['id']) ?>"><img src="<?= base_url('assets/images/icon/edit.gif') ?>"></a></td>
									</tr>
									
								<?php $i++; endforeach; } else { echo "No Data found"; }?>
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
</div>
