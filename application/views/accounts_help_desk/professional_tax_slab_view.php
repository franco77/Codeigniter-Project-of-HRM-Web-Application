<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>  
<div class="section main-section" ng-app="myApp" ng-controller="accounts" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('accounts_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<span  class="pull-right " role="button" style="margin-right: 10px; margin-top: 4px;" > <span style="color:#fff;">FY : </span>
						<select ng-model="searchYear" name="searchYear" id="searchYear" class="input-sm" ng-change="getFYData();" > 
							<?php
								$m = date('m');
								$y = date('Y');
								if($m >=4){
									$yr = date('Y');
								}
								else{
									$yr = $y - 1;
								}
							  //$yr=date("Y");
							  for ($j=$yr; $j>=2017;$j--){
							  if ($j == $dd_year){
							 ?>
							  <option value="<?php echo $j.'-'.($j+1);?>" selected ><?php echo $j.'-'.($j+1);?></option>
							 <?php }else{?>
							 <option value="<?php echo $j.'-'.($j+1);?>" ><?php echo $j.'-'.($j+1);?></option>
							 <?php }
							 }?> 
						</select>
					</span>
					<legend class="pkheader">Professional Tax Slab<small>(Define Professional Slab Here)</small></legend> 
					<div class="row well"> 
						<div class="table-responsive" ng-show="filteredItems > 0">
							<table class="table table-striped table-hover table-bordered">
								<thead>
									<tr class="info">
										<th>Sl no</th>
										<th>PT Slab Range</th>
										<th>PT Value</th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>{{data.range}}</td>
										<td>{{data.pt_value}}</td> 
									</tr>
								</tbody>
							</table>
						</div>
						<!-- /.table-responsive -->
					</div>
					<div class="row">
						<div class="col-lg-12" ng-show="filteredItems == 0">
							<div class="col-lg-12">
							<h4>No records found</h4>
							</div> 
						</div>
						<!--<div class="col-lg-12" ng-show="filteredItems > 0">    
							<div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="&laquo;" next-text="&raquo;"></div> 
						</div> 
						<div class="col-lg-8">
							<div id="morris-bar-chart"></div>
						</div> -->
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div> 
</div>
