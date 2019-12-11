<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="regination" ng-init="init('<?php echo base_url() ?>')">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content ">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<legend class="pkheader">MY RESIGNATION/TERMINATION APPLICATION</legend> 
					<div class="row well">
						<div class="row pkdsearch"> 
						<div class="col-md-3">
							<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
						</div> 
						<div class="col-md-5">
							<p>Filtered {{ filtered.length }} of {{ totalItems}} total update records</p>
						</div> 
					</div>
						<div class="table-responsive" ng-show="filteredItems > 0">
							<table class="table table-striped table-hover">
								<thead>
									<tr class="info"> 
										<th>Name</th>
										<th>Employee Code</th>
										<th>Apply Date</th>
										<th>Emp Action</th>
										<th>R Manager Action</th>
										<th>HR Action</th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit"> 
										<td><a href="#" data-toggle="modal" title="View Details" data-target="#myModals_{{data.rid}}" >{{data.full_name}}</a></td>
										<td>{{data.loginhandle}}</td>
										<td>{{data.apply_date}}</td>
										<td>
											<a href="#" title="Cancel/Withdraw" data-toggle="modal" data-target="#myModal_{{data.rid}}" ng-if="data.emp_status == 0" ><span class="glyphicon glyphicon-check center-block resignation" style="text-align:center;"></span></a>
											<span ng-if="data.emp_status == 1" >Canceled</span>
										</td>
										<td>
											<span class="label label-info" ng-if="data.rm_status == 0" >Active</span>
											<span class="label label-info" ng-if="data.rm_status == 1" >Approved</span>
											<span class="label label-info" ng-if="data.rm_status == 2" >Rejected</span>
										</td>
										<td>
											<span class="label label-info" ng-if="data.hr_status == 0" >Active</span>
											<span class="label label-info" ng-if="data.hr_status == 1" >Approved</span>
											<span class="label label-info" ng-if="data.hr_status == 2" >Rejected</span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-lg-12" ng-show="filteredItems == 0">
							<div class="col-lg-12">
							<h4>No records found</h4>
							</div> 
						</div>
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
	<div ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
	<div class="modal fade" id="myModal_{{data.rid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
					
					<button type="button" class="btn btn-primary" ng-click="updateReginationEmpStatus(data.rid)" >Yes</button>
				</div>
			</div>
		</div>
	</div>
	</div>
	<!-- Modal -->
	<div ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
	
	<!-- Modal -->
	<div class="modal fade" id="myModals_{{data.rid}}" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">View Resignation/Termination Application Details</h4>
				</div>
				<div class="modal-body">
					<div class="resource_box">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="25%"><strong>Request From</strong></td>
								<td width="75%">{{data.full_name}} ({{data.loginhandle}})</td>
							</tr>
							<tr>
								<td><strong>Subject</strong></td>
								<td>{{data.subject}}</td>
							</tr>
							<tr>
								<td><strong>Reason of Separation</strong></td>
								<td>{{data.separation_name}}</td>
							</tr>
							<tr>
								<td><strong>LWD</strong></td>
								<td>{{data.lwd}}</td>
							</tr>
							<tr>
								<td><strong>RM Desc</strong></td>
								<td>{{data.rm_desc}}</td>
							</tr>
							<tr>
								<td><strong>HR Desc</strong></td>
								<td>{{data.hr_desc}}</td>
							</tr>
							<tr>
								<td><strong>Reason</strong></td>
								<td>{{data.message  }}</td>
							</tr>
							<tr>
								<td><strong>Request Date</strong></td>
								<td>{{data.apply_date  }}</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<!--<button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>-->
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal -->
	</div>
</div>
