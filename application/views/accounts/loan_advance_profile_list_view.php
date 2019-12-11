<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="AllLoan" ng-init="init('<?php echo base_url() ?>')">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('accounts/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<legend class="pkheader">All Employee Under You Applied Loan/Advance <strong>(</strong>{{totalItems}}<strong>)</strong> <small>(Resource of our Organisation)</small></legend>
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Track Status of My Leave Application </legend>
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
						<div class="table-responsive" >
							<table class="table table-striped table-bordered table-condensed">
								<thead class="thead-dark">
									<tr class="info">
										<th>#</th>
										<th>Name</th>
										<th>Employee Code</th>
										<th>Apply For</th>
										<th>Print</th>
										<th>Reporting Status</th>
										<th>Dept Head Status</th>
										<th>HR Status</th> 
										<th>Account Status</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getLoanAdvanceReject | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>{{data.full_name}}</a> </td>
										<td>{{data.loginhandle}}</td>
										<td> 
											<a  data-toggle="modal" data-target="#myModal_{{data.lid}}">{{data.applyfor}}</a>
										</td> 
										<td><a ng-click='openNewWindow(data.lid,data.login_id)'><img src="<?= base_url('assets/images/printer_icon.png') ?>" alt="printer_icon" target="_blank"></a></td> 
										<td ng-if="data.rm_status == 1" style="color:#0092D6;text-align:center;"><span>Approved</span></td>
										<td ng-if="data.rm_status == 2" style="color:#D9534F;text-align:center;"><span>Rejected</span></td>
										<td ng-if="data.rm_status == 0" style="color:#5CB85C;text-align:center;"><span>Pending</span></td>
										<td ng-if="data.dh_status == 1" style="color:#0092D6;text-align:center;"><span>Approved</span></td>
										<td ng-if="data.dh_status == 2" style="color:#D9534F;text-align:center;"><span>Rejected</span></td>
										<td ng-if="data.dh_status == 0" style="color:#5CB85C;text-align:center;"><span>Pending</span></td>
										<td ng-if="data.hr_status == 1" style="color:#0092D6;text-align:center;"><span>Approved</span></td>
										<td ng-if="data.hr_status == 2" style="color:#D9534F;text-align:center;"><span>Rejected</span></td>
										<td ng-if="data.hr_status == 0 && data.dh_status == 0" style="color:#5CB85C;text-align:center;"><span>Pending</span></td>
										
										<td ng-if="data.hr_status == 0" style="color:#5CB85C;text-align:center;">Pending</td> 
										<td ng-if="data.ac_status == 1" style="color:#0092D6;text-align:center;"><span>Approved</span></td>
										<td ng-if="data.ac_status == 2" style="color:#D9534F;text-align:center;"><span>Rejected</span></td>
										<td ng-if="data.ac_status == 0 && data.hr_status == 1 ">
											<div class="iCompassTip" style="text-align:center;" >
													<img  class="leaveApprove" src="<?php echo base_url(); ?>assets/images/icon/approve.png" alt="" title="Approve" ng-click="loanApporved(data.lid);" style="cursor: pointer;" />
													<a href="" data-toggle="modal" data-target="#rejectReson_{{data.lid}}">
														<img  class="leaveReject" src="<?php echo base_url(); ?>assets/images/icon/reject.png" alt="" title="Reject" style="cursor: pointer;"  />
													</a>
											</div>
											<div class="modal fade" id="rejectReson_{{data.lid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal">&times;</button>
															<h4 class="modal-title" id="exampleModalLabel">Loan/Advance Request Rejection<div id="head" style="display:inline;"></div></h4>
														</div>
														<div class="modal-body">
															<h5>Reason <span class="red">*</span></h5>
															<input type="hidden" name="lid" id="lid" required>
															<input type="hidden" name="status" id="status" required>
															<textarea name="reason" id="reason_{{data.lid}}"  ng-model="reasond" required="" cols="80" rows="7"></textarea>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> 
															<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success"  ng-click="loanRejected(data.lid);" >Submit</button>
														</div>
													</div>
												</div>
											</div>
										</td>
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
	<!-- Modal -->
	<div ng-repeat="data in getLoanAdvanceReject">
	<div class="modal fade" id="myModal_{{data.lid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">View Loan/Advance</h4>
				</div>
				<div class="modal-body">
					<div class="table-responsive" >
						<table class="table table-striped table-bordered table-condensed">
							<tbody>
								<tr>
									<td width="25%"><strong>Request From</strong></td>
									<td width="75%">{{data.full_name}}({{data.loginhandle}})</td>
								</tr>
								<tr>
									<td><strong>Apply For</strong></td>
									<td>{{data.applyfor}}</td>
								</tr>
								<tr>
									<td><strong>Eligiable Amount</strong></td>
									<td ng-if="data.applyfor == 'advance'">{{data.eligibilityAMount}}</td>
									<td ng-if="data.applyfor == 'loan'">{{data.eligibilityAMount}} </td>
								</tr>
								<tr>
									<td><strong>Apply Amount</strong></td>
									<td>{{data.amountapplied}}</td>
								</tr>
								<tr>
									<td><strong>No Of Instalment</strong></td>
									<td>{{data.eligibleinstalment}}</td>
								</tr>           
								<tr>
									<td><strong>Reason</strong></td>
									<td>{{data.message}}</td>
								</tr>
								<tr>
									<td><strong>Request Date</strong></td>
									<td>{{data.created_date}}</td>
								</tr>
							</tbody> 
						</table>
					</div>
				</div>
				<div class="modal-footer"> 
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>