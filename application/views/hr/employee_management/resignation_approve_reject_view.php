<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="reginationApprove" ng-init="init('<?php echo base_url() ?>')">
    <div class="container"> 
		<div class="form-content page-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content page-content">
						<?php $this->load->view('hr/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<legend class="pkheader">All Applied Resignation Letter</legend> 
					<div class="row well">
					
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Search View Applied Resignation Letter </legend>
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
									<label for="staticEmail" class="col-sm-4 col-form-label">Year </label>
									<div class="col-sm-8">
										<select ng-model="year" name="year" id="year" class="form-control input-sm"> 
											<?php $yr=date("Y");
												for($j=$yr;$j>=2014;$j--){
												if ($j == $year){
												?>
												<option value="<?php echo $j;?>" selected><?php echo $j;?></option>
												<?php }else{?>
												<option value="<?php echo $j;?>"><?php echo $j;?></option>
												<?php }
												}?> 
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-5"></div>
							 <div class="col-md-2">
								<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary" value="Search" ng-click="advanceFilter();" /> 
							 </div>
						</div>
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
										<th>Apply Date</th>
										<th>Emp Action</th>
										<th>R Manager Action</th>
										<th>HR Action</th> 
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit"> 
										<td><a href="#" data-toggle="modal" data-target="#myModal_{{data.rid}}">{{data.full_name}}</a></td>
										<td>{{data.loginhandle}}</td>
										<td>{{data.created_date}}</td>
										<td>
											<span class="label label-info" ng-if="data.emp_status == 0" >Active</span>
											<span class="label label-info" ng-if="data.emp_status == 1" >Canceled</span>
										</td>
										<td>
											<span class="label label-info" ng-if="data.rm_status == 0" >Active</span>
											<span class="label label-info" ng-if="data.rm_status == 1" >Approved</span>
											<span class="label label-info" ng-if="data.rm_status == 2" >Rejected</span>
										</td>
										<td>
											<?php
											if($this->session->userdata('user_type') == 'HRM' || $this->session->userdata('user_type') == 'ADMINISTRATOR')
											{?>
											<span  ng-if="data.hr_status == 0 && data.emp_status == 0" >
												<div class="iCompassTip" >
													<img  class="leaveApprove" src="<?php echo base_url(); ?>assets/images/icon/approve.png" alt="" title="Approve" data-toggle="modal" data-target="#approveRemark_{{data.rid}}" style="cursor: pointer;" />
													<a href="" data-toggle="modal" data-target="#rejectReson_{{data.rid}}">
														<img  class="leaveReject" src="<?php echo base_url(); ?>assets/images/icon/reject.png" alt="" title="Reject" style="cursor: pointer;"  />
													</a>
												</div>
												<div class="modal fade" id="approveRemark_{{data.rid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
															 <button type="button" class="close" data-dismiss="modal">&times;</button>
																<h4 class="modal-title" id="exampleModalLabel">Resignation Request <div id="head" style="display:inline;"></div></h4>
															</div>
																<div class="modal-body">
																	<h5>Remarks <span class="red">*</span></h5>
																	<input type="hidden" name="rid" id="rid" required>
																	<input type="hidden" name="status" id="status" required>
																	<textarea name="remarks" id="remarks_{{data.rid}}"  ng-model="remarks" required cols="80" rows="7"></textarea>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> 
																	<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success"  ng-click="resignationApporved(data.rid);" ng-disabled="myForm.user.$dirty && myForm.user.$invalid" >Submit</button>
																</div>
														</div>
													</div>
												</div>
												<div class="modal fade" id="rejectReson_{{data.rid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
															 <button type="button" class="close" data-dismiss="modal">&times;</button>
																<h4 class="modal-title" id="exampleModalLabel">Resignation Request <div id="head" style="display:inline;"></div></h4>
															</div>
																<div class="modal-body">
																	<h5>Reason <span class="red">*</span></h5>
																	<input type="hidden" name="rid" id="rid" required>
																	<input type="hidden" name="status" id="status" required>
																	<textarea name="reason" id="reason_{{data.rid}}"  ng-model="reasond" required="" cols="80" rows="7"></textarea>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> 
																	<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success"  ng-click="resignationRejected(data.rid);" >Submit</button>
																</div>
														</div>
													</div>
												</div>
											</span>
											<?php } ?>
											<span class="label label-info"  ng-if="data.hr_status == 0 && data.emp_status == 1" >Active</span>
											<span class="label label-info" ng-if="data.hr_status == 1" >Approved</span>
											<span class="label label-info" ng-if="data.hr_status == 2" >Rejected</span>
										</td>
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
	<div class="modal fade" id="myModal_{{data.rid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">View Resignation Application Details</h4>
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
								<td><strong>Subject</strong></td>
								<td>{{data.subject}}</td>
							</tr>
							<tr>
								<td><strong>Reason of Separation</strong></td>
								<td>{{data.separation_name}}</td>
							</tr>
							<tr>
								<td><strong>LWD</strong></td>
								<td>{{data.lwd}}</td>
							</tr>
							<tr>
								<td><strong>RM Desc</strong></td>
								<td>{{data.rm_desc}}</td>
							</tr>
							<tr>
								<td><strong>HR Desc</strong></td>
								<td>{{data.hr_desc}}</td>
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
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
				</div>
			</div>
		</div>
	</div>
</div>
