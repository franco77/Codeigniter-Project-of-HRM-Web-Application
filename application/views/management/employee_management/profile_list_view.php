<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="profileList" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('management/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9"> 
					<legend class="pkheader">All Employee Details({{ totalItems}})</legend> 
					<div class="row well"> 
					
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Search/View Filter </legend>
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
									<label for="staticEmail" class="col-sm-4 col-form-label">Name </label>
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
							 <div class="col-md-2">
								<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary" value="Search" ng-click="advanceFilter();" /> 
							 </div>
						</div>
					
						<div class="row pkdsearch"> 
							<div class="col-md-2">PageSize:
								<select ng-model="entryLimit" class="form-control input-sm">
									<option ng-selected="entryLimit">10</option>
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
								<h5>Filtered {{ filtered.length }} of {{ totalItems}} total employees </h5>
							</div>
						</div>
						<div class="table-responsive" >
							<table class="table table-striped table-bordered table-condensed table-hover">
								<thead>
									<tr class="info">
										<th>#</th>
										<th>Name<a ng-click="sort_by('name');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Employee Code<a ng-click="sort_by('loginhandle');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Email<a ng-click="sort_by('email');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Designation<a ng-click="sort_by('desg_name');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Status</th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getprofilelist | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td><a ng-href="<?php echo base_url(); ?>en/management/general_readonly?id={{data.login_id}}" ng-bind="data.name;" ></a> </td>
										<td>{{data.loginhandle}}</td>
										<td>{{data.email}}</td> 
										<td>{{data.desg_name}}</td>
										<td ng-if="data.user_status == 1"><span class="label label-success">Active</span></td> 
										<td ng-if="data.user_status == 2"><span class="label label-success">Inactive</span></td> 
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="6" align="center">No records found</td> 
									</tr>
								</tbody>
							</table>
						</div>
					</div> 
					<div ng-show="filteredItems > 0">    
						<div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-sm" max-size="entryLimit" boundary-link-numbers="true" rotate="false" previous-text="&laquo;" next-text="&raquo;"></div>
					</div> 
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div> 
</div>