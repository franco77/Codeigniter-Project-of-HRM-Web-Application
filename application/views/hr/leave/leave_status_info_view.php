<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="leavestatusinfo" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<a  class="btn btn-primary pull-right btn-sm" role="button" style="margin-right: 10px; margin-top: 4px;" target="_blank" ng-click="exportToExcel()"><i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Export To Excel</a>
					<legend class="pkheader">Employee Leave Status Information</legend> 
					<div class="row well"> 
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Track Leave Status Information </legend>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Emp Code </label>
									<div class="col-sm-8">
										<input type="text" ng-model="searchEmpCode" name="searchEmpCode"  id="searchEmpCode" class="form-control input-sm"> 
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
									<label for="staticEmail" class="col-sm-4 col-form-label">Start Date </label>
									<div class="col-sm-8">
										<input type="text" id="searchStartDate" name="searchStartDate" ng-model="searchStartDate"  class="search_UI form-control datepickerShow" >
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">End Date </label>
									<div class="col-sm-8">
										<input type="text" id="searchEndDate" name="searchEndDate" ng-model="searchEndDate"  class="search_UI form-control datepickerShow" >
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Status </label>
									<div class="col-sm-8">
										<select name="searchStatus" class="search_UI form-control" id="searchStatus" ng-model="searchStatus">
                                             <option value="">Select Leave Status</option>
                                             <option value="A" >Approved</option>
                                             <option value="R" >Rejected</option>
                                             <option value="P" >Pending</option>
                                             <option value="W" >Withdraw</option>
                                             <option value="CA" >Cancel Approved</option>
                                             <option value="CR" >Cancel Rejected</option>
                                             <option value="CP">Cancel Pending</option>
                                         </select>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group row">
								</div>
							</div>
							 <div class="col-md-2">
								<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary" value="Search" ng-click="advanceFilter();" /> 
							 </div>
						</div>
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info">
										<th>#</th>
										<th>Name<a ng-click="sort_by('full_name');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Employee Code<a ng-click="sort_by('loginhandle');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Leave From<a ng-click="sort_by('leave_from');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Leave To<a ng-click="sort_by('leave_to');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Status<a ng-click="sort_by('status');"><i class="glyphicon glyphicon-sort"></i></a></th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getlatecoming | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td><a ng-href="<?php echo base_url(); ?>en/hr/general_readonly?id={{data.login_id}}" ng-bind="data.full_name;" ></a> </td>
										<td>{{data.loginhandle}}</td>
										<td>{{data.leave_from}}</td> 
										<td>{{data.leave_to}}</td>
										<td ng-if="data.status == 'A'" style="color:#0092D6;text-align:center;"><span>Approved</span></td>
										<td ng-if="data.status == 'R'" style="color:#D9534F;text-align:center;"><span>Rejected</span></td>
										<td ng-if="data.status == 'P'" style="color:#5CB85C;text-align:center;"><span>Pending</span></td> 
										<td ng-if="data.status != 'P' && data.status != 'R' && data.status != 'A'" style="color:#5CB85C;text-align:center;"><span>{{data.status}}</span></td> 
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="6" align="center">No records found</td> 
									</tr>
								</tbody>
							</table>
							<div class="row ">
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
								<div class="col-md-3">
									<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
								</div> 
								<div class="col-md-5">
									<h5>Filtered {{ filtered.length }} of {{ totalItems}} total late coming info </h5>
								</div>
							</div>
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

<script>
 var site_url = '<?php echo base_url(); ?>';
$(document).ready(function(){
	$('.datepickerShow').datepicker({
         dateFormat: 'dd-mm-yy'
	});
});
</script>