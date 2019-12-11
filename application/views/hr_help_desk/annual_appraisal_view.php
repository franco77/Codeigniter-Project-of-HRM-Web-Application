<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="annualAppraisal" ng-init="init('<?php echo base_url() ?>')">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9"> 
					<legend class="pkheader">Annual Appraisal Details</legend> 
					<div class="row well">
					
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Annual Appraisal Filter </legend>
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
									<label for="staticEmail" class="col-sm-4 col-form-label">Annual Appraisal Year of </label>
									<div class="col-sm-8">
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
							<div class="col-md-5">
								<div class="form-group row">
								</div>
							</div>
							 <div class="col-md-2">
								<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary" value="Search" ng-click="advanceFilter();" /> 
							 </div>
						</div>
						<!--<div class="row pkdsearch">
							<div class="col-md-2">PageSize:
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
							<div class="col-md-3">Search:
								<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
							</div> 
							<div class="col-md-6">
								<h5>Filtered {{ filtered.length }} of {{ totalItems}} total resumes </h5>
							</div>
						</div>-->
						<div class="table-responsive" >
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr  class="info">
										<th>#</th>
										<th>Emp. Name</th>
										<th>Emp. Code</th>
										<th>Date of Application</th>
										<th>Date of Review</th>
										<th>Action</th>
										<th>Rm Status</th>
										<th>Review Status</th>
										<th>Remark</th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>{{data.name}}</td>
										<td>{{data.loginhandle}}</td>
										<td>{{data.apply_date | date: 'fullDate'}}</td>
										<td>{{data.approved_date | date: 'fullDate'}}</td> 
										<td ng-if="data.rm_status == '0' && data.dh_status == '0' "  align="center">
											<a href="<?php echo base_url(); ?>hr_help_desk/annual_appraisal_form_rm?id={{data.login_id}}&mid={{data.mid}}"><img alt="Edit" style="cursor: pointer;" src="<?php echo base_url(); ?>assets/images/icon/edit.gif" /></a>
										</td>
										<td ng-if="data.rm_status == '1' && data.dh_status == '0' "  align="center">
											<a ng-click="openNewWindow(data.mid,data.login_id,data.applydate);"><img alt="Print" style="cursor: pointer;" src="<?php echo base_url(); ?>assets/images/printer_icon.png" /></a>
										</td>
										<td ng-if="data.dh_status == '1' ||  data.dh_status == '2'"  align="center"><a ng-click="openNewWindow(data.mid,data.login_id,data.applydate);"><img alt="Print" style="cursor: pointer;" src="<?php echo base_url(); ?>assets/images/printer_icon.png" /></a></td>
										
										<td ng-if="data.rm_status == '0' "><span class="label label-success">Pending</span></td>
										<td ng-if="data.rm_status == '1' "><span class="label label-primary">Approved</span></td>
										<td ng-if="data.rm_status == '2' "><span class="label label-danger">Rejected</span></td>
										<td ng-if="data.dh_status == '0' "><span class="label label-success">Pending</span> </td>
										<td ng-if="data.dh_status == '1' "><span class="label label-primary">Approved</span></td>
										<td ng-if="data.dh_status == '2' "><span class="label label-danger">Rejected</span></td>
										<td>
										<textarea name="remark"  rows="1" style="width: 110px;" ng-keyup="updateRemark($event, data.mid)" > {{data.remark}} </textarea></td>
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="8" align="center">No Data</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div ng-show="filteredItems > 0">    
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
</div>