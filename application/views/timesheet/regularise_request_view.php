<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="RegularizeAttendance" ng-init="init('<?php echo base_url() ?>')">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('timesheet/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9 center-xs">
					<legend class="pkheader">Regularize Attendance Request</legend> 
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Track Regularization Request </legend>
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
										<th width="5%">Sl#</th>
										<th width="20%">Request From</th>
										<th width="10%">From</th>
										<th width="10%">To</th>
										<th width="20%">Reason</th>
										<th width="20%">Req. Date</th>
										<th width="15%">Action</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>{{data.full_name}}</td>
										<td>{{data.from_dates | date}}</td>
										<td>{{data.to_dates | date}}</td>
										
										<td><a data-toggle="modal" data-target="#myModal_{{$index}}" >{{ data.reason | limitTo: 20 }} {{data.reason.length < 20 ? '' : '...'}}</a>
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
																		<td>{{data.from_dates | date}}</td>
																	</tr>
																	<tr>
																		<td><strong>To Date</strong></td>
																		<td>{{data.to_dates | date}}</td>
																	</tr>
																	<tr>
																		<td><strong>Reason</strong></td>
																		<td>{{data.reason}} <span ng-if="data.reason_date != '' && data.reason_date != '0000-00-00'">-  {{data.reason_date }}</span> <span ng-if="data.reason_hour != '0'">(  {{data.reason_hour }} Hours )</span></td>
																	</tr>
																	<tr>
																		<td><strong>Request Date</strong></td>
																		<td>{{data.req_dates | date  }}</td>
																	</tr>
																	<tr>
																		<td><strong>Status</strong></td>
																		<td ng-if="data.status == 'P'"> Pending </td>
																		<td ng-if="data.status == 'A'"> Approved on <strong>{{data.act_date | date  }}</strong> </td>
																		<td ng-if="data.status == 'R'"> Rejected on <strong>{{data.act_date | date  }}</strong> </td>
																	</tr>
																	<tr ng-if="data.status == 'R'">
																		<td><strong>Rejected Reason</strong></td>
																		<td>{{data.rej_reason}}</td>
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
										<td>{{data.req_dates}}</td>
											<td ng-if="data.status == 'P'">
												<div class="iCompassTip" ><center>
													<a href="" data-toggle="modal" data-target="#approveConfirmation_{{data.attd_req_id}}">
														<img  class="leaveApprove" src="<?php echo base_url(); ?>assets/images/icon/approve.png" alt="" title="Approve" style="cursor: pointer;"  />
													</a>
													<a href="" data-toggle="modal" data-target="#rejectReson_{{data.attd_req_id}}">
														<img  class="leaveReject" src="<?php echo base_url(); ?>assets/images/icon/reject.png" alt="" title="Reject" style="cursor: pointer;"  />
													</a>
													</center>
												</div>
												<div class="modal fade" id="approveConfirmation_{{data.attd_req_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
															 <button type="button" class="close" data-dismiss="modal">&times;</button>
																<h4 class="modal-title" id="exampleModalLabel">Regularization Request Approve Confirmation <div id="head" style="display:inline;"></div></h4>
															</div>
																<div class="modal-body">
																	<h5 style="color:#f00;">Are sure want to approve {{data.full_name}}'s regularization request from  <b> {{data.from_dates}}</b> to <b>{{data.to_dates}} </b> of  <b> {{data.no_of_days}}  days</b> ?</h5>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-danger" data-dismiss="modal">No</button> 
																	<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success"  ng-click="leaveApporved(data.attd_req_id);" >Yes</button>
																</div>
														</div>
													</div>
												</div>
												<div class="modal fade" id="rejectReson_{{data.attd_req_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
															 <button type="button" class="close" data-dismiss="modal">&times;</button>
																<h4 class="modal-title" id="exampleModalLabel">Regularization Request <div id="head" style="display:inline;"></div></h4>
															</div>
																<div class="modal-body">
																	<h5>Reason <span class="red">*</span></h5>
																	<textarea name="reason" id="reason_{{data.attd_req_id}}"  ng-model="reasond" required="" cols="80" rows="7"></textarea>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> 
																	<button type="button" name="can_submit" value="cancel_leave" class="btn btn-success"  ng-click="leaveRejected(data.attd_req_id);" >Submit</button>
																</div>
														</div>
													</div>
												</div>
											</td>
										<td ng-if="data.status == 'A'" class="text-center">Approved</td>
										<td ng-if="data.status == 'R'" class="text-center">Rejected</td>
									</tr>
									
									<tr ng-show="filteredItems == 0">
										<td colspan="8" align="center">No records found</td>
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
										<option>100</option> 
										<option>150</option> 
										<option>200</option>
									</select>
								</div>
								<!--<div class="col-md-3">Search:
									<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
								</div> -->
								<div class="col-md-5">
									<h5>Filtered {{ filtered.length }} of {{ totalItems}} total update records</h5>
								</div>
							</div>
						</div>
						<!-- /.table-responsive -->
						<div class="col-lg-12" ng-show="filteredItems == 0">
							<div class="col-lg-12">
							<h4>No records found</h4>
							</div> 
						</div>  
					</div>
					<div class="row" ng-show="filteredItems > 0">    
							<div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-sm" max-size="entryLimit" boundary-link-numbers="true" rotate="false" previous-text="&laquo;" next-text="&raquo;"></div> 
					</div>
				</div>
				<div class="clearfix"></div> 
			</div>
			<div class="clearfix"></div>
		</div> 
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
