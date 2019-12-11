<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="mymidyearreview" ng-init="init('<?php echo base_url() ?>')">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content ">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
					<legend class="pkheader">Mid Year Review Details</legend>
					<div class="row well">
						
					
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">My Mid Year Review Filter </legend>
							<div class="col-md-7">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-6 col-form-label">Annual Appraisal Year of </label>
									<div class="col-sm-6">
										<select name="searchYear" ng-model="searchYear" class="search_UI form-control input-sm">
											<?php
											$yr=date("Y")-1;
											for ($j=$yr;$j>=2014;$j--){
											?>
												<option value="<?php echo $j.'-'.($j+1);?>"  ><?php echo $j.'-'.($j+1);?></option>
											<?php } ?>
										</select>  
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group row">
								</div>
							</div>
							 <div class="col-md-2">
								<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary" value="Search" ng-click="advanceFilter();" /> 
							 </div>
						</div>
						<div class="table-responsive" >
							<div class="row ">
								<div class="col-md-2">
									<select ng-model="entryLimit" class="form-control input-sm">
										<option>10</option>
										<option>20</option>
										<option>30</option>
										<option>40</option>
										<option>50</option> 
									</select>
								</div>
								<!--<div class="col-md-3">Search:
									<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
								</div>--> 
								<div class="col-md-6">
									<h5>Filtered {{ filtered.length }} of {{ totalItems}} total resumes </h5>
								</div>
							</div>
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info">
										<th>Sl no</th>
										<th>Name</th>
										<th>Employee Code</th>
										<th>Date Of Application</th>
										<th>Remark</th> 
										<th>Status</th>
										<th>Action</th>												
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>{{data.name}}</td>
										<td>{{data.loginhandle}}</td>
										<td>{{data.apply_date}}</td>
										<td>{{data.remark}}</td>
										<td ng-if="data.dh_status == '0'">Pending</td>
										<td ng-if="data.dh_status == '1'">Approved</td>
										<td ng-if="data.dh_status == '2'">Reject</td> 
										<td ng-if="data.rm_status == '0'"><a href="<?php echo base_url(); ?>hr_help_desk/midyear_review_form_edit?id={{data.login_id}}&mid={{data.mid}}"><img alt="Edit" style="cursor: pointer;" src="<?php echo base_url(); ?>assets/images/icon/edit.png" /></a></td>
										<td ng-if="data.rm_status == '1'"><a ng-click="openNewWindow(data.mid,data.login_id,data.applydate);"><img alt="Print" style="cursor: pointer;" src="<?php echo base_url(); ?>assets/images/printer_icon.png" /></a></td>
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="7" align="center">No records found</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- /.table-responsive -->
					</div>
					<div class="row">
						<div class="col-lg-12" ng-show="filteredItems > 0">    
							<div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-sm" max-size="entryLimit" boundary-link-numbers="true" rotate="false" previous-text="&laquo;" next-text="&raquo;"></div> 
						</div>
						<!-- /.col-lg-4 (nested) -->
						<div class="col-lg-8">
							<div id="morris-bar-chart"></div>
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
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">My Resignation Application</h4>
				</div>
				<div class="modal-body">
				<h5>Mid Year Review Form</h5>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button> 
					<button type="button" class="btn btn-primary">Yes</button>
				</div>
			</div>
		</div>
	</div>
</div>
