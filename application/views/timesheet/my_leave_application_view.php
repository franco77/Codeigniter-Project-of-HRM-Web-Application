<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?> 
<div class="section main-section" ng-app="myApp" ng-controller="MyleaveApplication" ng-init="init('<?php echo base_url() ?>')">
    <div class="container page-content"> 
		<div class="form-content">
			<div class="mt mb">
				<div class="col-md-3">
					<div class="form-content">
						<?php $this->load->view('timesheet/left_sidebar');?>
					</div>
				</div>
				<div class="col-md-9">
					<legend class="pkheader">My Leave Application <small>(Leave application details)</small></legend> 
					<div class="row well">
						<div class="row pkdsearch">
							 <legend class="form_title col-md-12">Track Status of My Leave Application </legend>
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
											<option value="<?php echo($j)?>"><?php echo($j)?></option>
										 <?php 
										 }?>
										</select> 
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
										<th width="5%">SL.</th>
										<th width="15%">Reporting Mgr</th>
										<th width="15%">From</th>
										<th width="15%">To</th>
										<th width="5%">Type</th>
										<th width="15%">Reason</th>
										<th width="18%">Req. Date</th>
										<th width="12%">Status/Action</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="data in filtered = (getcustname | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
										<td>{{$index+1}}</td>
										<td>{{data.full_name}}</td>
										<td ng-if="data.leavefromhalfday == 'Y'">{{data.leave_from}}</td>
										<td ng-if="data.leavefromhalfday == 'N'">{{data.leave_from}}</td>
										<td ng-if="data.leavetohalfday == 'Y'">{{data.leave_to}}</td>
										<td ng-if="data.leavetohalfday == 'N'">{{data.leave_to}}</td>
										<td ng-if="data.leave_type == 'P'">PL</td>
										<td ng-if="data.leave_type == 'S'">SL</td>
										<td ng-if="data.leave_type == 'M'">ML</td>
										<td><a href="" data-toggle="modal" data-target="#myModal_{{data.application_id}}">{{ data.absence_reason | limitTo: 30 }} {{data.absence_reason.length < 30 ? '' : '...'}}</a></td>
										<td>{{data.app_dt}}</td>
										<td ng-if="data.status == 'A'">Approved&nbsp;&nbsp;&nbsp;&nbsp;<a ng-if="data.applied_month == '1'" data-toggle="modal" data-id="{{data.application_id}}" data-status-id="{{data.status}}" title="Cancel" class="cancel" href="#addBookDialog"><img src="<?php echo base_url();?>assets/images/icon/cancel.png"></a></td>
										<td ng-if="data.status == 'R'">Rejected</td>
										<td ng-if="data.status == 'P'">Pending&nbsp;&nbsp;<a data-toggle="modal" data-id="{{data.application_id}}" data-status-id="{{data.status}}" title="Add this item" class="cancel" href="#addBookDialog"><img src="<?php echo base_url();?>assets/images/icon/withdraw.png"></a></td>
										<td ng-if="data.status == 'W'">Withdraw</td>
										<td ng-if="data.status == 'CP'">Cancel Pending</td>
										<td ng-if="data.status == 'CR'">Cancel Rejected</td>
										<td ng-if="data.status == 'CA'">Cancel Approved</td>
									</tr>
									<tr ng-show="filteredItems == 0">
										<td colspan="8" align="center">No records found</td> 
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
								<div class="col-md-7 text-center">&nbsp; <br/><h5>Filtered {{ filtered.length }} of {{ totalItems}} total update records</h5></div>
								<!--<div class="col-md-3">Search:
									<input type="text" ng-model="search" ng-change="filter()" placeholder="Search" class="form-control input-sm" />
								</div>-->
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
	<div class="modal fade" id="addBookDialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
				 <button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">Leave Request <div id="head" style="display:inline;"></div></h4>
				</div>
				<form method="POST" action="">	
					<div class="modal-body">
						<h5>Reason <span class="red">*</span></h5>
						<input type="hidden" name="application_id" id="application_id" required>
						<input type="hidden" name="status" id="status" required>
						<textarea name="reason" required cols="80" rows="7"></textarea>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> 
						<button type="submit" name="can_submit" value="cancel_leave" class="btn btn-success">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div ng-repeat="data in getcustname">
	<div class="modal fade" id="myModal_{{data.application_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">View Leave Application Details</h4>
				</div>
				<div class="modal-body">
					<table class="table table-striped table-bordered table-condensed">
						<tbody>
							<tr>
								<td width="25%"><strong>Request From</strong></td>
								<td width="75%">{{data.emp_name}}</td>
							</tr>
							<tr>
								<td><strong>Request To</strong></td>
								<td>{{data.full_name}}</td>
							</tr>
							<tr>
								<td><strong>From Date</strong></td>
								<td>{{data.leave_from}}</td>
							</tr>
							<tr>
								<td><strong>To Date</strong></td>
								<td>{{data.leave_to}}</td>
							</tr>
							<tr>
								<td><strong>Reason</strong></td>
								<td>{{ data.absence_reason}}</td>
							</tr>
							<tr>
								<td><strong>Request Date</strong></td>
								<td>{{data.app_dt}}</td>
							</tr>              
							<tr>
								<td><strong>Status</strong></td>
								<td ng-if="data.status == 'A'">Approved on <strong>{{data.action_dt}}</strong></td>
								<td ng-if="data.status == 'R'">Rejected on <strong>{{data.action_dt}} {{data.reject_reason}}</strong></td>
								<td ng-if="data.status == 'P'">Pending</td>
								<td ng-if="data.status == 'W'">Withdraw on <strong>{{data.w_c_dt}}</strong></td>
								<td ng-if="data.status == 'CP'">Cancel Pending on <strong>{{data.w_c_dt}}</strong></td>
								<td ng-if="data.status == 'CR'">Cancel Rejected on <strong>{{data.w_c_dt}}</strong></td>
								<td ng-if="data.status == 'CA'">Cancel Approved on <strong>{{data.w_c_dt}}</strong></td>
								
							</tr>
						</tbody> 
					</table>
				
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal">Ok</button> 
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- Reason Modal -->
<script>
$(document).on("click", ".cancel", function () {
     var myBookId = $(this).data('id');
	 var status = $(this).data('status-id');
	$(".modal-body #status").val( status );
     $(".modal-body #application_id").val( myBookId );
	 if(status == 'P'){
		 $("#head").text("Withdraw");
	 } else if(status == 'A'){
		  $("#head").text("Cancel");
	 }
});
</script>
