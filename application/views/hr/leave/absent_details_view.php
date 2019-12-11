<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="absentdetails" ng-init="init('<?php echo base_url() ?>')">
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
					<legend class="pkheader">Employee Absent Information</legend> 
					<div class="row well"> 
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Track Employee Absent Information </legend>
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
										<th>Date<a ng-click="sort_by('date');"><i class="glyphicon glyphicon-sort"></i></a></th>
										<th>Reason<a ng-click="sort_by('in_time');"><i class="glyphicon glyphicon-sort"></i></a></th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getabsentdeatils | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td><a ng-href="<?php echo base_url(); ?>en/hr/general_readonly?id={{data.login_id}}" ng-bind="data.full_name;" ></a> </td>
										<td>{{data.loginhandle}}</td>
										<td>{{data.date}}</td> 
										<td ng-if="data.att_status == 'W'"><span class="label label-success">LWH</span></td> 
										<td ng-if="data.att_status != 'W'"><span class="label label-success">Absent</span></td> 
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="6" align="center">No records found</td> 
									</tr>
								</tbody>
							</table>
							<div class="row " ng-show="filteredItems > 0"> 
								<div class="col-md-2">
									<select ng-model="entryLimit" class="form-control input-sm">
										<option value="10" ng-selected="{{entryLimit == 10}}">10</option>
										<option value="20" ng-selected="{{entryLimit == 20}}">20</option>
										<option value="30" ng-selected="{{entryLimit == 30}}">30</option>
										<option value="40" ng-selected="{{entryLimit == 40}}">40</option>
										<option value="50" ng-selected="{{entryLimit == 50}}">50</option>
										<option value="100" ng-selected="{{entryLimit == 100}}">100</option>
										<option value="200" ng-selected="{{entryLimit == 200}}">200</option>
										<option value="300" ng-selected="{{entryLimit == 300}}">300</option> 
										<option value="400" ng-selected="{{entryLimit == 400}}">400</option> 
										<option value="500" ng-selected="{{entryLimit == 500}}">500</option>  
									</select>
								</div>
								<!--<div class="col-md-3">Search:
									<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
								</div> -->
								<div class="col-md-5">
									<h5>Filtered {{ filtered.length }} of {{ totalItems}} total resumes </h5>
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