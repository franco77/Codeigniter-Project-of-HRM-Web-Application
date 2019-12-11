<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="goalApproveReject" ng-init="init('<?php echo base_url() ?>')">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs"> 
						<legend class="pkheader">All Employee Under You Updated Goal/JD</legend> 
					<div class="row well">
						<div class="row pkdsearch"> 
							<div class="col-md-3">
								<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
							</div> 
							<div class="col-md-3">
								<p>Filtered {{ filtered.length }} of {{ totalItems}} total update records</p>
							</div>
						</div>
						<div class="table-responsive" ng-show="filteredItems > 0">
							<table class="table table-striped table-hover">
								<thead>
									<tr class="info">
										<th>Sl no</th>
										<th>Name</th>
										<th>Employee Code</th>
										<th>Apply Date</th>
										<th>R. Manager Action</th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td><a href="#" data-toggle="modal" data-target="#myModal_{{data.login_id}}">{{data.full_name}}</a></td>
										<td>{{data.loginhandle}}</td>
										<td>{{data.created_date}}</td>
										<td ng-if="data.rm_status == '0'">
											
												<div class="iCompassTip" >
													<img  class="leaveApprove" src="<?php echo base_url(); ?>assets/images/icon/approve.png" alt="" title="Approve" ng-click="goalApporved(data.login_id);" style="cursor: pointer;" />
												</div>
										
										</td>
										<td ng-if="data.rm_status == '1'">Approved</td>
										<td ng-if="data.rm_status == '2'">Reject</td> 
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
	<div class="modal fade" id="myModal_{{data.login_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">View Goal Details of {{data.full_name}}({{data.loginhandle}})</h4>
				</div>
				<div class="modal-body">
					<div class="table-responsive" >
					<table class="table table-striped table-bordered table-condensed">
						<thead>
							<tr class="info">
								<th>Sl no</th>
								<th>Performance Objectives</th>
								<th>Target</th>
								<th>Weightage</th>
								<th>Progress(%)</th> 
							</tr>
						</thead>
						<tbody class="bodySec">
							<tr ng-repeat="datas in data.goals">
								<td>{{$index+1}}</td>
								<td>{{datas.objective}}</a></td>
								<td>{{datas.target}}</td>
								<td>{{datas.weightage}}</td>
								<td>{{datas.progress}}</td>
							</tr>
						</tbody> 
					</table>
				</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
				</div>
			</div>
		</div>
	</div>
</div>
