<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="midyearreview" ng-init="init('<?php echo base_url() ?>')">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('hr_help_desk/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">Mid Year Review Details</legend>
					<div class="row well"> 
						<!-- /.panel-heading -->
						<div class="panel-body"> 
					
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
									<label for="staticEmail" class="col-sm-4 col-form-label">Annual Appraisal Year of </label>
									<div class="col-sm-8">
										<select name="searchYear" ng-model="searchYear" class="search_UI form-control input-sm">
											<?php
											$yr=date("Y");
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
							<!--<div class="well"> 
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
									<div class="col-md-6">
										<h5>Filtered {{ filtered.length }} of {{ totalItems}} total resumes </h5>
									</div>
								</div>
							</div>-->
							<div class="row"> 
								<div class="table-responsive" >
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
										</div>--> 
										<div class="col-md-6">
											<h5>Filtered {{ filtered.length }} of {{ totalItems}} total resumes </h5>
										</div>
									</div>
									<table class="table table-striped table-bordered table-condensed">
										<thead>
											<tr class="info">
												<th>#</th>
												<th>Emp. Name</th>
												<th>Date of Application</th>
												<th>Date of Review</th>
												<th>Application Detail</th>
												<th>RM Status</th>
												<th>Review Status</th>
												<th>Remark</th> 
											</tr>
										</thead>
										<tbody>
											<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
												<td>{{$index+1}}</td>
												<td>{{data.name}} - {{data.loginhandle}}</td>
												<td>{{data.apply_date | date: 'fullDate'}}</td>
												<td>{{data.approved_date | date: 'fullDate'}}</td>
												<td ng-if="data.rm_status == '0' " align="center"><a href="<?php echo base_url(); ?>hr_help_desk/midyear_review_form_rm?id={{data.login_id}}&mid={{data.mid}}"><img alt="Edit" style="cursor: pointer;" src="<?php echo base_url(); ?>assets/images/icon/edit.png" /></a></td>
												<td ng-if="data.rm_status == '1' "  align="center"><a ng-click="openNewWindow(data.mid,data.login_id,data.applydate);"><img alt="Print" style="cursor: pointer;" src="<?php echo base_url(); ?>assets/images/printer_icon.png" /></a></td>
												<td ng-if="data.rm_status == '2' ">Reject</td>
												
												<td ng-if="data.rm_status == '0' ">Pending</td>
												<td ng-if="data.rm_status == '1' ">Approved</td>
												<td ng-if="data.rm_status == '2' ">Rejected</td>
												<?php if($this->session->userdata('isDepartmentHead') == 'YES'){ ?>
												<td ng-if="data.dh_status == '0' ">
															
													<div class="iCompassTip" style="text-align:center;" >
														<a href="" data-toggle="modal" data-target="#approvalForm_{{data.mid}}">
															<img  class="leaveApprove" src="<?php echo base_url(); ?>assets/images/icon/approve.png" alt="" title="Approve"  style="cursor: pointer;" />
														</a>
														<a href="" data-toggle="modal" data-target="#rejectReson_{{data.mid}}">
															<img  class="leaveReject" src="<?php echo base_url(); ?>assets/images/icon/reject.png" alt="" title="Reject" style="cursor: pointer;"  />
														</a>
													</div>
													<div class="modal fade" id="approvalForm_{{data.mid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																	<h4 class="modal-title" id="exampleModalLabel">Do you want to approve this mid year review ?<div id="head" style="display:inline;"></div></h4>
																</div>
																<div class="modal-body">
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-danger" data-dismiss="modal">NO</button> 
																	<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success" ng-click="midYearApporved(data.mid);" >YES</button>
																</div>
															</div>
														</div>
													</div>
													<div class="modal fade" id="rejectReson_{{data.mid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal">&times;</button>
																	<h4 class="modal-title" id="exampleModalLabel">Do you want to reject this mid year review ?<div id="head" style="display:inline;"></div></h4>
																</div>
																<div class="modal-body">
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-danger" data-dismiss="modal">NO</button> 
																	<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success"  ng-click="midYearRejected(data.mid);" >YES</button>
																</div>
															</div>
														</div>
													</div>
												
												</td>
												<?php } else { ?>
												<td ng-if="data.dh_status == '0' ">Pending</td>
												<?php } ?>
												<td ng-if="data.dh_status == '1' ">Approved</td>
												<td ng-if="data.dh_status == '2' ">Rejected</td>
												
												<td><textarea name="remark"  rows="1" style="width: 110px;" ng-keyup="updateRemark($event, data.mid)" >{{data.remark}}</textarea></td>
											</tr>
											<tr ng-show="filteredItems == 0">
												<td colspan="7" align="center">No Data</td>
											</tr>
										</tbody>
									</table>
								</div> 
							</div>
						</div> 
					</div> 
					<div class="row">
						<div class="col-lg-12" ng-show="filteredItems > 0">    
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