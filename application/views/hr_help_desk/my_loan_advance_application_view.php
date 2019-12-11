<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="myLoanAdvance" ng-init="init('<?php echo base_url() ?>')">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<legend class="pkheader">My Loan/Advance Application</legend>  
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
										<th>Apply For</th>
										<th>Reporting Status</th>
										<th>Dept. Head Status</th>
										<th>HR Status</th>
										<th>Account Status</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit"> 
										<td>{{data.name}}</td>
										<td>{{data.loginhandle}}</td>
										<td>{{data.applyfor}}</td>
										<td ng-if="data.rm_status == '0'">Pending</td>
										<td ng-if="data.rm_status == '1'"><span class="label label-info" >Approved</span></td>
										<td ng-if="data.rm_status == '2'">Rejected</td> 
										<td ng-if="data.dh_status == '0'">Pending</td>
										<td ng-if="data.dh_status == '1'"><span class="label label-info" >Approved</span></td>
										<td ng-if="data.dh_status == '2'">Rejected</td> 
										<td ng-if="data.hr_status == '0'">Pending</td>
										<td ng-if="data.hr_status == '1'"><span class="label label-info" >Approved</span></td>
										<td ng-if="data.hr_status == '2'">Rejected</td>
										<td ng-if="data.ac_status == '0'">Pending</td>
										<td ng-if="data.ac_status == '1'"><span class="label label-info" >Approved</span></td>
										<td ng-if="data.ac_status == '2'">Rejected</td> 
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
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">My Resignation Application</h4>
				</div>
				<div class="modal-body">
				<h5>Do you want to cancel ?</h5>
				<h2>Development Pending</h2>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button> 
					<button type="button" class="btn btn-primary">Yes</button>
				</div>
			</div>
		</div>
	</div>
</div>
