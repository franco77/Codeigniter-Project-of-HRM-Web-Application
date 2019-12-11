<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="annualAppraisal" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<legend class="pkheader">Annual Appraisal Detail ({{ totalItems}}) <small>(Resource of our Organisation)</small>
						<a  class="btn btn-primary pull-right btn-sm" role="button" style="margin-right: 10px; margin-top: -4px;" target="_blank" ng-click="exportToExcel()"><i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Export To Excel</a>
					</legend>
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
						<div class="table-responsive" ng-show="filteredItems > 0">
							<table class="table table-striped table-bordered table-condensed">
								<thead>
									<tr class="info">
										<th>#</th>
										<th>EMP. Name</th>
										<th>Date Of Application</th>
										<th>Date Of Review</th>
										<th>Application Details</th>
										<th>RM Status</th>
										<th>Review Status</th> 
										<th>Remark</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getAnnualAppraisal | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>{{data.name}} - {{data.loginhandle}}</td>
										<td>{{data.apply_date | date: 'fullDate'}}</td>
										<td>{{data.approved_date | date: 'fullDate'}}</td>
										<td ng-if="data.dh_status == '0' "  align="center"><a ng-click="openNewWindow(data.mid,data.login_id,data.applydate);"><img alt="Print" style="cursor: pointer;" src="<?php echo base_url(); ?>assets/images/printer_icon.png" /></a></td>
										<td ng-if="data.dh_status == '1' "  align="center"><a ng-click="openNewWindow(data.mid,data.login_id,data.applydate);"><img alt="Print" style="cursor: pointer;" src="<?php echo base_url(); ?>assets/images/printer_icon.png" /></a></td>
										<td ng-if="data.dh_status == '2' "  align="center"><a ng-click="openNewWindow(data.mid,data.login_id,data.applydate);"><img alt="Print" style="cursor: pointer;" src="<?php echo base_url(); ?>assets/images/printer_icon.png" /></a></td>
										<td ng-if="data.rm_status == 1"><span class="label label-primary">Approved</span></td>
										<td ng-if="data.rm_status == 2"><span class="label label-danger">Rejected</span></td>
										<td ng-if="data.rm_status == 0"><span class="label label-success">Pending</span></td>
										<td ng-if="data.dh_status == 1"><span class="label label-primary">Approved</span></td>
										<td ng-if="data.dh_status == 2"><span class="label label-danger">Rejected</span></td>
										<td ng-if="data.dh_status == 0"><span class="label label-success">Pending</span></td>
										<td><textarea name="remark"  rows="1" style="width: 110px;" ng-keyup="updateRemark($event, data.mid)" > {{data.remark}} </textarea></td>
									</tr>
								</tbody>
							</table>
							
							<div class="row "> 
								<div class="col-md-2">
									<select ng-model="entryLimit" class="form-control input-sm">
										<option>10</option>
										<option>20</option>
										<option>30</option>
										<option>40</option>
										<option>50</option> 
									</select>
								</div>
								<!--<div class="col-md-3">Search:
									<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
								</div> -->
								<div class="col-md-6">
									<h5>Filtered {{ filtered.length }} of {{ totalItems}} total resumes </h5>
								</div>
							</div>
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
</div>