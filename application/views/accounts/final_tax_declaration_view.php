<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="accounts" ng-init="init('<?php echo base_url() ?>')">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('accounts/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">   
					<span  class="pull-right " role="button" style="margin-right: 10px; margin-top: 4px;" > <span style="color:#fff;">FY : </span>
						<select ng-model="searchYear" name="searchYear" id="searchYear" class="input-sm" ng-change="getFYData();" > 
							<?php
							  $yr=date("Y");
							  for ($j=$yr;$j>=2017;$j--){
							  //if ($j == $dd_year){
							 ?>
							  <!--<option value="<?php echo $j.'-'.($j+1);?>" selected ><?php echo $j.'-'.($j+1);?></option>-->
							 <?php //}else{?>
							 <option value="<?php echo $j.'-'.($j+1);?>" ><?php echo $j.'-'.($j+1);?></option>
							 <?php //}
							 }?> 
						</select>
					</span>
					<legend class="pkheader">Final Tax Declation Detail<small>(Resource of our Organisation)</small></legend>
					<div class="row well">
						<div class="table-responsive" ng-show="filteredItems > 0">
							<table class="table table-striped">
								<thead>
									<tr class="info">
										<th>SL No</th>
										<th>EMP Name</th> 
										<th>Date Of Application</th>
										<th>Date Of Review</th>
										<th>FY</th>
										<th>Details</th>
										<th>Ac Status</th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getestimated | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>{{data.name}}({{data.loginhandle}})</td>
										<td>{{data.apply_date}}</td> 
										<td>{{data.modified_date}}</td>
										<td>{{data.fyear}} - {{data.fyearS}}</td>
										<td ng-if="data.ac_status == '0'">
											<a href="<?php echo base_url(); ?>accounts_admin/final_declaration_form?id={{data.tid}}&lid={{data.login_id}}"><img alt="Edit" src='<?php echo base_url('assets/images/icon/edit.png');?>'/></a>
										</td>
										<td ng-if="data.ac_status == '1' || data.ac_status == '2'">
										<a ng-click="openNewWindow(data.tid,data.login_id);" ><img alt="Print" src='<?php echo base_url('assets/images/printer_icon.png');?>'/></a></td> 
										<td ng-if="data.ac_status == '0'"><span class="label label-warning">Pending</span></td>
										<td ng-if="data.ac_status == '1'"><span class="label label-primary">Approved</span></td>  										
										<td ng-if="data.ac_status == '2'"><span class="label label-danger">Rejected</span></td>  										
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-lg-12" ng-show="filteredItems == 0">
							<div class="col-lg-12">
							<h4>No records found</h4>
							</div> 
						</div>
						<!-- /.table-responsive -->
					</div>
					<div class="row"> 
						<div ng-show="filteredItems > 0">    
							<div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-sm" max-size="entryLimit" boundary-link-numbers="true" rotate="false" previous-text="&laquo;" next-text="&raquo;"></div> 
						</div> 
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
    </div>
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content modal-lg">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">Incom Tax Declaration(Estimation)</h4>
				</div>
				<div class="modal-body">
				<h5>Do you want to cancel ?</h5>
				<h2>Development Pending</h2>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button> 
				</div>
			</div>
		</div>
	</div>
</div>

