<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="AllLoan" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<legend class="pkheader">Applied Loan Application ({{totalItems}}) <small>(Loan Application Details)</legend> 
					<div class="row well">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info">
										<th>#</th>
										<th>Name</th>
										<th>Employee Code</th>
										<th>Email</th>
										<th>Reporting Status</th>
										<th>Dept Head Status</th>
										<th>HR Status</th> 
										<th>Account Status</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getloan | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td><a ng-href="<?php echo base_url(); ?>en/hr/general_readonly?id={{data.login_id}}" ng-bind="data.name;" ></a>   </td>
										<td>{{data.loginhandle}}</td>
										<td>{{data.email}}</td> 
										<td ng-if="data.rm_status == 1"><span class="label label-primary">Approved</span></td>
										<td ng-if="data.rm_status == 2"><span class="label label-danger">Rejected</span></td>
										<td ng-if="data.rm_status == 0"><span class="label label-success">Pending</span></td>
										<td ng-if="data.dh_status == 1"><span class="label label-primary">Approved</span></td>
										<td ng-if="data.dh_status == 2"><span class="label label-danger">Rejected</span></td>
										<td ng-if="data.dh_status == 0"><span class="label label-success">Pending</span></td>
										<td ng-if="data.hr_status == 1"><span class="label label-primary">Approved</span></td>
										<td ng-if="data.hr_status == 2"><span class="label label-danger">Rejected</span></td>
										<td ng-if="data.hr_status == 0"><span class="label label-success">Pending</span></td>										
										<td ng-if="data.ac_status == 1"><span class="label label-primary">Approved</span></td>
										<td ng-if="data.ac_status == 2"><span class="label label-danger">Rejected</span></td>
										<td ng-if="data.ac_status == 0"><span class="label label-success">Pending</span></td>
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="8" align="center">No records found</td> 
									</tr>
								</tbody>
							</table>
							<div class="row"> 
								<div class="col-md-2">
									<select ng-model="entryLimit" class="form-control input-sm">
										<option value="10" ng-selected="{{entryLimit == 10}}">10</option>
										<option value="20" ng-selected="{{entryLimit == 20}}">20</option>
										<option value="30" ng-selected="{{entryLimit == 30}}">30</option>
										<option value="40" ng-selected="{{entryLimit == 40}}">40</option>
										<option value="50" ng-selected="{{entryLimit == 50}}">50</option>
										<option value="100" ng-selected="{{entryLimit == 100}}">100</option>
									</select>
								</div>
								<!-- <div class="col-md-3">Search:
									<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
								</div> -->
								<div class="col-md-6">
									<h5>Filtered {{ filtered.length }} of {{ totalItems}} total resumes </h5>
								</div>
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
</div>