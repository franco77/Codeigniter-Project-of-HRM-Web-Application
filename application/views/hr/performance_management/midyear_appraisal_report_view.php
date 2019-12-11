<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="midYearAooraisal" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<a ng-click="exportToExcel()" class="btn btn-primary pull-right " role="button" style="margin-right: 10px; margin-top: 4px;" target="_blank"><i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Export To Excel</a>
					<legend class="pkheader">Mid-year Review Report({{ totalItems}}) <small>(Mid-year Appraisal Report of {{searchYear}})</small></legend>
					<div class="row well">
					
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Mid Year Review Filter </legend>
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
							<div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-4 col-form-label">Review Year of </label>
									<div class="col-sm-8">
										<select name="searchYear" ng-model="searchYear" class="search_UI form-control input-sm">
											<?php
											  $m = date('m');
												$y = date('Y');
												if($m >=4){
													$yr = date('Y');
												}
												else{
													$yr = $y - 1;
												}
											  for ($j=$yr;$j>=2014;$j--){
											  if ($j == $fyear){
											 ?>
											  <option value="<?php echo $j.'-'.($j+1);?>" selected ><?php echo $j.'-'.($j+1);?></option>
											 <?php }else{?>
											 <option value="<?php echo $j.'-'.($j+1);?>" ><?php echo $j.'-'.($j+1);?></option>
											 <?php }
											 }?> 
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
						<div class="table-responsive" >
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info">
										<th>#</th>
										<th>Name</th>
										<th>Employee Code</th>
										<th>Date Of Report</th>
										<th>Designation</th>
										<th>% Of Progressive Report</th>
										<th>Emp Development</th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getmidYearAooraisal | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td width="5%">{{$index+1}}</td>
										<td width="15%"><a ng-href="<?php echo base_url(); ?>en/hr/general_readonly?id={{data.login_id}}" ng-bind="data.name;" ></a>  </td>
										<td width="15%">{{data.loginhandle}}</td>
										<td width="15%">{{data.apply_date}}</td> 
										<td width="15%">{{data.desg_name}}</td> 
										<td width="15%">{{data.per_progress}}</td> 
										<td  width="20%">{{data.employee_development}}</td> 
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="7" align="center">No records found</td>
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
								<div class="col-md-6">
									<h5>Filtered {{ filtered.length }} of {{ totalItems}} Total Mid-year Appraisal Report </h5>
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