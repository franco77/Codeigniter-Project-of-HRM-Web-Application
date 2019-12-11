<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="LeaveRequest" ng-init="init('<?php echo base_url() ?>')">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3">
					<div class="form-content">
						<?php $this->load->view('timesheet/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">  
					<legend class="pkheader">Leave Request</legend> 
					<div class="row well"> 
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Track Leave Request </legend>
							 <div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-2 col-form-label">Month </label>
									<div class="col-sm-10">
										<select ng-model="searchMonth" name="searchMonth" id="searchMonth" class="form-control input-sm"> 
											<option value="" >ALL</option>
											<option value="01" >January</option>
											<option value="02" >February</option>
											<option value="03" >March</option>
											<option value="04" >April</option>
											<option value="05" >May</option>
											<option value="06" >June</option>
											<option value="07" >July</option>
											<option value="08" >August</option>
											<option value="09" >September</option>
											<option value="10" >October</option>
											<option value="11" >November</option>
											<option value="12" >December</option> 
										</select>
									</div>
								</div>
							 </div>
							 <div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-2 col-form-label">Year </label>
									<div class="col-sm-10">
										<select ng-model="searchYear" name="searchYear"  id="searchYear" class="form-control input-sm">
										  <?php
										  $yr=date("Y");
										  for ($j=$yr;$j>=2009;$j--){
										 ?>
											<option value="<?php echo($j)?>"  ><?php echo($j)?></option>
										 <?php 
										 }?>
										</select> 
									</div>
								</div>
							 </div>
							 <div class="col-md-5">
								<div class="form-group row">
									<label for="staticEmail" class="col-sm-2 col-form-label">Status </label>
									<div class="col-sm-10">
										
                                    <select name="searchStatus" ng-model="searchStatus" class="form-control">
										<option value="" >All</option>
										<option value="P">Pending</option>
										<option value="A">Approved</option>
										<option value="R" >Rejected</option>
										<option value="W" >Withdraw</option>
										<option value="CP" >Cancel Pending</option>
										<option value="CR" >Cancel Reject</option>
										<option value="CA" >Cancel Approved</option>
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
						<div class="table-responsive no-padding">
							<table class="table table-striped table-bordered table-condensed">
								<tr class="info">
									<th width="5%">Sl#</th>
									<th width="20%">Request From</th>
									<th width="10%">From</th>
									<th width="10%">To</th>
									<th width="5%">Type</th>
									<th width="15%">Reason</th>
									<th width="20%">Req. Date</th>
									<th width="15%">Status/Action</th>
								</tr>
								<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
									<td>{{$index+1}}</td>
									<td>{{data.full_name}}</td>
									<td >{{data.leave_from}}</td>
									<td>{{data.leave_to}}</td>
									<td ng-if="data.leave_type == 'P'">PL</td>
									<td ng-if="data.leave_type == 'S'">SL</td>
									<td ng-if="data.leave_type == 'M'">ML</td>
									<td><a data-toggle="modal" data-target="#myModal_{{$index}}" >{{ data.absence_reason | limitTo: 20 }} {{data.absence_reason.length < 20 ? '' : '...'}}</a>
										<!-- Modal -->
										<div class="modal fade" id="myModal_{{$index}}" role="dialog">
											<div class="modal-dialog">
												<!-- Modal content-->
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title">View Regularize Application Details</h4>
													</div>
													<div class="modal-body">
														<div class="resource_box">
															<table width="100%" border="0" cellspacing="0" cellpadding="0">
																<tr>
																	<td width="25%"><strong>Request From</strong></td>
																	<td width="75%">{{data.full_name}}</td>
																</tr>
																<tr>
																	<td><strong>Request To</strong></td>
																	<td>{{data.rep_full_name}}</td>
																</tr>
																<tr>
																	<td><strong>From Date</strong></td>
																	<td>{{data.leave_from | date}}</td>
																</tr>
																<tr>
																	<td><strong>To Date</strong></td>
																	<td>{{data.leave_to | date}}</td>
																</tr>
																<tr>
																	<td><strong>Reason</strong></td>
																	<td>{{data.absence_reason}}</td>
																</tr>
																<tr>
																	<td><strong>Request Date</strong></td>
																	<td>{{data.app_dt | date  }}</td>
																</tr>
																<tr>
																	<td><strong>Status</strong></td>
																	<td ng-if="data.status == 'P'"> Pending </td>
																	<td ng-if="data.status == 'A'"> Approved on <strong>{{data.action_dt | date  }}</strong> </td>
																	<td ng-if="data.status == 'R'"> Rejected on <strong>{{data.action_dt | date  }}</strong> </td>
																	<td ng-if="data.status == 'W'"> Withdrawn on <strong>{{data.w_c_dt | date  }}</strong> </td>
																	<td ng-if="data.status == 'CP'"> Cancel Leave Request on <strong>{{data.w_c_dt | date  }}</strong> </td>
																	<td ng-if="data.status == 'CR'"> Cancel Request Rejected on <strong>{{data.w_c_dt | date  }}</strong> </td>
																	<td ng-if="data.status == 'CA'"> Cancel Request Approved on <strong>{{data.w_c_dt | date  }}</strong> </td>
																</tr>
																<tr ng-if="data.status == 'R'">
																	<td><strong>Rejected Reason</strong></td>
																	<td>{{data.reject_reason}}</td>
																</tr>
																<tr ng-if="data.status == 'W'">
																	<td><strong>Withdraw Reason</strong></td>
																	<td>{{data.w_c_reason}}</td>
																</tr>
																<tr ng-if="data.status == 'CP'">
																	<td><strong>Cancel Reason</strong></td>
																	<td>{{data.w_c_reason}}</td>
																</tr>
															</table>
														</div>
													</div>
													<div class="modal-footer">
														<!--<button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>-->
														<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
													</div>
												</div>
											</div>
										</div>
										<!-- Modal -->
									</td>
									<td>{{data.app_dt}}</td>
									<td ng-if="data.status == 'A'" ><div class="iCompassTip" title="{{data.action_dt}}">Approved</div></td>
									<td ng-if="data.status == 'R'" ><div class="iCompassTip" title="{{data.action_dt}} - {{data.reject_reason}}">Rejected</div></td>
									<td ng-if="data.status == 'W'"><div class="iCompassTip" title="{{data.w_c_dt}} - {{data.w_c_reason}}">Withdraw</div></td>
									<td ng-if="data.status == 'CP'"><div class="iCompassTip" title="{{data.action_dt}} - {{data.reject_reason}}">Cancel Pending</div></td>
									<td ng-if="data.status == 'CR'"><div class="iCompassTip" title="{{data.action_dt}} - {{data.reject_reason}}">Cancel Rejected</div></td>
									<td ng-if="data.status == 'CA'"><div class="iCompassTip" title="{{data.action_dt}}">Cancel Approved</div></td>
									<td ng-if="data.status == 'P'" >
										<div class="iCompassTip" >
											<a href="" data-toggle="modal" data-target="#approveConfirmation_{{data.application_id}}">
												<img  class="leaveApprove" src="<?php echo base_url(); ?>assets/images/icon/approve.png" alt="" title="Approve" style="cursor: pointer;"  />
											</a>
											<a href="" data-toggle="modal" data-target="#rejectReson_{{data.application_id}}">
												<img  class="leaveReject" src="<?php echo base_url(); ?>assets/images/icon/reject.png" alt="" title="Reject" style="cursor: pointer;"  />
											</a>
										</div>
										<div class="modal fade" id="approveConfirmation_{{data.application_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
													 <button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title" id="exampleModalLabel">Leave Request Approve Confirmation <div id="head" style="display:inline;"></div></h4>
													</div>
														<div class="modal-body">
															<h5 style="color:#f00;">Are sure want to approve {{data.full_name}}'s  <b><span ng-if="data.leave_type == 'P'">PL</span><span ng-if="data.leave_type == 'S'">SL</span></b> leave request from  <b> {{data.leave_from | date}}</b> to <b>{{data.leave_to | date}} </b> of  <b> {{data.no_of_days}}  days</b> ?</h5>
															<h4 style="color:#900e0e;" id="errmsgApprove">{{errmsgApprove}}</h4>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-danger" data-dismiss="modal">No</button> 
															<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success"  ng-click="leaveApporved(data.application_id);" >Yes</button>
														</div>
												</div>
											</div>
										</div>
										<div class="modal fade" id="rejectReson_{{data.application_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
													 <button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title" id="exampleModalLabel">Leave Request <div id="head" style="display:inline;"></div></h4>
													</div>
														<div class="modal-body">
															<h5>Reason <span class="red">*</span></h5>
															<input type="hidden" name="application_id" id="application_id" required>
															<input type="hidden" name="status" id="status" required>
															<textarea name="reason" id="reason_{{data.application_id}}"  ng-model="reasond" required="" cols="80" rows="7"></textarea>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> 
															<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success"  ng-click="leaveRejected(data.application_id);" >Submit</button>
														</div>
												</div>
											</div>
										</div>
									</td>
								</tr>
								
								<tr ng-show="filteredItems == 0">
									<td colspan="8" align="center">No records found</td>
								</tr>
							</table>
							<div class="row ">
								<div class="col-md-2">
									<select ng-model="entryLimit" class="form-control input-sm">
										<option ng-selected="entryLimit">10</option>
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
									<h5>Filtered {{ filtered.length }} of {{ totalItems}} total update records</h5>
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