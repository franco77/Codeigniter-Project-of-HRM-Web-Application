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
							  for ($j=$yr;$j>=2017;$j--){
							  if ($j == $dd_year){
							 ?>
							  <option value="<?php echo $j.'-'.($j+1);?>" selected ><?php echo $j.'-'.($j+1);?></option>
							 <?php }else{?>
							 <option value="<?php echo $j.'-'.($j+1);?>" ><?php echo $j.'-'.($j+1);?></option>
							 <?php }
							 }?> 
						</select>
					</span>
					<legend class="pkheader">Income Tax Slab <small>(Income Tax slab here)</small></legend> 
					<div class="row well"> 
						<div class="table-responsive" ng-show="filteredItems > 0">
							<table class="table table-striped table-hover table-bordered">
								<thead>
									<tr class="info">
										<th>Sl no</th>
										<th>Income Range (in Rupees)</th>
										<th>Tax Rate (in %age)</th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>{{data.range}}</td>
										<td>{{data.it_value}}</td> 
									</tr>
								</tbody>
							</table>
							<table cellpadding="0" cellspacing="0"  width="100%">
								<tr align="center" colspan="7">
									<td><strong>Taxable income 50lakh to 1 crore = surcharge 10%</strong></td>
								</tr>
								<tr align="center" colspan="7"> 
									<td><strong>Taxable income above 1 crore = surcharge 15%</strong></td>
								</tr> 
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
