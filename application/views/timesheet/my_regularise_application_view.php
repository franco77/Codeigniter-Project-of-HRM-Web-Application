<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 

<div class="section main-section" ng-app="myApp" ng-controller="myRegulariseApplication" ng-init="init('<?php echo base_url() ?>')">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3 center-xs">
					<div class="form-content">
						<?php $this->load->view('timesheet/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9"> 
					<legend class="pkheader">My Regularize Application</legend> 
					<div class="row well"> 
						<div class="row pkdsearch">
							<legend class="form_title col-md-12">Track Regularize Status of My Application </legend>
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
												<option value="<?php echo($j); ?>"  ><?php echo($j); ?></option>
											<?php 
											} ?>
										</select> 
									</div>
								</div>
							 </div>
							 <div class="col-md-2">
								<input type="button" id="btnSearch" name="btnSearch" class="btn btn-primary" value="Search" ng-click="advanceFilter();" /> 
							 </div>
						</div>
						<div class="table-responsive no-padding">
							<table class="table table-striped table-bordered table-condensed">
								<tr class="info"> 
									<th width="5%">Sl.</th>
									<th width="15%">Reporting Person</th>
									<th width="15%">From</th>
									<th width="15%">To</th>
									<th width="20%">Reason</th>
									<th width="20%">Req. Date</th>
									<th width="10%">Status</th>
								</tr>
								<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
									<td>{{$index+1}}</td>
									<td>{{data.rep_full_name}}</td>
									<td>{{data.from_date}}</td>
									<td>{{data.to_date}}</td>
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
																	<td>{{data.from_date | date}}</td>
																</tr>
																<tr>
																	<td><strong>To Date</strong></td>
																	<td>{{data.to_date | date}}</td>
																</tr>
																<tr>
																	<td><strong>Reason</strong></td>
																	<td>{{data.reason}} <span ng-if="data.reason_date != '' && data.reason_date != '0000-00-00'">-  {{data.reason_date }}</span> <span ng-if="data.reason_hour != '0'">(  {{data.reason_hour }} Hours )</span>  </td>
																</tr>
																<tr>
																	<td><strong>Request Date</strong></td>
																	<td>{{data.req_date | date  }}</td>
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
									<td>{{data.req_date | date  }}</td> 
									<td ng-if="data.status == 'P'"> Pending </td>
									<td ng-if="data.status == 'A'"> Approved </td>
									<td ng-if="data.status == 'R'"> Rejected </td>
																				
								</tr>
								<tr ng-show="filteredItems == 0">
									<td colspan="7" align="center">Not data found</td>
																				
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
								<div class="col-md-5">
									<h5>Filtered {{ filtered.length }} of {{ totalItems}} total update records</h5>
								</div>
							</div>
						</div> 
					</div>
					<div class="row">
						<div ng-show="filteredItems > 0">    
							<div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="&laquo;" next-text="&raquo;"></div> 
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


	