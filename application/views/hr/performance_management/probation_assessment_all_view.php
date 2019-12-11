<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="probation" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<legend class="pkheader">Probation Assessment Detail ({{ totalItems}})<small>(Resource of our Organisation)</small></legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Manpower Requisition Filter </legend>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Department </label>
									<div class="col-sm-8">
										<select ng-model="searchDepartment" name="searchDepartment" id="searchDepartment" class="form-control input-sm" ng-change="designationFetch()"> 
											<option value="" >Select</option>
											<option ng-repeat="searchDepartment in getDepartments" ng-selected="{{ searchDepartment.selected == true }}" selected=""  value="{{ searchDepartment.dept_id }}">{{ searchDepartment.dept_name }}</option> 
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail"  class="col-sm-4 col-form-label">Name </label>
									<div class="col-sm-8">
										<input type="text" ng-model="searchName" name="searchName"  id="searchName" class="form-control input-sm"> 
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Designation </label>
									<div class="col-sm-8">
										<select ng-model="searchDesignation" name="searchDesignation" id="searchDesignation" class="form-control input-sm"> 
											<option value="" >Select</option>
											<option ng-repeat="searchDesignation in getDesignations" ng-selected="{{ searchDesignation.selected == true }}" selected=""  value="{{ searchDesignation.desg_id }}">{{ searchDesignation.desg_name }}</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Emp Code </label>
									<div class="col-sm-8">
										<input type="text" ng-model="searchEmpCode" name="searchEmpCode"  id="searchEmpCode" class="form-control input-sm"> 
									</div>
								</div>
							</div>
							 <div class="col-md-12">
								<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary pull-right" value="Search"ng-click="advanceFilter();" /> 
							 </div>
						</div>
					</div>
					<div class="row well">
						<div class="row pkdsearch"> 
							<div class="col-md-2">PageSize:
								<select ng-model="entryLimit" class="form-control input-sm">
									<option>10</option>
									<option>20</option>
									<option>30</option>
									<option>40</option>
									<option>50</option> 
								</select>
							</div>
							<div class="col-md-3">Search:
								<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
							</div> 
							<div class="col-md-5">
								<h5>Filtered {{ filtered.length }} of {{ totalItems}} total resumes </h5>
							</div>
						</div>
						<div class="table-responsive" ng-show="filteredItems > 0">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info">
										<th>#</th>
										<th>Name</th>
										<th>Employee Code</th>
										<th>Email</th>
										<th>Apply Date</th>
										<th>Status</th> 
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getprobation | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>{{data.name}}</a> </td>
										<td>{{data.loginhandle}}</td>
										<td>{{data.email}}</td>
										<td>{{data.apply_date}}</td>
										<td ng-if="data.dh_status == 1"><span class="label label-primary">Approved</span></td>
										<td ng-if="data.dh_status == 2"><span class="label label-danger">Rejected</span></td>
										<td ng-if="data.dh_status == 0"><span class="label label-success">Pending</span></td>
										<td><a ng-click="openNewWindow(data.mid,data.login_id);"><img alt="Print" style="cursor: pointer;" src='<?php echo base_url('assets/images/printer_icon.png')?>' /></a></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-12" ng-show="filteredItems == 0">
							<div class="col-md-12">
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
		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title" id="exampleModalLabel">Probation Assessment</h4>
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
</div>